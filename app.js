/**
 * Calculadora de Pozos de Luz - Normativa Peruana RNE
 * Basado en Norma Técnica A.010 y A.020
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('calculatorForm');
    const resultados = document.getElementById('resultados');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        calcularPozoLuz();
    });
});

/**
 * Función principal de cálculo de pozos de luz
 */
function calcularPozoLuz() {
    // Obtener valores del formulario
    const tipoEdificacion = document.getElementById('tipoEdificacion').value;
    const alturaEdificacion = parseFloat(document.getElementById('alturaEdificacion').value);
    const numeroPisos = parseInt(document.getElementById('numeroPisos').value);
    const tipoAmbiente = document.getElementById('tipoAmbiente').value;
    const ladosEdificados = parseInt(document.getElementById('ladosEdificados').value);

    // Calcular dimensión mínima base según tipo de edificación
    let dimensionMinima = calcularDimensionMinima(tipoEdificacion, tipoAmbiente);

    // Calcular distancia perpendicular según altura y tipo de ambiente
    let distanciaPerpendicular = calcularDistanciaPerpendicular(alturaEdificacion, tipoAmbiente);

    // Ajustar por tramos de 18m
    const tramosCompletos = Math.floor(alturaEdificacion / 18);
    let incrementoTramo = 0;

    if (tramosCompletos > 0) {
        // Incremento por cada tramo adicional de 18m
        incrementoTramo = tramosCompletos * 0.25;
        dimensionMinima += incrementoTramo;
    }

    // La dimensión mínima no puede ser menor que la distancia perpendicular calculada
    const dimensionFinal = Math.max(dimensionMinima, distanciaPerpendicular);

    // Calcular área mínima
    const areaMinima = dimensionFinal * dimensionFinal;

    // Mostrar resultados
    mostrarResultados({
        dimensionMinima: dimensionFinal,
        distanciaPerpendicular: distanciaPerpendicular,
        areaMinima: areaMinima,
        tipoEdificacion: tipoEdificacion,
        tipoAmbiente: tipoAmbiente,
        alturaEdificacion: alturaEdificacion,
        numeroPisos: numeroPisos,
        ladosEdificados: ladosEdificados,
        tramosCompletos: tramosCompletos,
        incrementoTramo: incrementoTramo,
        dimensionBase: calcularDimensionMinima(tipoEdificacion, tipoAmbiente)
    });
}

/**
 * Calcula la dimensión mínima base según tipo de edificación y ambiente
 * Basado en RNE A.010 Art. 19 y A.020
 */
function calcularDimensionMinima(tipoEdificacion, tipoAmbiente) {
    const dimensiones = {
        'unifamiliar': {
            'tipoA': 2.00,  // Dormitorios, sala, comedor
            'tipoB': 1.80   // Cocina, servicios
        },
        'bifamiliar': {
            'tipoA': 2.00,  // Dormitorios, sala, comedor
            'tipoB': 1.80   // Cocina, servicios
        },
        'multifamiliar': {
            'tipoA': 2.20,  // Dormitorios, sala, comedor
            'tipoB': 2.00   // Cocina, servicios
        }
    };

    return dimensiones[tipoEdificacion][tipoAmbiente];
}

/**
 * Calcula la distancia perpendicular mínima según altura y tipo de ambiente
 * RNE A.010: 1/3 de altura para Tipo A, 1/4 para Tipo B
 */
function calcularDistanciaPerpendicular(altura, tipoAmbiente) {
    let factor;

    if (tipoAmbiente === 'tipoA') {
        // Dormitorios, estudios, salas, comedores: 1/3 de la altura
        factor = 1/3;
    } else {
        // Servicios, cocinas, pasajes, patios: 1/4 de la altura
        factor = 1/4;
    }

    return altura * factor;
}

/**
 * Obtiene el nombre legible del tipo de edificación
 */
function getNombreTipoEdificacion(tipo) {
    const nombres = {
        'unifamiliar': 'Vivienda Unifamiliar',
        'bifamiliar': 'Vivienda Bifamiliar',
        'multifamiliar': 'Edificación Multifamiliar'
    };
    return nombres[tipo];
}

/**
 * Obtiene el nombre legible del tipo de ambiente
 */
function getNombreTipoAmbiente(tipo) {
    const nombres = {
        'tipoA': 'Tipo A (Dormitorios, Sala, Comedor, Estudio)',
        'tipoB': 'Tipo B (Cocina, Servicios, Pasajes)'
    };
    return nombres[tipo];
}

/**
 * Muestra los resultados del cálculo en la interfaz
 */
function mostrarResultados(datos) {
    // Mostrar sección de resultados
    const resultados = document.getElementById('resultados');
    resultados.classList.remove('hidden');

    // Dimensión mínima
    document.getElementById('dimensionMinima').textContent = datos.dimensionMinima.toFixed(2);

    // Distancia perpendicular
    document.getElementById('distanciaPerpendicular').textContent = datos.distanciaPerpendicular.toFixed(2);

    // Descripción de la distancia perpendicular
    const factor = datos.tipoAmbiente === 'tipoA' ? '1/3' : '1/4';
    document.getElementById('descripcionDistancia').textContent =
        `Calculado como ${factor} de la altura (${datos.alturaEdificacion}m)`;

    // Área mínima
    document.getElementById('areaMinima').textContent = datos.areaMinima.toFixed(2);

    // Detalles del cálculo
    const detallesLista = document.getElementById('detallesLista');
    detallesLista.innerHTML = '';

    const detalles = [
        `Tipo de edificación: ${getNombreTipoEdificacion(datos.tipoEdificacion)}`,
        `Tipo de ambiente: ${getNombreTipoAmbiente(datos.tipoAmbiente)}`,
        `Altura de edificación: ${datos.alturaEdificacion} m`,
        `Número de pisos: ${datos.numeroPisos}`,
        `Lados edificados: ${datos.ladosEdificados}`,
        `Dimensión base normativa: ${datos.dimensionBase.toFixed(2)} m`,
        `Tramos de 18m completos: ${datos.tramosCompletos}`,
        `Incremento por tramos: +${datos.incrementoTramo.toFixed(2)} m`,
        `Factor de distancia perpendicular: ${datos.tipoAmbiente === 'tipoA' ? '1/3' : '1/4'} de altura`
    ];

    detalles.forEach(detalle => {
        const li = document.createElement('li');
        li.textContent = detalle;
        detallesLista.appendChild(li);
    });

    // Scroll suave hacia los resultados
    resultados.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/**
 * Función auxiliar para validar los inputs
 */
function validarFormulario() {
    const form = document.getElementById('calculatorForm');
    return form.checkValidity();
}
