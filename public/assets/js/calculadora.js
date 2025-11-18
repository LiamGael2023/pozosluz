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
    const baseUrl = window.BASE_URL || '';

    // Mostrar loading
    const btn = form.querySelector('button[type="submit"]');
    const btnText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Calculando...';
    btn.disabled = true;

    try {
        const response = await fetch(baseUrl + '/calcular', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            mostrarResultados(data.resultado);
        } else {
            mostrarErrores(data.errores || ['Error desconocido']);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar el cálculo. Por favor, intente nuevamente.');
    } finally {
        btn.innerHTML = btnText;
        btn.disabled = false;
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
        `Factor: ${resultado.factor_perpendicular} de ${resultado.altura}m`;

    // Generar gráfico del pozo de luz
    generarGraficoPozo(resultado);

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
 * Generar gráfico SVG del pozo de luz
 */
function generarGraficoPozo(resultado) {
    const container = document.getElementById('grafico-pozo');
    const dimension = resultado.dimension_minima;
    const altura = resultado.altura;
    const ladosEdificados = resultado.lados_edificados;

    // Tamaño del SVG
    const svgWidth = 400;
    const svgHeight = 300;
    const margin = 60;

    // Escala para el dibujo
    const pozoSize = Math.min(svgWidth - margin * 2, svgHeight - margin * 2) * 0.6;
    const centerX = svgWidth / 2;
    const centerY = svgHeight / 2;

    // Colores
    const colorEdificio = '#6c7a89';
    const colorPozo = '#e8f4f8';
    const colorLinea = '#206bc4';
    const colorTexto = '#1e293b';

    let svg = `
    <svg width="${svgWidth}" height="${svgHeight}" xmlns="http://www.w3.org/2000/svg">
        <!-- Fondo -->
        <rect width="100%" height="100%" fill="#f8fafc"/>

        <!-- Título -->
        <text x="${centerX}" y="25" text-anchor="middle" font-size="14" font-weight="600" fill="${colorTexto}">
            Vista en Planta del Pozo de Luz
        </text>
    `;

    // Dibujar edificaciones según lados edificados
    const buildingWidth = 30;
    const halfPozo = pozoSize / 2;

    // Edificaciones en los 4 lados (se muestran según ladosEdificados)
    const edificaciones = [
        { // Arriba
            x: centerX - halfPozo - buildingWidth,
            y: centerY - halfPozo - buildingWidth,
            width: pozoSize + buildingWidth * 2,
            height: buildingWidth
        },
        { // Derecha
            x: centerX + halfPozo,
            y: centerY - halfPozo,
            width: buildingWidth,
            height: pozoSize
        },
        { // Abajo
            x: centerX - halfPozo - buildingWidth,
            y: centerY + halfPozo,
            width: pozoSize + buildingWidth * 2,
            height: buildingWidth
        },
        { // Izquierda
            x: centerX - halfPozo - buildingWidth,
            y: centerY - halfPozo,
            width: buildingWidth,
            height: pozoSize
        }
    ];

    // Dibujar edificaciones
    for (let i = 0; i < 4; i++) {
        const ed = edificaciones[i];
        const isActive = i < ladosEdificados;
        svg += `
        <rect x="${ed.x}" y="${ed.y}" width="${ed.width}" height="${ed.height}"
              fill="${isActive ? colorEdificio : '#e2e8f0'}"
              stroke="${isActive ? '#4a5568' : '#cbd5e1'}"
              stroke-width="1"/>
        `;
    }

    // Dibujar el pozo de luz (centro)
    svg += `
        <rect x="${centerX - halfPozo}" y="${centerY - halfPozo}"
              width="${pozoSize}" height="${pozoSize}"
              fill="${colorPozo}" stroke="${colorLinea}" stroke-width="2"/>
    `;

    // Patrón de luz en el pozo
    svg += `
        <defs>
            <pattern id="lightPattern" patternUnits="userSpaceOnUse" width="10" height="10">
                <circle cx="5" cy="5" r="1" fill="${colorLinea}" opacity="0.2"/>
            </pattern>
        </defs>
        <rect x="${centerX - halfPozo}" y="${centerY - halfPozo}"
              width="${pozoSize}" height="${pozoSize}"
              fill="url(#lightPattern)"/>
    `;

    // Líneas de cota horizontal
    const cotaY = centerY + halfPozo + 35;
    svg += `
        <!-- Cota horizontal -->
        <line x1="${centerX - halfPozo}" y1="${cotaY}" x2="${centerX + halfPozo}" y2="${cotaY}"
              stroke="${colorLinea}" stroke-width="1.5"/>
        <line x1="${centerX - halfPozo}" y1="${cotaY - 5}" x2="${centerX - halfPozo}" y2="${cotaY + 5}"
              stroke="${colorLinea}" stroke-width="1.5"/>
        <line x1="${centerX + halfPozo}" y1="${cotaY - 5}" x2="${centerX + halfPozo}" y2="${cotaY + 5}"
              stroke="${colorLinea}" stroke-width="1.5"/>
        <text x="${centerX}" y="${cotaY + 18}" text-anchor="middle" font-size="12" font-weight="600" fill="${colorLinea}">
            ${dimension.toFixed(2)} m
        </text>
    `;

    // Líneas de cota vertical
    const cotaX = centerX - halfPozo - 35;
    svg += `
        <!-- Cota vertical -->
        <line x1="${cotaX}" y1="${centerY - halfPozo}" x2="${cotaX}" y2="${centerY + halfPozo}"
              stroke="${colorLinea}" stroke-width="1.5"/>
        <line x1="${cotaX - 5}" y1="${centerY - halfPozo}" x2="${cotaX + 5}" y2="${centerY - halfPozo}"
              stroke="${colorLinea}" stroke-width="1.5"/>
        <line x1="${cotaX - 5}" y1="${centerY + halfPozo}" x2="${cotaX + 5}" y2="${centerY + halfPozo}"
              stroke="${colorLinea}" stroke-width="1.5"/>
        <text x="${cotaX - 5}" y="${centerY + 4}" text-anchor="end" font-size="12" font-weight="600" fill="${colorLinea}"
              transform="rotate(-90 ${cotaX - 5} ${centerY})">
            ${dimension.toFixed(2)} m
        </text>
    `;

    // Etiqueta del área
    svg += `
        <text x="${centerX}" y="${centerY - 10}" text-anchor="middle" font-size="11" fill="${colorTexto}">
            Área
        </text>
        <text x="${centerX}" y="${centerY + 8}" text-anchor="middle" font-size="14" font-weight="700" fill="${colorLinea}">
            ${resultado.area_minima.toFixed(2)} m²
        </text>
    `;

    // Información adicional
    svg += `
        <text x="${centerX}" y="${svgHeight - 15}" text-anchor="middle" font-size="10" fill="#64748b">
            Altura del paramento: ${altura} m | Lados edificados: ${ladosEdificados}
        </text>
    `;

    svg += '</svg>';

    container.innerHTML = svg;
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
        { label: 'Tramos de 18m', value: resultado.tramos_completos },
        { label: 'Incremento por Tramos', value: `+${resultado.incremento_tramo.toFixed(2)} m` }
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
