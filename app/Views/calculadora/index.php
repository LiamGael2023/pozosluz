<div class="row row-deck row-cards">
    <!-- Formulario de Cálculo -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
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

                    <!-- Altura de la Edificación -->
                    <div class="mb-3">
                        <label class="form-label required">Altura de la Edificación</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="altura" id="altura"
                                   min="2.5" max="200" step="0.1" required
                                   placeholder="Ej: 9.0">
                            <span class="input-group-text">metros</span>
                        </div>
                        <small class="form-hint">Altura del paramento más bajo del pozo</small>
                    </div>

                    <!-- Número de Pisos -->
                    <div class="mb-3">
                        <label class="form-label required">Número de Pisos</label>
                        <input type="number" class="form-control" name="numero_pisos" id="numero_pisos"
                               min="1" max="50" step="1" required placeholder="Ej: 3">
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
                        <label class="form-label required">Lados con Edificaciones Propias</label>
                        <select class="form-select" name="lados_edificados" id="lados_edificados" required>
                            <option value="">Seleccione...</option>
                            <option value="1">1 lado</option>
                            <option value="2">2 lados</option>
                            <option value="3">3 lados</option>
                            <option value="4">4 lados</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-calculator me-2"></i>Calcular Dimensiones
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card de Notas Importantes -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-info-circle me-2"></i>Notas Importantes
                </h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled space-y-1">
                    <li class="d-flex align-items-start">
                        <i class="ti ti-check text-success me-2 mt-1"></i>
                        <span>Las dimensiones se miden entre las caras de los paramentos que definen el pozo.</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="ti ti-check text-success me-2 mt-1"></i>
                        <span>Se considera como paramento más bajo a cualquiera de los dos lados del pozo perpendiculares a la distancia mínima.</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="ti ti-check text-success me-2 mt-1"></i>
                        <span>Los pozos pueden techarse con cubierta transparente, dejando área de ventilación > 50%.</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="ti ti-check text-success me-2 mt-1"></i>
                        <span>Las dimensiones se calculan por tramos cada 18.00 m de altura.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Resultados -->
    <div class="col-lg-6">
        <div id="resultados-container" style="display: none;">
            <!-- Dimensión Mínima -->
            <div class="card card-resultado mb-3">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <span class="badge bg-blue-lt normativa-badge">RNE A.010 / A.020</span>
                    </div>
                    <h4 class="text-muted mb-0">Dimensión Mínima del Pozo</h4>
                    <div class="resultado-valor" id="resultado-dimension">--</div>
                    <div class="resultado-unidad">metros por lado</div>
                </div>
            </div>

            <!-- Distancia Perpendicular -->
            <div class="card card-resultado mb-3">
                <div class="card-body text-center">
                    <h4 class="text-muted mb-0">Distancia Perpendicular Mínima</h4>
                    <div class="resultado-valor text-green" id="resultado-perpendicular">--</div>
                    <div class="resultado-unidad">metros</div>
                    <small class="text-muted" id="resultado-perpendicular-desc"></small>
                </div>
            </div>

            <!-- Área Mínima -->
            <div class="card card-resultado mb-3">
                <div class="card-body text-center">
                    <h4 class="text-muted mb-0">Área Mínima del Pozo</h4>
                    <div class="resultado-valor text-azure" id="resultado-area">--</div>
                    <div class="resultado-unidad">m²</div>
                </div>
            </div>

            <!-- Detalles del Cálculo -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-list-details me-2"></i>Detalles del Cálculo
                    </h3>
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
                    <div class="mb-3">
                        <i class="ti ti-building-arch" style="font-size: 4rem; color: var(--tblr-muted);"></i>
                    </div>
                    <h3 class="text-muted">Complete el formulario</h3>
                    <p class="text-muted">
                        Ingrese los datos de la edificación para calcular las dimensiones mínimas del pozo de luz según la normativa peruana.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
