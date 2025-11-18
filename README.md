# Calculadora de Pozos de Luz - RNE Perú

Aplicación web para calcular las dimensiones mínimas de pozos de luz según la normativa peruana (RNE A.010 y A.020).

## Características

- Cálculo según tipo de edificación (Unifamiliar, Bifamiliar, Multifamiliar)
- Soporte para ambientes Tipo A y Tipo B
- Cálculo de distancia perpendicular (1/3 o 1/4 de altura)
- Ajuste por tramos cada 18m de altura
- Historial de cálculos
- Diseño responsivo con Tabler.io
- Arquitectura MVC en PHP

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache con mod_rewrite habilitado

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/pozosluz.git
cd pozosluz
```

### 2. Configurar la base de datos

```bash
# Crear la base de datos e importar el esquema
mysql -u root -p < database/schema.sql
```

### 3. Configurar variables de entorno

```bash
cp .env.example .env
# Editar .env con tus credenciales de base de datos
```

### 4. Configurar Apache

Apuntar el DocumentRoot a la carpeta `public/` o usar el `.htaccess` de la raíz.

Ejemplo de VirtualHost:

```apache
<VirtualHost *:80>
    ServerName pozosluz.local
    DocumentRoot /var/www/pozosluz/public

    <Directory /var/www/pozosluz/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 5. Acceder a la aplicación

Abrir en el navegador: `http://localhost/pozosluz`

## Estructura del Proyecto

```
pozosluz/
├── app/
│   ├── Controllers/     # Controladores MVC
│   ├── Core/            # Clases base del framework
│   ├── Models/          # Modelos de datos
│   └── Views/           # Vistas (layouts, páginas)
├── config/              # Archivos de configuración
├── database/            # Scripts SQL
├── public/              # Punto de entrada público
│   ├── assets/          # CSS, JS, imágenes
│   └── index.php        # Front controller
└── README.md
```

## Normativa Aplicada

### Dimensiones Mínimas por Lado

| Tipo Edificación | Tipo A (m) | Tipo B (m) |
|------------------|------------|------------|
| Unifamiliar      | 2.00       | 1.80       |
| Bifamiliar       | 2.00       | 1.80       |
| Multifamiliar    | 2.20       | 2.00       |

### Distancia Perpendicular

- **Tipo A** (Dormitorios, Sala, Comedor): 1/3 de la altura
- **Tipo B** (Cocina, Servicios): 1/4 de la altura

### Referencias

- Norma Técnica A.010 - Condiciones Generales de Diseño
- Norma Técnica A.020 - Vivienda
- D.S. Nº 011-2006-VIVIENDA
- R.M. Nº 188-2021-VIVIENDA

## Licencia

MIT License

## Disclaimer

Esta calculadora es una herramienta de referencia. Consulte siempre la normativa vigente y a un profesional habilitado para proyectos reales.
