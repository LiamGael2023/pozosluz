<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Pozos de Luz') ?> - RNE Perú</title>

    <!-- Tabler.io CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        .resultado-valor {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--tblr-primary);
        }
        .resultado-unidad {
            font-size: 0.875rem;
            color: var(--tblr-muted);
        }
        .card-resultado {
            transition: transform 0.2s ease;
        }
        .card-resultado:hover {
            transform: translateY(-2px);
        }
        .normativa-badge {
            font-size: 0.75rem;
        }
        @media (max-width: 768px) {
            .resultado-valor {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body class="theme-light">
    <div class="page">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md navbar-light d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="/">
                        <i class="ti ti-building-arch me-2"></i>
                        Pozos de Luz
                    </a>
                </h1>
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="/">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-calculator"></i>
                                    </span>
                                    <span class="nav-link-title">Calculadora</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/historial">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-history"></i>
                                    </span>
                                    <span class="nav-link-title">Historial</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page wrapper -->
        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="page-pretitle">
                        Normativa Peruana RNE A.010 / A.020
                    </div>
                    <h2 class="page-title">
                        <?= htmlspecialchars($titulo ?? 'Calculadora de Pozos de Luz') ?>
                    </h2>
                </div>
            </div>

            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <?= $content ?>
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    <a href="#" class="link-secondary" data-bs-toggle="modal" data-bs-target="#modal-normativa">
                                        <i class="ti ti-book me-1"></i>Referencias Normativas
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Calculadora de referencia - Consulte siempre la normativa vigente
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Modal de Referencias Normativas -->
    <div class="modal modal-blur fade" id="modal-normativa" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Referencias Normativas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h4>Reglamento Nacional de Edificaciones (RNE)</h4>
                        <ul>
                            <li><strong>Norma Técnica A.010</strong> - Condiciones Generales de Diseño</li>
                            <li><strong>Norma Técnica A.020</strong> - Vivienda</li>
                            <li>D.S. Nº 011-2006-VIVIENDA y modificatorias</li>
                            <li>R.M. Nº 188-2021-VIVIENDA</li>
                        </ul>
                    </div>
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <div><i class="ti ti-info-circle me-2"></i></div>
                            <div>
                                <h4 class="alert-title">Importante</h4>
                                <div class="text-muted">
                                    Esta calculadora es una herramienta de referencia.
                                    Consulte siempre la normativa vigente y a un profesional habilitado para proyectos reales.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabler.io JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>

    <!-- Custom JS -->
    <script src="/assets/js/calculadora.js"></script>
</body>
</html>
