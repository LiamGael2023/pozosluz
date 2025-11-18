-- =====================================================
-- Base de Datos: Calculadora de Pozos de Luz
-- Normativa Peruana RNE A.010 / A.020
-- =====================================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS pozos_luz
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE pozos_luz;

-- =====================================================
-- Tabla: tipos_edificacion
-- =====================================================
CREATE TABLE IF NOT EXISTS tipos_edificacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    dimension_minima_tipo_a DECIMAL(4,2) NOT NULL COMMENT 'Dimensión mínima para ambientes Tipo A (m)',
    dimension_minima_tipo_b DECIMAL(4,2) NOT NULL COMMENT 'Dimensión mínima para ambientes Tipo B (m)',
    descripcion TEXT,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos iniciales de tipos de edificación según RNE
INSERT INTO tipos_edificacion (codigo, nombre, dimension_minima_tipo_a, dimension_minima_tipo_b, descripcion) VALUES
('unifamiliar', 'Vivienda Unifamiliar', 2.00, 1.80, 'Edificación con una sola unidad de vivienda'),
('bifamiliar', 'Vivienda Bifamiliar', 2.00, 1.80, 'Edificación con dos unidades de vivienda'),
('multifamiliar', 'Edificación Multifamiliar', 2.20, 2.00, 'Edificación con tres o más unidades de vivienda');

-- =====================================================
-- Tabla: tipos_ambiente
-- =====================================================
CREATE TABLE IF NOT EXISTS tipos_ambiente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    factor_perpendicular DECIMAL(5,4) NOT NULL COMMENT 'Factor para calcular distancia perpendicular',
    descripcion TEXT,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos iniciales de tipos de ambiente según RNE
INSERT INTO tipos_ambiente (codigo, nombre, factor_perpendicular, descripcion) VALUES
('tipoA', 'Tipo A - Dormitorios, Sala, Comedor, Estudio', 0.3333, 'Ambientes habitables principales - Factor 1/3 de altura'),
('tipoB', 'Tipo B - Cocina, Patio de Servicio, Pasajes', 0.2500, 'Ambientes de servicio - Factor 1/4 de altura');

-- =====================================================
-- Tabla: calculos
-- Historial de cálculos realizados
-- =====================================================
CREATE TABLE IF NOT EXISTS calculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_edificacion VARCHAR(50) NOT NULL,
    altura DECIMAL(6,2) NOT NULL COMMENT 'Altura del paramento más bajo (m)',
    numero_pisos INT NOT NULL,
    tipo_ambiente VARCHAR(50) NOT NULL,
    lados_edificados INT NOT NULL COMMENT 'Número de lados con edificaciones propias',
    dimension_minima DECIMAL(6,2) NOT NULL COMMENT 'Dimensión mínima calculada (m)',
    distancia_perpendicular DECIMAL(6,2) NOT NULL COMMENT 'Distancia perpendicular mínima (m)',
    area_minima DECIMAL(8,2) NOT NULL COMMENT 'Área mínima del pozo (m²)',
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_tipo_edificacion (tipo_edificacion),
    INDEX idx_tipo_ambiente (tipo_ambiente),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: normativa_referencias
-- Referencias a la normativa aplicable
-- =====================================================
CREATE TABLE IF NOT EXISTS normativa_referencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    url VARCHAR(500) DEFAULT NULL,
    fecha_publicacion DATE DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Referencias normativas
INSERT INTO normativa_referencias (codigo, nombre, descripcion, fecha_publicacion) VALUES
('A.010', 'Norma Técnica A.010 - Condiciones Generales de Diseño', 'Establece los criterios y requisitos mínimos de diseño arquitectónico', '2006-06-08'),
('A.020', 'Norma Técnica A.020 - Vivienda', 'Establece las condiciones de diseño para edificaciones de vivienda', '2006-06-08'),
('DS-011-2006', 'D.S. Nº 011-2006-VIVIENDA', 'Aprueba el Reglamento Nacional de Edificaciones', '2006-06-08'),
('RM-188-2021', 'R.M. Nº 188-2021-VIVIENDA', 'Modifica la Norma Técnica A.010 y A.020 del RNE', '2021-07-07');

-- =====================================================
-- Vista: resumen_calculos
-- =====================================================
CREATE OR REPLACE VIEW resumen_calculos AS
SELECT
    DATE(created_at) as fecha,
    tipo_edificacion,
    tipo_ambiente,
    COUNT(*) as total_calculos,
    AVG(altura) as altura_promedio,
    AVG(dimension_minima) as dimension_promedio,
    AVG(area_minima) as area_promedio
FROM calculos
GROUP BY DATE(created_at), tipo_edificacion, tipo_ambiente
ORDER BY fecha DESC;
