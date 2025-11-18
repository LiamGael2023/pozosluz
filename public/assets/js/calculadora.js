/**
 * Calculadora de Pozos de Luz - Frontend JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formCalculadora');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            calcularPozoLuz();
        });
    }
});

/**
 * Realizar cálculo mediante AJAX
 */
async function calcularPozoLuz() {
    const form = document.getElementById('formCalculadora');
    const formData = new FormData(form);

    try {
        const response = await fetch('/calcular', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            mostrarResultados(data.resultado);
        } else {
            mostrarErrores(data.errores);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar el cálculo. Por favor, intente nuevamente.');
    }
}

/**
 * Mostrar resultados en la interfaz
 */
function mostrarResultados(resultado) {
    // Ocultar placeholder y mostrar resultados
    document.getElementById('placeholder-resultados').style.display = 'none';
    document.getElementById('resultados-container').style.display = 'block';

    // Actualizar valores principales
    document.getElementById('resultado-dimension').textContent = resultado.dimension_minima.toFixed(2);
    document.getElementById('resultado-perpendicular').textContent = resultado.distancia_perpendicular.toFixed(2);
    document.getElementById('resultado-area').textContent = resultado.area_minima.toFixed(2);

    // Descripción de distancia perpendicular
    document.getElementById('resultado-perpendicular-desc').textContent =
        `Calculado como ${resultado.factor_perpendicular} de la altura (${resultado.altura}m)`;

    // Generar detalles del cálculo
    const detallesContainer = document.getElementById('detalles-calculo');
    detallesContainer.innerHTML = generarDetalles(resultado);

    // Scroll suave a los resultados en móvil
    if (window.innerWidth < 992) {
        document.getElementById('resultados-container').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

/**
 * Generar HTML de detalles del cálculo
 */
function generarDetalles(resultado) {
    const tiposEdificacion = {
        'unifamiliar': 'Vivienda Unifamiliar',
        'bifamiliar': 'Vivienda Bifamiliar',
        'multifamiliar': 'Edificación Multifamiliar'
    };

    const tiposAmbiente = {
        'tipoA': 'Tipo A (Dormitorios, Sala, Comedor)',
        'tipoB': 'Tipo B (Cocina, Servicios)'
    };

    const detalles = [
        { label: 'Tipo de Edificación', value: tiposEdificacion[resultado.tipo_edificacion] },
        { label: 'Tipo de Ambiente', value: tiposAmbiente[resultado.tipo_ambiente] },
        { label: 'Altura de Edificación', value: `${resultado.altura} m` },
        { label: 'Número de Pisos', value: resultado.numero_pisos },
        { label: 'Lados Edificados', value: resultado.lados_edificados },
        { label: 'Dimensión Base (Normativa)', value: `${resultado.dimension_base.toFixed(2)} m` },
        { label: 'Tramos de 18m Completos', value: resultado.tramos_completos },
        { label: 'Incremento por Tramos', value: `+${resultado.incremento_tramo.toFixed(2)} m` },
        { label: 'Factor Perpendicular', value: `${resultado.factor_perpendicular} de altura` }
    ];

    return detalles.map(d => `
        <div class="datagrid-item">
            <div class="datagrid-title">${d.label}</div>
            <div class="datagrid-content">${d.value}</div>
        </div>
    `).join('');
}

/**
 * Mostrar errores de validación
 */
function mostrarErrores(errores) {
    let mensaje = 'Por favor corrija los siguientes errores:\n\n';
    errores.forEach(error => {
        mensaje += `• ${error}\n`;
    });
    alert(mensaje);
}
