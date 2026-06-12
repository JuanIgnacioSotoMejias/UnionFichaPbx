/**
 * home_graficas.js - Módulo de Visualización Estadística (Dashboard Multi-Rol)
 * 
 * Gestiona el renderizado de gráficos analíticos según el rol del usuario.
 * Utiliza ApexCharts para visualizaciones dinámicas y responsivas.
 */

document.addEventListener('DOMContentLoaded', function() {

    // 1. EXTRACCIÓN DE DATOS Y ROL
    if (!window.VENT911_STATS || !window.USER_ROL) return;

    const stats = window.VENT911_STATS;
    const rol = window.USER_ROL;
    const chartInstances = {}; // Almacén para actualizar gráficas sin recrearlas

    // Colores Institucionales
    const colors = {
        primary: '#2563eb', success: '#16a34a', danger: '#dc2626', warning: '#ca8a04', info: '#0891b2', secondary: '#64748b'
    };

    const getBaseOptions = (type, height = 300) => ({
        chart: { type, height, fontFamily: 'inherit', toolbar: { show: false }, animations: { enabled: true, easing: 'easeinout', speed: 800 } },
        stroke: { curve: 'smooth', width: 2 },
        dataLabels: { enabled: false },
        colors: [colors.primary, colors.success, colors.danger, colors.warning, colors.info]
    });

    // 2. INICIALIZACIÓN POR ROL
    switch (rol) {
        case 1: renderAdminStats(stats); break;
        case 2: renderOperadorStats(stats); break;
        case 3: renderDespachadorStats(stats); break;
        case 4: renderJefaturaStats(stats); break;
    }

    /**
     * Función Global para actualizar el Dashboard vía AJAX
     */
    window.actualizarDashboard = function() {
        fetch('index.php?url=home/obtenerStatsAjax')
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    actualizarGraficasSegunRol(res.datos);
                }
            })
            .catch(err => console.error('Error actualizando dashboard:', err));
    };

    // Actualización automática cada 2 minutos
    setInterval(window.actualizarDashboard, 120000);

    function actualizarGraficasSegunRol(s) {
        // Actualizar Contadores Críticos (Widgets)
        const updateText = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.textContent = val;
        };

        if (rol === 1) {
            if (chartInstances.roles) {
                chartInstances.roles.updateSeries(s.roles.map(r => parseInt(r.total)));
                chartInstances.roles.updateOptions({ labels: s.roles.map(r => r.nombre_rol) });
            }
            if (chartInstances.emergencias) {
                chartInstances.emergencias.updateSeries([{ name: 'Incidentes', data: s.emergencias.map(e => parseInt(e.total)) }]);
                chartInstances.emergencias.updateOptions({ xaxis: { categories: s.emergencias.map(e => e.nombre) } });
            }
            if (chartInstances.estados) {
                chartInstances.estados.updateSeries([{ name: 'Fichas', data: s.estados.map(e => parseInt(e.total)) }]);
                chartInstances.estados.updateOptions({ xaxis: { categories: s.estados.map(e => e.estado_ficha) } });
            }
        } else if (rol === 2) {
            updateText('counter_total_hoy', s.total_hoy);
            if (chartInstances.misEstados) {
                chartInstances.misEstados.updateSeries(s.estados.map(e => parseInt(e.total)));
                chartInstances.misEstados.updateOptions({ labels: s.estados.map(e => e.estado_ficha) });
            }
            if (chartInstances.semana) {
                chartInstances.semana.updateSeries([{ name: 'Fichas creadas', data: s.semana.map(d => parseInt(d.total)) }]);
                chartInstances.semana.updateOptions({ xaxis: { categories: s.semana.map(d => d.fecha) } });
            }
        } else if (rol === 3) {
            updateText('counter_pendientes_globales', s.pendientes_globales);
            updateText('counter_mis_despachos_activos', s.mis_despachos_activos);
            if (chartInstances.organismos) {
                chartInstances.organismos.updateSeries([{ name: 'Solicitudes', data: s.top_organismos.map(o => parseInt(o.total)) }]);
                chartInstances.organismos.updateOptions({ xaxis: { categories: s.top_organismos.map(o => o.nombre_organismo) } });
            }
        } else if (rol === 4) {
            updateText('counter_total_hoy_jefatura', s.kpis.total_hoy);
            updateText('counter_efectividad', s.kpis.efectividad + '%');
            let pendTotal = 0;
            s.municipios.forEach(m => pendTotal += parseInt(m.pendientes));
            updateText('counter_pendientes_jefatura', pendTotal);

            if (chartInstances.comparativa) {
                const hoyData = Array(24).fill(0);
                const ayerData = Array(24).fill(0);
                s.comparativa.hoy.forEach(h => hoyData[h.hora] = parseInt(h.total));
                s.comparativa.ayer.forEach(a => ayerData[a.hora] = parseInt(a.total));
                chartInstances.comparativa.updateSeries([
                    { name: 'Hoy', data: hoyData },
                    { name: 'Ayer', data: ayerData }
                ]);
            }
            if (chartInstances.cierres) {
                chartInstances.cierres.updateSeries(s.cierres.map(c => parseInt(c.total)));
                chartInstances.cierres.updateOptions({ labels: s.cierres.map(c => c.motivo) });
            }
            if (chartInstances.municipiosEficiencia) {
                chartInstances.municipiosEficiencia.updateSeries([
                    { name: 'Resueltos', data: s.municipios.map(m => parseInt(m.resueltos)) },
                    { name: 'Pendientes', data: s.municipios.map(m => parseInt(m.pendientes)) }
                ]);
                chartInstances.municipiosEficiencia.updateOptions({ xaxis: { categories: s.municipios.map(m => m.nombre_municipio) } });
            }
        }
    }

    // --- RENDERIZADORES ---

    function renderAdminStats(s) {
        if (s.roles && document.querySelector("#rolesChart")) {
            chartInstances.roles = new ApexCharts(document.querySelector("#rolesChart"), {
                ...getBaseOptions('donut'),
                series: s.roles.map(r => parseInt(r.total)),
                labels: s.roles.map(r => r.nombre_rol),
                plotOptions: { pie: { donut: { labels: { show: true, total: { show: true, label: 'Personal' } } } } }
            });
            chartInstances.roles.render();
        }

        if (s.emergencias && document.querySelector("#emergenciasChart")) {
            chartInstances.emergencias = new ApexCharts(document.querySelector("#emergenciasChart"), {
                ...getBaseOptions('bar'),
                series: [{ name: 'Incidentes', data: s.emergencias.map(e => parseInt(e.total)) }],
                xaxis: { categories: s.emergencias.map(e => e.nombre) },
                plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
                colors: [colors.danger]
            });
            chartInstances.emergencias.render();
        }

        if (s.estados && document.querySelector("#estadosGlobalChart")) {
            chartInstances.estados = new ApexCharts(document.querySelector("#estadosGlobalChart"), {
                ...getBaseOptions('bar', 350),
                series: [{ name: 'Fichas', data: s.estados.map(e => parseInt(e.total)) }],
                xaxis: { categories: s.estados.map(e => e.estado_ficha) },
                colors: [colors.success]
            });
            chartInstances.estados.render();
        }
    }

    function renderOperadorStats(s) {
        if (s.estados && document.querySelector("#misEstadosChart")) {
            chartInstances.misEstados = new ApexCharts(document.querySelector("#misEstadosChart"), {
                ...getBaseOptions('donut', 250),
                series: s.estados.map(e => parseInt(e.total)),
                labels: s.estados.map(e => e.estado_ficha)
            });
            chartInstances.misEstados.render();
        }

        if (s.semana && document.querySelector("#actividadSemanalChart")) {
            chartInstances.semana = new ApexCharts(document.querySelector("#actividadSemanalChart"), {
                ...getBaseOptions('area'),
                series: [{ name: 'Fichas creadas', data: s.semana.map(d => parseInt(d.total)) }],
                xaxis: { categories: s.semana.map(d => d.fecha), type: 'datetime' },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.3 } }
            });
            chartInstances.semana.render();
        }

        // Inicializar monitoreo del widget PBX en tiempo real
        if (document.getElementById('pbx-widget-extension')) {
            actualizarWidgetPbx();
            setInterval(actualizarWidgetPbx, 30000);
        }
    }

    function renderDespachadorStats(s) {
        if (s.top_organismos && document.querySelector("#organismosChart")) {
            chartInstances.organismos = new ApexCharts(document.querySelector("#organismosChart"), {
                ...getBaseOptions('bar'),
                series: [{ name: 'Solicitudes', data: s.top_organismos.map(o => parseInt(o.total)) }],
                xaxis: { categories: s.top_organismos.map(o => o.nombre_organismo) },
                colors: [colors.info]
            });
            chartInstances.organismos.render();
        }
    }

    function renderJefaturaStats(s) {
        // Comparativa Temporal
        if (document.querySelector("#comparativaTemporalChart")) {
            const hoyData = Array(24).fill(0);
            const ayerData = Array(24).fill(0);
            s.comparativa.hoy.forEach(h => hoyData[h.hora] = parseInt(h.total));
            s.comparativa.ayer.forEach(a => ayerData[a.hora] = parseInt(a.total));

            chartInstances.comparativa = new ApexCharts(document.querySelector("#comparativaTemporalChart"), {
                ...getBaseOptions('area', 350),
                series: [
                    { name: 'Hoy', data: hoyData },
                    { name: 'Ayer', data: ayerData }
                ],
                xaxis: { categories: Array.from({length: 24}, (_, i) => `${i}:00`) },
                colors: [colors.primary, colors.secondary],
                stroke: { width: [3, 2], dashArray: [0, 5] }
            });
            chartInstances.comparativa.render();
        }

        // Calidad de Cierres
        if (document.querySelector("#cierresCalidadChart")) {
            chartInstances.cierres = new ApexCharts(document.querySelector("#cierresCalidadChart"), {
                ...getBaseOptions('donut', 350),
                series: s.cierres.map(c => parseInt(c.total)),
                labels: s.cierres.map(c => c.motivo),
                legend: { position: 'bottom' }
            });
            chartInstances.cierres.render();
        }

        // Eficiencia por Municipio
        if (document.querySelector("#municipiosEficienciaChart")) {
            chartInstances.municipiosEficiencia = new ApexCharts(document.querySelector("#municipiosEficienciaChart"), {
                ...getBaseOptions('bar', 400),
                series: [
                    { name: 'Resueltos', data: s.municipios.map(m => parseInt(m.resueltos)) },
                    { name: 'Pendientes', data: s.municipios.map(m => parseInt(m.pendientes)) }
                ],
                xaxis: { categories: s.municipios.map(m => m.nombre_municipio) },
                plotOptions: { bar: { stacked: true, borderRadius: 6 } },
                colors: [colors.success, colors.warning]
            });
            chartInstances.municipiosEficiencia.render();
        }
    }

    /**
     * Realiza una consulta asíncrona al proxy local para obtener el estado de la sesión y extensión PBX
     */
    async function actualizarWidgetPbx() {
        const extEl = document.getElementById('pbx-widget-extension');
        const queueEl = document.getElementById('pbx-widget-queue');
        const sessBadge = document.getElementById('pbx-widget-session-badge');
        const phoneBadge = document.getElementById('pbx-widget-phone-badge');
        
        const cardEl = document.getElementById('cardPbxStatus');
        const iconEl = document.getElementById('pbx-widget-icon');
        const queueLabelEl = document.getElementById('pbx-widget-queue-label');
        
        if (!extEl) return;

        try {
            const response = await fetch('index.php?url=auth/estadoPbx', { method: 'POST' });
            if (!response.ok) throw new Error('HTTP ' + response.status);
            
            const res = await response.json();
            if (res.success && res.data) {
                const data = res.data;
                const hasPhone = data.extension && data.extension !== '0000' && data.extension !== '';
                
                if (hasPhone) {
                    // --- ESTADO ACTIVO / CON TELÉFONO ---
                    extEl.innerHTML = data.extension;
                    extEl.className = 'display-5 fw-bold text-dark mb-0';
                    queueEl.textContent = data.queue_name || 'N/A';
                    
                    if (queueLabelEl) queueLabelEl.style.setProperty('display', 'block', 'important');
                    
                    // Restablecer estilos de tarjeta y de icono a los colores por defecto (Verde/Success)
                    if (cardEl) {
                        cardEl.classList.remove('border-danger');
                        cardEl.classList.add('border-success');
                    }
                    if (iconEl) {
                        iconEl.className = 'bi bi-telephone-fill fs-2 me-3 text-success';
                    }
                    
                    // 1. Badge de Sesión (Activa / Inactiva)
                    if (data.is_active) {
                        sessBadge.textContent = 'Sesión: Activa';
                        sessBadge.style.backgroundColor = '#16a34a'; // bg-success
                        sessBadge.classList.add('badge-pulse');
                    } else {
                        sessBadge.textContent = 'Sesión: Inactiva';
                        sessBadge.style.backgroundColor = '#dc2626'; // bg-danger
                        sessBadge.classList.remove('badge-pulse');
                    }
                    
                    // 2. Badge de Teléfono (Conectado / Desconectado)
                    if (data.telefono_status === 'ONLINE') {
                        phoneBadge.textContent = 'Teléfono: Conectado';
                        phoneBadge.style.backgroundColor = '#16a34a'; // bg-success
                    } else {
                        phoneBadge.textContent = 'Teléfono: Desconectado';
                        phoneBadge.style.backgroundColor = '#dc2626'; // bg-danger
                    }
                    
                    if (sessBadge) sessBadge.style.setProperty('display', 'inline-block', 'important');
                    if (phoneBadge) phoneBadge.style.setProperty('display', 'inline-block', 'important');
                } else {
                    // --- ESTADO INACTIVO / SIN TELÉFONO ---
                    extEl.innerHTML = '<span class="fs-4 text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i>No tiene línea telefónica</span>';
                    if (queueLabelEl) queueLabelEl.style.setProperty('display', 'none', 'important');
                    
                    // Cambiar estilos de tarjeta e icono a Rojo (Danger)
                    if (cardEl) {
                        cardEl.classList.remove('border-success');
                        cardEl.classList.add('border-danger');
                    }
                    if (iconEl) {
                        iconEl.className = 'bi bi-telephone-x-fill fs-2 me-3 text-danger';
                    }
                    
                    if (sessBadge) sessBadge.style.setProperty('display', 'none', 'important');
                    if (phoneBadge) phoneBadge.style.setProperty('display', 'none', 'important');
                }
            } else {
                throw new Error(res.message || 'Error de datos');
            }
        } catch (e) {
            console.error('[PBX Widget] Error al actualizar:', e);
            extEl.innerHTML = '<span class="fs-5 text-secondary fw-semibold">Error de Conexión</span>';
            if (queueLabelEl) queueLabelEl.style.setProperty('display', 'none', 'important');
            
            if (cardEl) {
                cardEl.classList.remove('border-success');
                cardEl.classList.add('border-danger');
            }
            if (iconEl) {
                iconEl.className = 'bi bi-telephone-minus-fill fs-2 me-3 text-secondary';
            }
            
            if (sessBadge) sessBadge.style.setProperty('display', 'none', 'important');
            if (phoneBadge) phoneBadge.style.setProperty('display', 'none', 'important');
        }
    }
});
