@extends('empleado.dashboard_empleado')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <!-- Columna izquierda - Perfil y Estadísticas -->
        <div class="col-lg-4">
            <!-- Tarjeta de Perfil -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user mr-2"></i>Mi Perfil</h5>
                </div>
                <div class="card-body text-center">
                    <!--<div class="profile-avatar">
                        <span class="text-white h3">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>-->
                    <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted mb-3">{{ Auth::user()->email }}</p>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stats-card">
                                <div class="stats-number">{{ $estadisticasMes['total_registros'] }}</div>
                                <div class="stats-label">Registros</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-card">
                                <div class="stats-number">{{ $estadisticasMes['total_horas'] }}h</div>
                                <div class="stats-label">Horas Totales</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">Promedio diario: {{ $estadisticasMes['promedio_horas'] }}h</small>
                    </div>
                </div>
            </div>

            <!-- Control de Tiempo -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-play-circle mr-2"></i>Control de Tiempo</h5>
                </div>
                <div class="card-body">
                    <div class="time-display">
                        <div id="estado-actual" class="mb-2">Estado: No iniciado</div>
                        <div id="tiempo-transcurrido">00:00:00</div>
                    </div>

                    <div class="text-center">
                        <button id="btn-start" class="btn btn-control btn-start">
                            <i class="fas fa-play mr-2"></i>INICIAR
                        </button>

                        <div id="btn-group-active" style="display: none;">
                            <button id="btn-pause" class="btn btn-control btn-pause">
                                <i class="fas fa-pause mr-2"></i>PAUSAR
                            </button>
                            <button id="btn-stop" class="btn btn-control btn-stop">
                                <i class="fas fa-stop mr-2"></i>DETENER
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha - Historial y Filtros -->
        <div class="col-lg-8">
            <!-- Filtros -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>Filtrar por Mes Completo</h5>
                </div>
                <div class="card-body p-2 p-lg-3">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="filterMes" class="font-weight-bold text-dark small">
                                    <i class="fas fa-calendar-alt mr-1"></i>Seleccione un mes
                                </label>
                                <input type="text" class="form-control" id="filterMes" 
                                    placeholder="Seleccione un mes" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <button id="btn-apply-filters" class="btn btn-primary btn-sm mr-2">
                                    <i class="fas fa-filter mr-1"></i>Filtrar
                                </button>
                                <button id="btn-reset-filters" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-redo mr-1"></i>Mes Actual
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Se filtrará del día 1 al último día del mes
                            </small>
                        </div>
                    </div>

                    <!-- Información del filtro aplicado -->
                    <div class="row mt-2" id="filtroInfo" style="display: none;">
                        <div class="col-md-12">
                            <div class="alert alert-info py-2 mb-0 small d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-info-circle"></i> 
                                    Filtrando por mes completo: <strong id="infoMes"></strong>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-info" onclick="limpiarFiltroMes()">
                                    <i class="fas fa-times"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DataTable -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history mr-2"></i>Historial de Registros</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="historial-table" class="table table-hover table-custom">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora Inicio</th>
                                    <th>Hora Fin</th>
                                    <th>Pausa Inicio</th>
                                    <th>Pausa Fin</th>
                                    <th>Tiempo Pausa</th>
                                    <th>Duración</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los datos se cargarán via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resumen Estadístico -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar mr-2"></i>Resumen del Período</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 id="total-horas-periodo" class="text-primary">0.00h</h4>
                            <small class="text-muted">Horas Totales</small>
                        </div>
                        <div class="col-md-3">
                            <h4 id="total-registros-periodo" class="text-success">0</h4>
                            <small class="text-muted">Total Registros</small>
                        </div>
                        <div class="col-md-3">
                            <h4 id="promedio-diario-periodo" class="text-info">0.00h</h4>
                            <small class="text-muted">Promedio Diario</small>
                        </div>
                        <div class="col-md-3">
                            <h4 id="dias-trabajados-periodo" class="text-warning">0</h4>
                            <small class="text-muted">Días Trabajados</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
<!-- Modal de confirmación para STOP -->
<div class="modal fade" id="confirmStopModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Detención</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-stop-circle fa-3x text-danger mb-3"></i>
                <h5>¿Estás seguro de que deseas detener el tiempo?</h5>
                <p class="mb-0">Tiempo transcurrido: <strong id="tiempo-final"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirm-stop">Sí, Detener</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal de Detalles del Registro -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">
                    <i class="fas fa-clock mr-2"></i>Detalles del Registro
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Información Básica -->
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white py-2">
                                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Información Básica</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>Fecha:</strong></td>
                                        <td id="detail-fecha">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Estado:</strong></td>
                                        <td><span id="detail-estado" class="badge">-</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Duración Total:</strong></td>
                                        <td><span id="detail-duracion" class="font-weight-bold">-</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tiempos -->
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white py-2">
                                <h6 class="mb-0"><i class="fas fa-history mr-2"></i>Linea de Tiempo</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>Inicio:</strong></td>
                                        <td id="detail-inicio">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fin:</strong></td>
                                        <td id="detail-fin">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tiempo Activo:</strong></td>
                                        <td id="detail-tiempo-activo">-</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Pausas -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-warning text-white py-2">
                                <h6 class="mb-0"><i class="fas fa-pause-circle mr-2"></i>Información de Pausas</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td><strong>Pausa Inicio:</strong></td>
                                                <td id="detail-pausa-inicio">-</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Pausa Fin:</strong></td>
                                                <td id="detail-pausa-fin">-</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td><strong>Tiempo en Pausa:</strong></td>
                                                <td><span id="detail-tiempo-pausa" class="text-info font-weight-bold">-</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Eficiencia:</strong></td>
                                                <td><span id="detail-eficiencia" class="font-weight-bold">-</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-secondary text-white py-2">
                                <h6 class="mb-0"><i class="fas fa-chart-bar mr-2"></i>Estadísticas del Día</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <div class="stat-item">
                                            <div class="stat-number text-primary" id="detail-total-dia">0.00h</div>
                                            <div class="stat-label small">Total del Día</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-item">
                                            <div class="stat-number text-success" id="detail-registros-dia">0</div>
                                            <div class="stat-label small">Registros del Día</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-item">
                                            <div class="stat-number text-info" id="detail-promedio-dia">0.00h</div>
                                            <div class="stat-label small">Promedio por Registro</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="imprimirDetalles()">
                    <i class="fas fa-print mr-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- ***********************************************************************  JS ****************************************************************************************************-->


@section('scripts')
<script>
$(document).ready(function() {
    const empleadoId = {{ $empleado->id }};
    let dataTable;

   // Inicializar Flatpickr para selector de mes
    const flatpickrInstance = flatpickr("#filterMes", {
        plugins: [
            new monthSelectPlugin({
                shorthand: true,
                dateFormat: "Y-m",
                altFormat: "F Y",
                theme: "material_blue"
            })
        ],
        locale: "es",
        onChange: function(selectedDates, dateStr, instance) {
            if (dateStr) {
                $('#btn-apply-filters').prop('disabled', false);
                mostrarInfoFiltro(dateStr);
            } else {
                $('#btn-apply-filters').prop('disabled', true);
                $('#filtroInfo').hide();
            }
        }
    });

    // Inicializar DataTable con manejo de estado vacío
    function initializeDataTable() {
        dataTable = $('#historial-table').DataTable({
            serverSide: true,
            ajax: {
                url: `/empleado/registro/${empleadoId}/datatable`,
                type: 'GET',
                data: function (d) {
                    const selectedDate = $('#filterMes').val();
                    if (selectedDate) {
                        const partes = selectedDate.split('-');
                        d.year = parseInt(partes[0]);
                        d.month = parseInt(partes[1]);
                    } else {
                        const now = new Date();
                        d.month = now.getMonth() + 1;
                        d.year = now.getFullYear();
                    }
                },
                error: function(xhr, error, thrown) {
                    console.error('Error DataTable:', xhr.responseText);
                    if (xhr.status === 200) return;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al cargar datos',
                        text: 'No se pudieron cargar los registros.',
                        timer: 3000
                    });
                }
            },
            columns: [
                { 
                    data: 'created_at',
                    name: 'created_at',
                    width: '12%',
                    render: function(data) {
                        return data ? new Date(data).toLocaleDateString('es-ES', {
                            weekday: 'short',
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        }) : '-';
                    }
                },
                { 
                    data: 'inicio',
                    name: 'inicio',
                    width: '10%',
                    render: function(data) {
                        return data ? new Date(data).toLocaleTimeString('es-ES') : '-';
                    }
                },
                { 
                    data: 'fin',
                    name: 'fin',
                    width: '10%',
                    render: function(data) {
                        return data ? new Date(data).toLocaleTimeString('es-ES') : 'En progreso';
                    }
                },
                { 
                    data: 'pausa_inicio',
                    name: 'pausa_inicio',
                    width: '10%',
                    render: function(data) {
                        return data ? new Date(data).toLocaleTimeString('es-ES') : '-';
                    }
                },
                { 
                    data: 'pausa_fin',
                    name: 'pausa_fin',
                    width: '10%',
                    render: function(data) {
                        return data ? new Date(data).toLocaleTimeString('es-ES') : '-';
                    }
                },
                { 
                    data: 'tiempo_pausa_total',
                    name: 'tiempo_pausa_total',
                    width: '10%',
                    render: function(data, type, row) {
                        console.log('Render tiempo pausa:', { data, row });
                        
                        // Si hay pausa_inicio y pausa_fin pero tiempo_pausa_total es 0, calcular manualmente
                        let tiempoPausa = Math.max(0, parseInt(data || 0));
                        
                        if (tiempoPausa === 0 && row.pausa_inicio && row.pausa_fin) {
                            // Calcular manualmente la diferencia
                            const inicio = new Date(row.pausa_inicio);
                            const fin = new Date(row.pausa_fin);
                            const diferenciaMs = fin - inicio;
                            tiempoPausa = Math.max(0, Math.floor(diferenciaMs / 1000));
                            
                            console.log('Cálculo manual pausa:', {
                                inicio: row.pausa_inicio,
                                fin: row.pausa_fin,
                                diferenciaMs: diferenciaMs,
                                tiempoPausa: tiempoPausa
                            });
                        }
                        
                        if (tiempoPausa === 0) {
                            if (row.pausa_inicio || row.pausa_fin) {
                                return '<span class="text-warning" title="Hubo pausas pero tiempo calculado es 0">0:00:00</span>';
                            }
                            return '<span class="text-muted">Sin pausas</span>';
                        }
                        
                        const hours = Math.floor(tiempoPausa / 3600);
                        const minutes = Math.floor((tiempoPausa % 3600) / 60);
                        const seconds = tiempoPausa % 60;
                        
                        return `<span class="text-info font-weight-bold">${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}</span>`;
                    }
                },
                { 
                    data: 'tiempo_total',
                    name: 'tiempo_total',
                    width: '10%',
                    render: function(data, type, row) {
                        if (!data || data === 0) {
                            return row.fin ? '00:00:00' : '-';
                        }
                        
                        const tiempoPositivo = Math.max(0, parseInt(data));
                        const hours = Math.floor(tiempoPositivo / 3600);
                        const minutes = Math.floor((tiempoPositivo % 3600) / 60);
                        const seconds = tiempoPositivo % 60;
                        
                        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    }
                },
                { 
                    data: 'estado',
                    name: 'estado',
                    width: '10%',
                    render: function(data) {
                        const statusMap = {
                            'activo': 'badge-active',
                            'pausado': 'badge-paused',
                            'completado': 'badge-completed'
                        };
                        const statusText = data ? data.charAt(0).toUpperCase() + data.slice(1) : 'Desconocido';
                        return `<span class="badge badge-status ${statusMap[data] || 'badge-secondary'}">${statusText}</span>`;
                    }
                },
                {
                    data: 'id',
                    name: 'actions',
                    width: '8%',
                    render: function(data) {
                        return data ? `
                            <button class="btn btn-sm btn-outline-primary" onclick="viewDetails(${data})" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                        ` : '';
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json',
                emptyTable: 'No hay registros de tiempo para el mes seleccionado',
                zeroRecords: 'No se encontraron registros que coincidan con el filtro'
            },
            order: [[0, 'desc']],
            scrollX: true,
            autoWidth: false,
            responsive: false,
            drawCallback: function(settings) {
                updatePeriodSummary();
                
                if (settings.json && settings.json.recordsTotal === 0) {
                    const api = this.api();
                    const $table = $(api.table().node());
                    const selectedDate = $('#filterMes').val();
                    const periodText = selectedDate ? `para ${formatMonthYear(selectedDate)}` : 'para el período seleccionado';
                    
                    $table.find('.dataTables_empty').html(
                        '<div class="text-center py-4">' +
                        '<i class="fas fa-clock fa-3x text-muted mb-3"></i>' +
                        `<h5 class="text-muted">No hay registros ${periodText}</h5>` +
                        '<p class="text-muted">Cuando trabajes durante este mes, aparecerán aquí tus registros.</p>' +
                        '</div>'
                    );
                }
            }
        });
    }
    // Aplicar filtros
    $('#btn-apply-filters').click(function() {
        const selectedDate = $('#filterMes').val();
        if (!selectedDate) {
            Swal.fire({
                icon: 'warning',
                title: 'Selecciona un mes',
                text: 'Por favor, selecciona un mes y año para filtrar.',
                timer: 3000
            });
            return;
        }
        
        dataTable.ajax.reload();
        
        const filterBtn = $(this);
        const originalText = filterBtn.html();
        filterBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i> FILTRANDO...');
        
        setTimeout(() => {
            filterBtn.html(originalText);
        }, 1000);
    });


    // Resetear filtros
    $('#btn-reset-filters').click(function() {
        flatpickrInstance.setDate('today');
        dataTable.ajax.reload();
        $('#filtroInfo').hide();
        
        const resetBtn = $(this);
        const originalText = resetBtn.html();
        resetBtn.html('<i class="fas fa-check mr-2"></i> ACTUAL');
        
        setTimeout(() => {
            resetBtn.html(originalText);
        }, 2000);
    });

    // Mostrar información del filtro aplicado
    function mostrarInfoFiltro(fecha) {
        const filtroInfo = $('#filtroInfo');
        const infoMes = $('#infoMes');
        
        if (!fecha || fecha.trim() === '') {
            filtroInfo.hide();
            return;
        }
        
        const partes = fecha.split('-');
        const año = partes[0];
        const mesNumero = parseInt(partes[1]);
        
        const meses = {
            1: 'enero', 2: 'febrero', 3: 'marzo', 4: 'abril',
            5: 'mayo', 6: 'junio', 7: 'julio', 8: 'agosto',
            9: 'septiembre', 10: 'octubre', 11: 'noviembre', 12: 'diciembre'
        };
        
        if (año && mesNumero && meses[mesNumero]) {
            const mesFormateado = `${meses[mesNumero]} de ${año}`;
            infoMes.text(mesFormateado);
            filtroInfo.show();
        }
    }


    // Limpiar filtro de mes
    function limpiarFiltroMes() {
        flatpickrInstance.clear();
        $('#filtroInfo').hide();
        dataTable.ajax.reload();
    }

    // Formatear mes y año para mostrar
    function formatMonthYear(dateString) {
        const partes = dateString.split('-');
        const año = partes[0];
        const mesNumero = parseInt(partes[1]);
        
        const meses = {
            1: 'enero', 2: 'febrero', 3: 'marzo', 4: 'abril',
            5: 'mayo', 6: 'junio', 7: 'julio', 8: 'agosto',
            9: 'septiembre', 10: 'octubre', 11: 'noviembre', 12: 'diciembre'
        };
        
        return meses[mesNumero] ? `${meses[mesNumero]} de ${año}` : dateString;
    }

    // Actualizar resumen del período
    function updatePeriodSummary() {
        const selectedDate = $('#filterMes').val(); // CAMBIADO: filterMes en lugar de filter-month-year
        let month = null;
        let year = null;

        if (selectedDate) {
            const partes = selectedDate.split('-');
            year = parseInt(partes[0]);
            month = parseInt(partes[1]);
        } else {
            const now = new Date();
            month = now.getMonth() + 1;
            year = now.getFullYear();
        }

        $.ajax({
            url: `/empleado/registro/${empleadoId}/resumen-periodo`,
            method: 'GET',
            data: {
                month: month,
                year: year
            },
            success: function(response) {
                $('#total-horas-periodo').text(response.total_horas + 'h');
                $('#total-registros-periodo').text(response.total_registros);
                $('#promedio-diario-periodo').text(response.promedio_diario + 'h');
                $('#dias-trabajados-periodo').text(response.dias_trabajados);
                
                const periodTitle = selectedDate ? 
                    `Resumen de ${formatMonthYear(selectedDate)}` : 
                    'Resumen del Mes Actual';
                    
                $('.card-header h5').last().html(`<i class="fas fa-chart-bar mr-2"></i>${periodTitle}`);
            },
            error: function(xhr) {
                console.error('Error al cargar resumen:', xhr);
            }
        });
    }

    // Control de tiempo (mantener tu lógica existente)
    const btnStart = $('#btn-start');
    const btnPause = $('#btn-pause');
    const btnStop = $('#btn-stop');
    const btnGroupActive = $('#btn-group-active');
    const estadoActual = $('#estado-actual');
    const tiempoTranscurridoElement = $('#tiempo-transcurrido');

    // Verificar estado al cargar la página
    checkEstado();

    // Evento START
    btnStart.click(function() {
        console.log('Iniciando tiempo para empleado:', empleadoId);
        
        $.ajax({
            url: `/empleado/registro/${empleadoId}/start`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Respuesta START:', response);
                if (response.success) {
                    btnStart.hide();
                    btnGroupActive.show();
                    estadoActual.text('Estado: Activo');
                    
                    // Recargar todo automáticamente
                    recargarDatosCompletos();
                    checkEstado();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Tiempo iniciado',
                        text: 'El control de tiempo ha comenzado',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                console.error('Error START:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo iniciar el tiempo'
                });
            }
        });
    });

    // Evento PAUSE
    btnPause.click(function() {
        $.ajax({
            url: `/empleado/registro/${empleadoId}/pause`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Actualizar estado y recargar datos
                    checkEstado();
                    recargarDatosCompletos();
                    
                    Swal.fire({
                        icon: 'success',
                        title: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                console.error('Error PAUSE:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo pausar el tiempo'
                });
            }
        });
    });

    // Evento STOP 
    // Evento STOP - VERSIÓN CON CORRECCIÓN DE EMERGENCIA
btnStop.click(function() {
    console.log('=== INICIANDO STOP CON CORRECCIÓN ===');
    
    $.ajax({
        url: `/empleado/registro/${empleadoId}/estado`,
        method: 'GET',
        success: function(estadoResponse) {
            console.log('Respuesta estado:', estadoResponse);
            
            if (estadoResponse.activo) {
                let tiempoFormateado = estadoResponse.tiempo_formateado;
                let tiempoSegundos = estadoResponse.tiempo_transcurrido;
                
                // CORRECCIÓN DE EMERGENCIA: Si el tiempo bruto es negativo, forzar cálculo
                if (estadoResponse.debug && estadoResponse.debug.tiempo_bruto_formateado.includes('-')) {
                    console.log('❌ TIEMPO BRUTO NEGATIVO - APLICANDO CORRECCIÓN DE EMERGENCIA');
                    
                    // Calcular tiempo manualmente basado en la hora actual
                    const ahora = new Date();
                    const inicio = new Date(estadoResponse.inicio_original);
                    
                    console.log('Fechas para cálculo:', {
                        ahora: ahora.toLocaleString(),
                        inicio: inicio.toLocaleString(),
                        inicio_original: estadoResponse.inicio_original
                    });
                    
                    // Si la fecha de inicio es en el futuro, forzar cálculo con hora actual menos 1 hora
                    if (inicio > ahora) {
                        console.log('Fecha de inicio en el futuro, forzando cálculo...');
                        const inicioForzado = new Date(ahora.getTime() - (60 * 60 * 1000)); // 1 hora antes
                        const diferenciaMs = ahora - inicioForzado;
                        const segundosBrutos = Math.floor(diferenciaMs / 1000);
                        const segundosNetos = Math.max(0, segundosBrutos - estadoResponse.tiempo_pausa_total);
                        
                        tiempoFormateado = formatTime(segundosNetos);
                        tiempoSegundos = segundosNetos;
                        
                        console.log('Cálculo forzado:', {
                            segundosBrutos,
                            segundosNetos,
                            tiempoFormateado
                        });
                    }
                }
                
                // Si después de todo sigue siendo 0, usar un valor mínimo
                if (tiempoSegundos === 0 && estadoResponse.debug) {
                    const segundosNetosManual = Math.max(1, estadoResponse.debug.segundos_totales - estadoResponse.debug.pausa_acumulada);
                    if (segundosNetosManual > 0) {
                        tiempoFormateado = formatTime(segundosNetosManual);
                        tiempoSegundos = segundosNetosManual;
                        console.log('Aplicando cálculo manual final:', tiempoFormateado);
                    }
                }

                const tiempoPausaFormateado = estadoResponse.debug?.pausa_formateada || '00:00:00';
                const tiempoBrutoFormateado = estadoResponse.debug?.tiempo_bruto_formateado || '00:00:00';
                
                let contenidoModal = `
                    <div class="mb-2">
                        <strong class="h4 text-primary">${tiempoFormateado}</strong>
                    </div>
                    <div class="small">
                        <div>⏱️ Tiempo bruto: ${tiempoBrutoFormateado}</div>
                        <div>⏸️ Tiempo pausa: ${tiempoPausaFormateado}</div>
                    </div>
                `;
                
                // Mostrar advertencia si se aplicó corrección
                if (estadoResponse.correccion_aplicada || tiempoBrutoFormateado.includes('-')) {
                    contenidoModal += `
                        <div class="alert alert-warning small mt-2">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Se corrigió automáticamente un problema con la fecha/hora del sistema
                        </div>
                    `;
                }
                
                $('#tiempo-final').html(contenidoModal);
                $('#confirmStopModal').modal('show');
                $('#confirm-stop').data('tiempo-total', tiempoSegundos);
                $('#confirm-stop').data('tiempo-formateado', tiempoFormateado);
                
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'No hay tiempo activo',
                    text: 'No hay un registro de tiempo activo para detener.'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener estado:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo obtener el tiempo actual: ' + error
            });
        }
    });
});

    // Confirmar STOP
    $('#confirm-stop').click(function() {
        const tiempoTotal = $(this).data('tiempo-total');
        const tiempoFormateado = $(this).data('tiempo-formateado');
        const confirmBtn = $(this);
        const originalText = confirmBtn.html();
        
        confirmBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i> DETENIENDO...');
        confirmBtn.prop('disabled', true);

        $.ajax({
            url: `/empleado/registro/${empleadoId}/stop`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tiempo_total: tiempoTotal
            },
            success: function(response) {
                if (response.success) {
                    // Resetear interfaz
                    btnStart.show();
                    btnGroupActive.hide();
                    estadoActual.text('Estado: No iniciado');
                    tiempoTranscurridoElement.text('Tiempo: 00:00:00');
                    detenerActualizacionTiempoReal();
                    
                    // Recargar todos los datos automáticamente
                    recargarDatosCompletos();
                    
                    $('#confirmStopModal').modal('hide');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Tiempo detenido',
                        html: `Tiempo registrado: <strong>${response.tiempo_formateado || tiempoFormateado}</strong>`,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                console.error('Error STOP:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo detener el tiempo'
                });
            },
            complete: function() {
                confirmBtn.html(originalText);
                confirmBtn.prop('disabled', false);
            }
        });
    });

    // Función para verificar estado
    function checkEstado() {
        $.ajax({
            url: `/empleado/registro/${empleadoId}/estado`,
            method: 'GET',
            success: function(response) {
                console.log('Estado actual:', response);
                if (response.activo) {
                    btnStart.hide();
                    btnGroupActive.show();
                    estadoActual.text(`Estado: ${response.estado.charAt(0).toUpperCase() + response.estado.slice(1)}`);
                    
                    // CORRECIÓN: Iniciar actualización en tiempo real SIEMPRE que esté activo
                    if (response.estado === 'activo') {
                        iniciarActualizacionTiempoReal(response.tiempo_transcurrido || 0);
                        tiempoTranscurridoElement.text(`Tiempo: ${response.tiempo_formateado || '00:00:00'}`);
                    } else if (response.estado === 'pausado') {
                        detenerActualizacionTiempoReal();
                        tiempoTranscurridoElement.text(`Tiempo: ${response.tiempo_formateado || '00:00:00'} (Pausado)`);
                    }
                } else {
                    btnStart.show();
                    btnGroupActive.hide();
                    estadoActual.text('Estado: No iniciado');
                    tiempoTranscurridoElement.text('Tiempo: 00:00:00');
                    detenerActualizacionTiempoReal();
                }
            },
            error: function(xhr) {
                console.error('Error al verificar estado:', xhr);
            }
        });
    }


    // Función para actualización en tiempo real
    let intervaloActualizacion = null;

    function iniciarActualizacionTiempoReal(tiempoInicial) {
        detenerActualizacionTiempoReal();
        
        let segundosTranscurridos = tiempoInicial || 0;
        
        intervaloActualizacion = setInterval(function() {
            segundosTranscurridos++;
            
            const horas = Math.floor(segundosTranscurridos / 3600);
            const minutos = Math.floor((segundosTranscurridos % 3600) / 60);
            const segundos = segundosTranscurridos % 60;
            
            const tiempoFormateado = `${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
            tiempoTranscurridoElement.text(`Tiempo: ${tiempoFormateado}`);
        }, 1000);
    }

    function detenerActualizacionTiempoReal() {
        if (intervaloActualizacion) {
            clearInterval(intervaloActualizacion);
            intervaloActualizacion = null;
        }
    }


    // Función para cargar historial
    function loadHistorial() {
        $.ajax({
            url: `/empleado/registro/${empleadoId}/historial`,
            method: 'GET',
            success: function(response) {
                console.log('Historial cargado:', response);
                if (response.success) {
                    $('#historial-hoy').html('');
                    
                    if (response.historial && response.historial.length > 0) {
                        response.historial.forEach(registro => {
                            const inicio = new Date(registro.inicio).toLocaleTimeString();
                            const fin = registro.fin ? new Date(registro.fin).toLocaleTimeString() : null;
                            const tiempoTotal = registro.tiempo_total ? formatTime(registro.tiempo_total) : null;
                            
                            $('#historial-hoy').append(`
                                <div class="historial-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <strong>Inicio:</strong> ${inicio}
                                        ${fin ? `<strong class="ml-3">Fin:</strong> ${fin}` : '<span class="badge badge-warning ml-3">En progreso</span>'}
                                    </div>
                                    <div>
                                        ${tiempoTotal ? `<span class="badge badge-info">${tiempoTotal}</span>` : ''}
                                    </div>
                                </div>
                            `);
                        });
                    } else {
                        $('#historial-hoy').html('<p class="text-muted text-center">No hay registros para hoy.</p>');
                    }
                }
            },
            error: function(xhr) {
                console.error('Error al cargar historial:', xhr);
            }
        });
    }

    // Función mejorada para recargar datos
    function recargarDatosCompletos() {
        // Recargar DataTable
        dataTable.ajax.reload(null, false); // false = mantener la página actual
        
        // Actualizar resumen
        updatePeriodSummary();
        
        // Actualizar estadísticas del perfil
        actualizarEstadisticasPerfil();
    }

    // Función para actualizar estadísticas del perfil
    function actualizarEstadisticasPerfil() {
        $.ajax({
            url: `/empleado/registro/${empleadoId}/estadisticas-mes`,
            method: 'GET',
            success: function(response) {
                // Actualizar las estadísticas en la tarjeta de perfil
                $('.stats-number').first().text(response.total_registros || '0');
                $('.stats-number').last().text((response.total_horas || '0.00') + 'h');
                $('.text-muted small').text('Promedio diario: ' + (response.promedio_horas || '0.00') + 'h');
            },
            error: function(xhr) {
                console.error('Error al actualizar estadísticas:', xhr);
            }
        });
    }



    // Función auxiliar para formatear tiempo
    function formatTime(seconds) {
        if (!seconds || seconds === 0) return '00:00:00';
        
        const segundosPositivos = Math.max(0, parseInt(seconds));
        const hours = Math.floor(segundosPositivos / 3600);
        const minutes = Math.floor((segundosPositivos % 3600) / 60);
        const secs = segundosPositivos % 60;
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    // Inicializar
    initializeDataTable();
    checkEstado();
    loadHistorial();

    // Función para ver detalles del registro - VERSIÓN MEJORADA CON MANEJO DE ERRORES
    window.viewDetails = function(registroId) {
        console.log('Cargando detalles del registro:', registroId);
        
        // Mostrar loading en el modal
        $('#detailsModal .modal-body').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
                <p class="text-muted">Cargando detalles del registro...</p>
            </div>
        `);
        

        const url = `/empleado/registro/${empleadoId}/detalles/${registroId}`;
        // Mostrar el modal inmediatamente
        $('#detailsModal').modal('show');
        
        // Obtener datos del registro via AJAX
        $.ajax({
            url: url,
            method: 'GET',
            timeout: 10000, // 10 segundos timeout
            success: function(response) {
                console.log('Respuesta detalles:', response);
                
                if (response.success && response.registro) {
                    mostrarDetallesEnModal(response.registro, response.estadisticasDia);
                } else {
                    $('#detailsModal .modal-body').html(`
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <h5>Error al cargar detalles</h5>
                            <p class="text-muted">${response.message || 'No se pudieron cargar los detalles del registro.'}</p>
                            <button class="btn btn-secondary mt-2" onclick="$('#detailsModal').modal('hide')">Cerrar</button>
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar detalles:', xhr);
                
                let mensajeError = 'Error de conexión';
                if (xhr.status === 404) {
                    mensajeError = 'Registro no encontrado';
                } else if (xhr.status === 403) {
                    mensajeError = 'No tienes permiso para ver este registro';
                } else if (xhr.status === 500) {
                    mensajeError = 'Error interno del servidor';
                } else if (status === 'timeout') {
                    mensajeError = 'Tiempo de espera agotado';
                }
                
                $('#detailsModal .modal-body').html(`
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                        <h5>${mensajeError}</h5>
                        <p class="text-muted">No se pudo cargar la información del registro.</p>
                        <p class="small text-muted">Código de error: ${xhr.status || 'N/A'}</p>
                        <button class="btn btn-secondary mt-2" onclick="$('#detailsModal').modal('hide')">Cerrar</button>
                    </div>
                `);
            }
        });
    };

    // Función para mostrar detalles en el modal - VERSIÓN CORREGIDA
function mostrarDetallesEnModal(registro, estadisticasDia) {
    console.log('Mostrando detalles:', registro);
    
    // Formatear fechas y tiempos
    const fecha = registro.created_at ? new Date(registro.created_at).toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }) : '-';
    
    const inicio = registro.inicio ? new Date(registro.inicio).toLocaleTimeString('es-ES') : '-';
    const fin = registro.fin ? new Date(registro.fin).toLocaleTimeString('es-ES') : 'En progreso';
    const pausaInicio = registro.pausa_inicio ? new Date(registro.pausa_inicio).toLocaleTimeString('es-ES') : 'Sin pausas';
    const pausaFin = registro.pausa_fin ? new Date(registro.pausa_fin).toLocaleTimeString('es-ES') : (registro.pausa_inicio ? 'Pausa activa' : 'Sin pausas');
    
    // Calcular tiempos
    const tiempoTotal = registro.tiempo_total ? formatTime(registro.tiempo_total) : '00:00:00';
    const tiempoPausa = registro.tiempo_pausa_total ? formatTime(registro.tiempo_pausa_total) : '00:00:00';
    
    // Calcular tiempo activo (tiempo total - tiempo pausa)
    const tiempoActivoSegundos = Math.max(0, (registro.tiempo_total || 0) - (registro.tiempo_pausa_total || 0));
    const tiempoActivo = formatTime(tiempoActivoSegundos);
    
    // Calcular eficiencia
    let eficiencia = '-';
    if (registro.tiempo_total > 0 && registro.tiempo_pausa_total > 0) {
        const porcentaje = ((tiempoActivoSegundos / registro.tiempo_total) * 100).toFixed(1);
        eficiencia = `${porcentaje}%`;
        
        // Color según eficiencia
        if (porcentaje >= 90) eficiencia = `<span class="text-success">${eficiencia} ⭐</span>`;
        else if (porcentaje >= 70) eficiencia = `<span class="text-warning">${eficiencia} 👍</span>`;
        else eficiencia = `<span class="text-danger">${eficiencia} 👎</span>`;
    } else if (registro.tiempo_total > 0) {
        eficiencia = `<span class="text-success">100% ⭐</span>`;
    }
    
    // Estado con colores
    let estadoBadge = '';
    switch(registro.estado) {
        case 'activo':
            estadoBadge = '<span class="badge badge-active">🟢 Activo</span>';
            break;
        case 'pausado':
            estadoBadge = '<span class="badge badge-paused">🟡 Pausado</span>';
            break;
        case 'completado':
            estadoBadge = '<span class="badge badge-completed">🔵 Completado</span>';
            break;
        default:
            estadoBadge = '<span class="badge badge-secondary">⚫ Desconocido</span>';
    }
    
    // Construir el contenido HTML completo
    const contenidoHTML = `
        <div class="row">
            <!-- Información Básica -->
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Información Básica</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>Fecha:</strong></td>
                                <td>${fecha}</td>
                            </tr>
                            <tr>
                                <td><strong>Estado:</strong></td>
                                <td>${estadoBadge}</td>
                            </tr>
                            <tr>
                                <td><strong>Duración Total:</strong></td>
                                <td><span class="font-weight-bold text-primary">${tiempoTotal}</span></td>
                            </tr>
                            <tr>
                                <td><strong>ID Registro:</strong></td>
                                <td><small class="text-muted">#${registro.id}</small></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Tiempos -->
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-info text-white py-2">
                        <h6 class="mb-0"><i class="fas fa-history mr-2"></i>Línea de Tiempo</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>Inicio:</strong></td>
                                <td>${inicio}</td>
                            </tr>
                            <tr>
                                <td><strong>Fin:</strong></td>
                                <td>${fin}</td>
                            </tr>
                            <tr>
                                <td><strong>Tiempo Activo:</strong></td>
                                <td><span class="font-weight-bold text-success">${tiempoActivo}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de Pausas -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header bg-warning text-white py-2">
                        <h6 class="mb-0"><i class="fas fa-pause-circle mr-2"></i>Información de Pausas</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>Pausa Inicio:</strong></td>
                                        <td>${pausaInicio}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pausa Fin:</strong></td>
                                        <td>${pausaFin}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>Tiempo en Pausa:</strong></td>
                                        <td><span class="text-info font-weight-bold">${tiempoPausa}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Eficiencia:</strong></td>
                                        <td>${eficiencia}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white py-2">
                        <h6 class="mb-0"><i class="fas fa-chart-bar mr-2"></i>Estadísticas del Día</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="stat-item">
                                    <div class="stat-number text-primary">${estadisticasDia ? estadisticasDia.total_horas_dia + 'h' : '0.00h'}</div>
                                    <div class="stat-label small">Total del Día</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-item">
                                    <div class="stat-number text-success">${estadisticasDia ? estadisticasDia.total_registros_dia : '0'}</div>
                                    <div class="stat-label small">Registros del Día</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-item">
                                    <div class="stat-number text-info">${estadisticasDia ? estadisticasDia.promedio_por_registro + 'h' : '0.00h'}</div>
                                    <div class="stat-label small">Promedio por Registro</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Reemplazar todo el contenido del modal-body
    $('#detailsModal .modal-body').html(contenidoHTML);
}

    // Función para imprimir detalles - VERSIÓN CORREGIDA
    function imprimirDetalles() {
        const modalBody = $('#detailsModal .modal-body');
        const ventanaImpresion = window.open('', '_blank');
        
        const contenido = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Detalles del Registro</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
                    .section { margin-bottom: 20px; }
                    .section-title { background: #f8f9fa; padding: 10px; font-weight: bold; border-left: 4px solid #007bff; }
                    table { width: 100%; border-collapse: collapse; }
                    td { padding: 8px; border-bottom: 1px solid #ddd; }
                    .badge { padding: 4px 8px; border-radius: 4px; color: white; }
                    .badge-active { background: #28a745; }
                    .badge-paused { background: #ffc107; color: black; }
                    .badge-completed { background: #007bff; }
                    .stats { display: flex; justify-content: space-around; text-align: center; margin-top: 20px; }
                    .stat-item { padding: 10px; }
                    .stat-number { font-size: 24px; font-weight: bold; }
                    @media print {
                        body { margin: 0; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Detalles del Registro de Tiempo</h1>
                    <p>Generado el: ${new Date().toLocaleString('es-ES')}</p>
                </div>
                
                ${modalBody.html()}
            </body>
            </html>
        `;
        
        ventanaImpresion.document.write(contenido);
        ventanaImpresion.document.close();
        ventanaImpresion.focus();
        
        // Esperar a que se cargue el contenido antes de imprimir
        setTimeout(() => {
            ventanaImpresion.print();
            // ventanaImpresion.close(); // Opcional: cerrar después de imprimir
        }, 500);
    }
});



</script>
@endsection


<!-- ***********************************************************************  CSS ****************************************************************************************************-->

@section('styles')
<style>
:root {
    --primary: #4361ee;
    --secondary: #3f37c9;
    --success: #4cc9f0;
    --danger: #f72585;
    --warning: #f8961e;
    --info: #4895ef;
    --light: #f8f9fa;
    --dark: #212529;
    --sidebar-bg: #2c3e50;
    --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: var(--card-shadow);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 20px;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.card-header {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-radius: 15px 15px 0 0 !important;
    border: none;
    font-weight: 600;
    padding: 15px 20px;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
}

.stats-card {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    margin-bottom: 15px;
}

.stats-number {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stats-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.btn-control {
    padding: 15px 30px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 50px;
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    margin: 5px;
}

.btn-start {
    background: linear-gradient(135deg, #00b09b, #96c93d);
    color: white;
}

.btn-pause {
    background: linear-gradient(135deg, #ff9a00, #ff6a00);
    color: white;
}

.btn-stop {
    background: linear-gradient(135deg, #ff416c, #ff4b2b);
    color: white;
}

.btn-control:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

.time-display {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    margin: 20px 0;
}

#tiempo-transcurrido {
    font-size: 2.5rem;
    font-weight: bold;
    font-family: 'Courier New', monospace;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.filter-section {
    background: var(--light);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
}

.table-custom {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--card-shadow);
}

.badge-status {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 600;
}

.badge-active {
    background: linear-gradient(135deg, #00b09b, #96c93d);
    color: white;
}

.badge-paused {
    background: linear-gradient(135deg, #ff9a00, #ff6a00);
    color: white;
}

.badge-completed {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

/* Agrega esto a tus estilos */
.dataTables_empty {
    padding: 40px !important;
    text-align: center !important;
}

.no-data-container {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

.no-data-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}


/* Estilos para el selector de mes */
.flatpickr-monthSelect-months {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 5px;
    padding: 10px;
}

.flatpickr-monthSelect-month {
    padding: 10px;
    border-radius: 5px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.flatpickr-monthSelect-month:hover {
    background: #4361ee;
    color: white;
}

.flatpickr-monthSelect-month.selected {
    background: #4361ee;
    color: white;
}

/* Mejorar la apariencia del input */
#filter-month-year {
    background-color: white;
    cursor: pointer;
    font-weight: 500;
}

#filter-month-year:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}

/* Estilos para el DataTable responsive */
.table-responsive {
    width: 100%;
    overflow-x: auto;
}

#historial-table {
    width: 100% !important;
    min-width: 800px; /* Ancho mínimo para evitar que se comprima demasiado */
}

/* Ajustar el ancho de las columnas específicas */
#historial-table th:nth-child(1), /* Fecha */
#historial-table td:nth-child(1) {
    min-width: 120px;
    max-width: 150px;
}

#historial-table th:nth-child(2), /* Hora Inicio */
#historial-table th:nth-child(3), /* Hora Fin */
#historial-table th:nth-child(4), /* Pausa Inicio */
#historial-table th:nth-child(5), /* Pausa Fin */
#historial-table td:nth-child(2),
#historial-table td:nth-child(3),
#historial-table td:nth-child(4),
#historial-table td:nth-child(5) {
    min-width: 90px;
    max-width: 110px;
}

#historial-table th:nth-child(6), /* Tiempo Pausa */
#historial-table th:nth-child(7), /* Duración */
#historial-table td:nth-child(6),
#historial-table td:nth-child(7) {
    min-width: 100px;
    max-width: 120px;
}

#historial-table th:nth-child(8), /* Estado */
#historial-table td:nth-child(8) {
    min-width: 100px;
    max-width: 120px;
}

#historial-table th:nth-child(9), /* Acciones */
#historial-table td:nth-child(9) {
    min-width: 80px;
    max-width: 100px;
    text-align: center;
}

/* Mejorar la visualización en móviles */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.85rem;
    }
    
    #historial-table {
        min-width: 1000px; /* Más ancho en móviles para mejor scroll */
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

/* Asegurar que el contenedor principal ocupe todo el ancho */
.container-fluid {
    padding-left: 15px;
    padding-right: 15px;
}

.col-lg-8 {
    padding-left: 15px;
    padding-right: 15px;
}

/* Estilos para el modal de detalles */
.stat-item {
    padding: 15px;
    border-radius: 10px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    margin: 5px;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    color: #6c757d;
    font-size: 0.85rem;
}

.table-sm td {
    padding: 8px 5px;
    border: none;
}

.card .card-header {
    font-weight: 600;
    font-size: 0.9rem;
}

/* Mejorar la visualización de badges */
.badge-active {
    background: linear-gradient(135deg, #00b09b, #96c93d);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
}

.badge-paused {
    background: linear-gradient(135deg, #ff9a00, #ff6a00);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
}

.badge-completed {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
}

/* Responsive para el modal */
@media (max-width: 768px) {
    .modal-lg {
        margin: 10px;
    }
    
    .stat-number {
        font-size: 1.4rem;
    }
    
    .card-body {
        padding: 10px;
    }
}

/* Animación de carga */
.spinner-border {
    width: 3rem;
    height: 3rem;
}

</style>
@endsection