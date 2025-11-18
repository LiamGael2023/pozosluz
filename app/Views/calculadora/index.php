<div class="row row-deck row-cards">
    <!-- Formulario de Cálculo -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header bg-primary-lt">
                <h3 class="card-title">
                    <i class="ti ti-calculator me-2"></i>Datos de la Edificación
                </h3>
            </div>
            <div class="card-body">
                <form id="formCalculadora">
                    <!-- Tipo de Edificación -->
                    <div class="mb-3">
                        <label class="form-label required">Tipo de Edificación</label>
                        <select class="form-select" name="tipo_edificacion" id="tipo_edificacion" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($tiposEdificacion as $tipo): ?>
                                <option value="<?= htmlspecialchars($tipo['id']) ?>">
                                    <?= htmlspecialchars($tipo['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <!-- Altura de la Edificación -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Altura (m)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="altura" id="altura"
                                       min="2.5" max="200" step="0.1" required
                                       placeholder="9.0">
                                <span class="input-group-text">m</span>
                            </div>
                            <small class="form-hint">Paramento más bajo</small>
                        </div>

                        <!-- Número de Pisos -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">N° de Pisos</label>
                            <input type="number" class="form-control" name="numero_pisos" id="numero_pisos"
                                   min="1" max="50" step="1" required placeholder="3">
                        </div>
                    </div>

                    <!-- Tipo de Ambiente -->
                    <div class="mb-3">
                        <label class="form-label required">Tipo de Ambiente</label>
                        <select class="form-select" name="tipo_ambiente" id="tipo_ambiente" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($tiposAmbiente as $tipo): ?>
                                <option value="<?= htmlspecialchars($tipo['id']) ?>">
                                    <?= htmlspecialchars($tipo['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Lados Edificados -->
                    <div class="mb-3">
                        <label class="form-label required">Lados Edificados</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="lados_edificados" id="lados-1" value="1" required>
                            <label class="btn btn-outline-primary" for="lados-1">1</label>
                            <input type="radio" class="btn-check" name="lados_edificados" id="lados-2" value="2">
                            <label class="btn btn-outline-primary" for="lados-2">2</label>
                            <input type="radio" class="btn-check" name="lados_edificados" id="lados-3" value="3">
                            <label class="btn btn-outline-primary" for="lados-3">3</label>
                            <input type="radio" class="btn-check" name="lados_edificados" id="lados-4" value="4">
                            <label class="btn btn-outline-primary" for="lados-4">4</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        <i class="ti ti-calculator me-2"></i>Calcular Dimensiones
                    </button>
                </form>
            </div>
        </div>

        <!-- Notas Importantes -->
        <div class="card mt-3">
            <div class="card-status-start bg-info"></div>
            <div class="card-body">
                <h4 class="card-title mb-3">
                    <i class="ti ti-info-circle me-2 text-info"></i>Notas RNE
                </h4>
                <ul class="list-unstyled space-y-2 mb-0">
                    <li class="d-flex">
                        <i class="ti ti-point-filled text-info me-2 mt-1"></i>
                        <small>Las dimensiones se miden entre las caras de los paramentos.</small>
                    </li>
                    <li class="d-flex">
                        <i class="ti ti-point-filled text-info me-2 mt-1"></i>
                        <small>Los pozos pueden techarse dejando ventilación > 50%.</small>
                    </li>
                    <li class="d-flex">
                        <i class="ti ti-point-filled text-info me-2 mt-1"></i>
                        <small>Cálculo por tramos cada 18.00 m de altura.</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Resultados -->
    <div class="col-lg-7">
        <div id="resultados-container" style="display: none;">
            <!-- Gráfico del Pozo de Luz -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-3d-cube-sphere me-2"></i>Vista del Pozo de Luz
                    </h3>
                </div>
                <div class="card-body">
                    <div id="grafico-pozo" class="d-flex justify-content-center">
                        <!-- SVG generado por JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Resultados principales -->
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Lado Mínimo</div>
                            </div>
                            <div class="h1 mb-0" id="resultado-dimension">--</div>
                            <div class="text-muted">metros</div>
                        </div>
                        <div class="card-footer bg-primary-lt">
                            <span class="text-primary fw-bold">
                                <i class="ti ti-ruler-2 me-1"></i>Dimensión
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Dist. Perpendicular</div>
                            </div>
                            <div class="h1 mb-0" id="resultado-perpendicular">--</div>
                            <div class="text-muted">metros</div>
                        </div>
                        <div class="card-footer bg-green-lt">
                            <span class="text-green fw-bold">
                                <i class="ti ti-arrows-vertical me-1"></i>Perpendicular
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Área Mínima</div>
                            </div>
                            <div class="h1 mb-0" id="resultado-area">--</div>
                            <div class="text-muted">m²</div>
                        </div>
                        <div class="card-footer bg-azure-lt">
                            <span class="text-azure fw-bold">
                                <i class="ti ti-square me-1"></i>Área
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles del Cálculo -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-list-details me-2"></i>Detalles del Cálculo
                    </h3>
                    <div class="card-actions">
                        <span class="badge bg-blue-lt" id="resultado-perpendicular-desc"></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="datagrid" id="detalles-calculo">
                        <!-- Los detalles se llenan dinámicamente -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Placeholder cuando no hay resultados -->
        <div id="placeholder-resultados">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                            <path d="M3 21h18"></path>
                            <path d="M5 21v-14l8-4v18"></path>
                            <path d="M19 21v-10l-6-4"></path>
                            <path d="M9 9v.01"></path>
                            <path d="M9 12v.01"></path>
                            <path d="M9 15v.01"></path>
                            <path d="M9 18v.01"></path>
                        </svg>
                    </div>
                    <h2 class="text-muted mb-3">Calculadora de Pozos de Luz</h2>
                    <p class="text-muted mb-4">
                        Ingrese los datos de la edificación para calcular las dimensiones mínimas del pozo de luz según la normativa peruana RNE A.010 / A.020.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <div class="d-flex align-items-center text-muted">
                                <i class="ti ti-check text-success me-2"></i>
                                <small>Viviendas Unifamiliares</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center text-muted">
                                <i class="ti ti-check text-success me-2"></i>
                                <small>Bifamiliares</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center text-muted">
                                <i class="ti ti-check text-success me-2"></i>
                                <small>Multifamiliares</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
