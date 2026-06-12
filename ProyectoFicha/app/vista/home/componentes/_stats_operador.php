<?php
/**
 * Componente: Dashboard Operador
 */
?>
<div class="row g-4 mb-4">
    <!-- Botón Gigante Crear Ficha -->
    <div class="col-lg-4 col-md-6 col-12">
        <div class="card shadow-sm border-0 rounded-4 card-btn-crear text-white h-100 cursor-pointer hover-scale" id="btnNuevaFicha">
            <div class="card-body p-4 d-flex flex-column justify-content-center align-items-center text-center">
                <i class="bi bi-plus-circle-fill btn-crear-icon"></i>
                <h3 class="fw-bold mb-0">CREAR FICHA</h3>
                <p class="mb-0 fw-medium">Registrar nueva emergencia</p>
            </div>
        </div>
    </div>

    <!-- Resumen Hoy -->
    <div class="col-lg-4 col-md-6 col-12">
        <div class="card shadow-sm border-0 rounded-4 bg-white border-start border-success border-5 h-100">
            <div class="card-body p-4 d-flex flex-column justify-content-center align-items-center text-center">
                <i class="bi bi-file-earmark-plus-fill display-4 mb-3 text-success"></i>
                <h4 class="fw-bold mb-0 text-dark">Fichas Creadas Hoy</h4>
                <p class="display-3 fw-bold mb-0 text-success" id="counter_total_hoy"><?php echo $datos['total_hoy'] ?? 0; ?></p>
            </div>
        </div>
    </div>

    <!-- Monitoreo de Teléfono PBX (Receptor) -->
    <div class="col-lg-4 col-md-12 col-12">
        <div class="card shadow-sm border-0 rounded-4 bg-white border-start border-primary border-5 h-100" id="cardPbxStatus">
            <div class="card-body p-4 d-flex flex-column justify-content-between text-center text-lg-start">
                <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;" id="pbx-widget-icon-container">
                        <i class="bi bi-telephone-fill fs-3 text-primary" id="pbx-widget-icon"></i>
                    </div>
                    <div class="text-start">
                        <h5 class="fw-bold text-dark mb-0">Teléfono Asignado</h5>
                        <p class="text-muted small mb-0">Monitoreo PBX Receptor</p>
                    </div>
                </div>
                
                <div class="my-2 text-center" style="min-height: 65px; display: flex; flex-direction: column; justify-content: center;">
                    <div id="pbx-widget-extension-container">
                        <span class="display-5 fw-bold text-dark mb-0" id="pbx-widget-extension">--</span>
                    </div>
                    <div class="text-secondary small fw-semibold mt-1" id="pbx-widget-queue-label" style="display: none;">
                        Cola: <span id="pbx-widget-queue" class="text-primary">--</span>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-center mt-3" id="pbx-widget-badges-container">
                    <span class="badge rounded-pill px-3 py-2 text-white" id="pbx-widget-session-badge" style="background-color: #64748b; font-size: 0.75rem; display: none;">
                        Sesión: Cargando...
                    </span>
                    <span class="badge rounded-pill px-3 py-2 text-white" id="pbx-widget-phone-badge" style="background-color: #64748b; font-size: 0.75rem; display: none;">
                        Teléfono: Cargando...
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Mis Estados -->
    <div class="col-lg-4 col-12">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white border-bottom p-3">
                <h3 class="card-title text-primary fw-bold mb-0">
                    <i class="bi bi-pie-chart-fill me-2"></i>Mis Fichas por Estado
                </h3>
            </div>
            <div class="card-body p-4">
                <div id="misEstadosChart" style="min-height: 250px;"></div>
            </div>
        </div>
    </div>

    <!-- Actividad Semanal -->
    <div class="col-lg-8 col-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white border-bottom p-3">
                <h3 class="card-title text-success fw-bold mb-0">
                    <i class="bi bi-graph-up me-2"></i>Mi Actividad (Últimos 7 días)
                </h3>
            </div>
            <div class="card-body p-4">
                <div id="actividadSemanalChart" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
</div>
