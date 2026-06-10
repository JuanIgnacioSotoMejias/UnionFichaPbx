<?php
/**
 * scripts.php - Cargador Central de Lógica Javascript
 * 
 * Centraliza la invocación de librerías core y configuraciones de UI
 * condicionales según el módulo activo.
 */

// 1. DETERMINACIÓN DEL CONTEXTO DE EJECUCIÓN
$pageName = $pageName ?? 'home';
?>

<!-- 2. LIBRERÍAS TRANSVERSALES (SweetAlert2) -->
<script src="public/libs/sweetalert2/sweetalert2.min.js"></script>

<!-- 3. CARGA SEGÚN CONTEXTO (Login vs Dashboard) -->
<?php if (in_array($pageName, ['login', 'setup'])): ?>
    <!-- Scripts exclusivos de Autenticación -->
    <?php if ($pageName === 'login'): ?>
        <script src="public/js/auth/login.js?v=<?= filemtime('public/js/auth/login.js') ?>"></script>
    <?php elseif ($pageName === 'setup'): ?>
        <script src="public/js/auth/setup.js?v=<?= filemtime('public/js/auth/setup.js') ?>"></script>
    <?php endif; ?>

<?php else: ?>
    <!-- Contexto de Sesión para Lógica Frontend -->
    <script>
        window.USUARIO_ID     = <?php echo (int)($_SESSION['user_id']    ?? 0); ?>;
        window.USUARIO_ROL_ID = <?php echo (int)($_SESSION['user_rol_id'] ?? 0); ?>;

        // Heartbeat PBX: mantiene la sesión telefónica activa para operadores
        if (window.USUARIO_ROL_ID === 2) {
            (function() {
                const enviarHeartbeatPbx = async () => {
                    try {
                        const response = await fetch('index.php?url=auth/heartbeatPbx', { method: 'POST' });
                        if (!response.ok) {
                            console.warn('[PBX Heartbeat] HTTP error: ' + response.status);
                            actualizarIndicadorPbx(false);
                            return;
                        }
                        const data = await response.json();
                        if (data.reauthenticated) {
                            console.log('[PBX Heartbeat] Sesión re-autenticada automáticamente.');
                            actualizarIndicadorPbx(true);
                        } else if (data.ok) {
                            console.log('[PBX Heartbeat] OK');
                            actualizarIndicadorPbx(true);
                        } else {
                            console.warn('[PBX Heartbeat] Fallido: ' + (data.message || data.error));
                            actualizarIndicadorPbx(false);
                        }
                    } catch (e) {
                        console.error('[PBX Heartbeat] Error de conexión:', e);
                        actualizarIndicadorPbx(false, true);
                    }
                };

                function actualizarIndicadorPbx(activo, errorConexion = false) {
                    const statusIndicator = document.getElementById('pbx-status-indicator');
                    const statusTooltip = document.getElementById('pbx-status-tooltip');
                    if (!statusIndicator) return;
                    
                    if (errorConexion) {
                        statusIndicator.className = 'bi bi-telephone-fill text-secondary';
                        const newTitle = 'PBX: Sin conexión (Ficha)';
                        statusTooltip.title = newTitle;
                        statusTooltip.setAttribute('data-bs-original-title', newTitle);
                        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                            const tooltipInstance = bootstrap.Tooltip.getInstance(statusTooltip);
                            if (tooltipInstance) tooltipInstance.setContent({ '.tooltip-inner': newTitle });
                        }
                    } else if (activo) {
                        statusIndicator.className = 'bi bi-telephone-fill text-success';
                        const newTitle = 'PBX: Teléfono Activo';
                        statusTooltip.title = newTitle;
                        statusTooltip.setAttribute('data-bs-original-title', newTitle);
                        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                            const tooltipInstance = bootstrap.Tooltip.getInstance(statusTooltip);
                            if (tooltipInstance) tooltipInstance.setContent({ '.tooltip-inner': newTitle });
                        }
                    } else {
                        statusIndicator.className = 'bi bi-telephone-fill text-danger';
                        const newTitle = 'PBX: Teléfono Inactivo';
                        statusTooltip.title = newTitle;
                        statusTooltip.setAttribute('data-bs-original-title', newTitle);
                        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                            const tooltipInstance = bootstrap.Tooltip.getInstance(statusTooltip);
                            if (tooltipInstance) tooltipInstance.setContent({ '.tooltip-inner': newTitle });
                        }
                    }
                }

                // Ejecutar al cargar y luego cada 60 segundos
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(enviarHeartbeatPbx, 2000); // esperar 2s tras cargar
                    setInterval(enviarHeartbeatPbx, 60000); // cada 1 minuto
                });
            })();
        }

        // Utilidad global de escape XSS: disponible en todos los módulos del dashboard.
        // Centralizada aquí para que notificaciones.js y otros scripts no dependan
        // de que datatables_config.js esté cargado en la misma página.
        window.escapeHTML = function (str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g,  '&amp;')
                .replace(/</g,  '&lt;')
                .replace(/>/g,  '&gt;')
                .replace(/"/g,  '&quot;')
                .replace(/'/g,  '&#39;');
        };
    </script>

    <!-- Scripts Robustos del Sistema (Dashboard) -->
    <script src="public/libs/overlayscrollbars/overlayscrollbars.browser.es6.min.js"></script>
    <script src="public/libs/popperjs/popper.min.js"></script>
    <script src="public/libs/bootstrap/bootstrap.min.js"></script>
    <script src="public/js/adminlte.js"></script>
    
    <!-- jQuery Ecosystem (DataTables/Select2 dependencias) -->
    <script src="public/libs/datatables/jquery-3.7.1.min.js"></script>
    <script src="public/libs/select2/select2.min.js"></script>
    <script src="public/libs/select2/es.js"></script>

    <!-- Lógica de Notificaciones en Tiempo Real -->
    <script src="public/js/comun/notificaciones.js?v=<?=filemtime('public/js/comun/notificaciones.js')?>"></script>

    <!-- 4. CONFIGURACIÓN DE COMPORTAMIENTO DE INTERFAZ -->
    <script>
        /**
         * Inicialización de OverlayScrollbars para la Sidebar.
         * Garantiza una experiencia de scroll suave y estética.
         */
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-scroll-area';
        const DefaultConfig = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };

        document.addEventListener('DOMContentLoaded', function () {
            const initScroll = () => {
                const target = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
                if (target && typeof OverlayScrollbarsGlobal !== 'undefined') {
                    OverlayScrollbarsGlobal.OverlayScrollbars(target, {
                        overflow: { x: 'hidden' },
                        scrollbars: {
                            theme: DefaultConfig.scrollbarTheme,
                            autoHide: DefaultConfig.scrollbarAutoHide,
                            clickScroll: DefaultConfig.scrollbarClickScroll,
                        },
                    });
                } else if (target) {
                    setTimeout(initScroll, 100);
                }
            };
            
            // Solo activar scrollbars en desktop para optimizar rendimiento móvil
            const isMobile = window.innerWidth <= 992;
            if (!isMobile) initScroll();
        });
    </script>
    <script src="public/js/ayuda/ayuda_contextual.js?v=<?= filemtime('public/js/ayuda/ayuda_contextual.js') ?>"></script>
<?php endif; ?>

