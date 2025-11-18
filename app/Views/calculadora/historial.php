<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ti ti-history me-2"></i>Historial de Cálculos Recientes
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($historial)): ?>
            <div class="empty">
                <div class="empty-icon">
                    <i class="ti ti-history-off" style="font-size: 3rem;"></i>
                </div>
                <p class="empty-title">No hay cálculos registrados</p>
                <p class="empty-subtitle text-muted">
                    Los cálculos realizados aparecerán aquí una vez que la base de datos esté configurada.
                </p>
                <div class="empty-action">
                    <a href="/" class="btn btn-primary">
                        <i class="ti ti-calculator me-2"></i>Ir a la Calculadora
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo Edificación</th>
                            <th>Altura</th>
                            <th>Pisos</th>
                            <th>Tipo Ambiente</th>
                            <th>Dimensión Mínima</th>
                            <th>Área Mínima</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial as $calculo): ?>
                            <tr>
                                <td class="text-muted">
                                    <?= date('d/m/Y H:i', strtotime($calculo['created_at'])) ?>
                                </td>
                                <td>
                                    <?php
                                    $tiposEdif = [
                                        'unifamiliar' => 'Unifamiliar',
                                        'bifamiliar' => 'Bifamiliar',
                                        'multifamiliar' => 'Multifamiliar'
                                    ];
                                    echo $tiposEdif[$calculo['tipo_edificacion']] ?? $calculo['tipo_edificacion'];
                                    ?>
                                </td>
                                <td><?= number_format($calculo['altura'], 2) ?> m</td>
                                <td><?= $calculo['numero_pisos'] ?></td>
                                <td>
                                    <?php
                                    $tiposAmb = [
                                        'tipoA' => 'Tipo A',
                                        'tipoB' => 'Tipo B'
                                    ];
                                    echo $tiposAmb[$calculo['tipo_ambiente']] ?? $calculo['tipo_ambiente'];
                                    ?>
                                </td>
                                <td>
                                    <span class="badge bg-blue-lt">
                                        <?= number_format($calculo['dimension_minima'], 2) ?> m
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-azure-lt">
                                        <?= number_format($calculo['area_minima'], 2) ?> m²
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
