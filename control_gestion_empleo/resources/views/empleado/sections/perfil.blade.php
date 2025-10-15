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
                                    <th>Dirección</th>
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
        console.log('🔄 Inicializando DataTable...');
    
        // Destruir si ya existe
        if ($.fn.DataTable.isDataTable('#historial-table')) {
            dataTable.clear().destroy();
            $('#historial-table').empty();
        }
        
        dataTable = $('#historial-table').DataTable({
            serverSide: true,
            //processing: true,
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
                    console.log('📤 Parámetros DataTable:', d);
                },
                dataSrc: function (json) {
                    console.log('📥 Datos recibidos DataTable:', json);
                    return json.data;
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
                    data: 'direccion',
                    name: 'direccion',
                    width: '15%',
                    render: function(data, type, row) {
                        const ciudad = row.ciudad || '';
                        const pais = row.pais || '';
                        
                        // Mostrar ciudad y país si son válidos
                        if (ciudad && pais && 
                            !ciudad.includes('GPS') && 
                            !ciudad.includes('Coordenadas') &&
                            !pais.includes('GPS')) {
                            
                            return `
                                <div class="ubicacion-info" title="${data || 'Ubicación registrada'}">
                                    <i class="fas fa-map-marker-alt text-success mr-1"></i>
                                    <small>${ciudad}, ${pais}</small>
                                </div>
                            `;
                        }
                        
                        // Si tenemos coordenadas pero no ciudad específica
                        if (data && data.includes('Ubicación GPS')) {
                            // Intentar mostrar algo más específico
                            if (ciudad && ciudad !== 'Ubicación GPS') {
                                return `
                                    <div class="ubicacion-info" title="${data}">
                                        <i class="fas fa-map-marker-alt text-info mr-1"></i>
                                        <small>${ciudad}</small>
                                    </div>
                                `;
                            }
                            
                            return `
                                <div class="ubicacion-info" title="${data}">
                                    <i class="fas fa-map-marker-alt text-warning mr-1"></i>
                                    <small>Ubicación por GPS</small>
                                </div>
                            `;
                        }
                        
                        return '<span class="text-muted">Sin ubicación</span>';
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
                url: "{{ asset('js/datatables/Spanish.json') }}",
                emptyTable: 'No hay registros para el mes seleccionado',
                zeroRecords: 'No se encontraron registros que coincidan'
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
    let intervaloActualizacion = null;


    const GOOGLE_MAPS_API_KEY = '{{ $googleMapsApiKey  }}';

    // Verificar estado al cargar la página
    //checkEstado();


    // =============================================
    // FUNCIONES DE GEOLOCALIZACIÓN
    // =============================================

    // Función optimizada para obtener ubicación
    function obtenerUbicacionGoogleMaps() {
        return new Promise((resolve, reject) => {
            console.log('🔍 Iniciando geolocalización...');
            
            if (!navigator.geolocation) {
                reject(new Error('Geolocalización no soportada'));
                return;
            }

            // Opciones optimizadas para mayor velocidad
            const opciones = {
                enableHighAccuracy: true,    // GPS para mejor precisión
                timeout: 10000,              // 10 segundos máximo
                maximumAge: 30000            // Cache de 30 segundos
            };

            const inicioTiempo = Date.now();
            
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const tiempoTranscurrido = Date.now() - inicioTiempo;
                    console.log(`✅ Geolocalización exitosa en ${tiempoTranscurrido}ms`);
                    
                    const ubicacion = {
                        latitud: position.coords.latitude,
                        longitud: position.coords.longitude,
                        precision: Math.round(position.coords.accuracy)
                    };
                    resolve(ubicacion);
                },
                (error) => {
                    const tiempoTranscurrido = Date.now() - inicioTiempo;
                    console.error(`❌ Error en geolocalización después de ${tiempoTranscurrido}ms:`, error);
                    reject(new Error(`GPS: ${obtenerMensajeErrorGeolocalizacion(error)}`));
                },
                opciones
            );
        });
    }

   // Función optimizada para obtener dirección
    function obtenerDireccionGoogle(latitud, longitud) {
        return new Promise((resolve, reject) => {
            if (!GOOGLE_MAPS_API_KEY || GOOGLE_MAPS_API_KEY === 'TU_API_KEY_AQUI') {
                reject(new Error('API Key de Google Maps no configurada'));
                return;
            }

            // URL optimizada - solo pedir los campos necesarios
            const url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitud},${longitud}&key=${GOOGLE_MAPS_API_KEY}&language=es`;
            
            console.log('🗺️ Consultando Google Geocoding API...');
            const inicioTiempo = Date.now();
            
            // Usar AbortController para timeout
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 8000); // 8 segundos máximo

            fetch(url, { signal: controller.signal })
                .then(response => {
                    clearTimeout(timeoutId);
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const tiempoTranscurrido = Date.now() - inicioTiempo;
                    console.log(`✅ Google API respondió en ${tiempoTranscurrido}ms`);
                    
                    if (data.status === 'OK' && data.results.length > 0) {
                        // Usar el primer resultado (más relevante)
                        const address = data.results[0];
                        const componentes = extraerComponentesDireccion(address.address_components);
                        
                        const resultado = {
                            direccion: address.formatted_address,
                            ciudad: componentes.ciudad,
                            pais: componentes.pais
                        };
                        
                        console.log('📍 Dirección obtenida:', resultado);
                        resolve(resultado);
                    } else {
                        reject(new Error('Google API: ' + data.status));
                    }
                })
                .catch(error => {
                    clearTimeout(timeoutId);
                    if (error.name === 'AbortError') {
                        reject(new Error('Timeout en Google API'));
                    } else {
                        reject(new Error('Error Google API: ' + error.message));
                    }
                });
        });
    }


    // Función para encontrar la mejor ubicación entre todos los resultados
function encontrarMejorUbicacion(resultados) {
    let mejorUbicacion = {
        direccion: '',
        ciudad: 'Ubicación GPS',
        pais: 'GPS'
    };

    // Buscar en todos los resultados
    for (const resultado of resultados) {
        const componentes = extraerComponentesDireccion(resultado.address_components);
        const tipos = resultado.types;
        
        console.log('🔍 Analizando resultado:', { tipos, componentes });
        
        // Priorizar resultados que tengan localidad
        if (tipos.includes('locality') || tipos.includes('sublocality')) {
            mejorUbicacion = {
                direccion: resultado.formatted_address,
                ciudad: componentes.ciudad,
                pais: componentes.pais
            };
            break;
        }
        
        // Si no encontramos localidad, usar el primer resultado con ciudad
        if (componentes.ciudad !== 'Ciudad desconocida' && !mejorUbicacion.direccion) {
            mejorUbicacion = {
                direccion: resultado.formatted_address,
                ciudad: componentes.ciudad,
                pais: componentes.pais
            };
        }
    }

    // Si no encontramos buena información, usar el primer resultado
    if (!mejorUbicacion.direccion && resultados.length > 0) {
        const componentes = extraerComponentesDireccion(resultados[0].address_components);
        mejorUbicacion = {
            direccion: resultados[0].formatted_address,
            ciudad: componentes.ciudad,
            pais: componentes.pais
        };
    }

    return mejorUbicacion;
}

    // Función para extraer componentes de la dirección
    function extraerComponentesDireccion(componentes) {
        const resultado = {
            ciudad: 'Ciudad desconocida',
            pais: 'País desconocido',
            codigo_postal: '',
            barrio: '',
            provincia: ''
        };

        componentes.forEach(componente => {
            const tipos = componente.types;
            
            if (tipos.includes('locality')) {
                resultado.ciudad = componente.long_name;
            } else if (tipos.includes('country')) {
                resultado.pais = componente.long_name;
            } else if (tipos.includes('postal_code')) {
                resultado.codigo_postal = componente.long_name;
            } else if (tipos.includes('sublocality') || tipos.includes('neighborhood')) {
                resultado.barrio = componente.long_name;
            } else if (tipos.includes('administrative_area_level_1')) {
                resultado.provincia = componente.long_name;
            }
        });

        return resultado;
    }

    // Función para obtener mensajes de error amigables
    function obtenerMensajeErrorGeolocalizacion(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                return 'Permiso de ubicación denegado';
            case error.POSITION_UNAVAILABLE:
                return 'Ubicación no disponible';
            case error.TIMEOUT:
                return 'Tiempo de espera agotado';
            default:
                return 'Error desconocido';
        }
    }

    // Función para obtener ubicación aproximada por IP
    function obtenerUbicacionPorIP() {
        return new Promise((resolve, reject) => {
            console.log('🌐 Obteniendo ubicación por IP...');
            
            fetch('https://ipapi.co/json/')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en respuesta de ipapi');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('📍 Ubicación por IP obtenida:', data);
                    resolve({
                        latitud: data.latitude,
                        longitud: data.longitude,
                        direccion: `${data.city || 'Ciudad desconocida'}, ${data.region || 'Región desconocida'}, ${data.country_name || 'País desconocido'}`,
                        ciudad: data.city || 'Ciudad por IP',
                        pais: data.country_name || 'País por IP',
                        precision: 50000,
                        tipo: 'aproximada_por_IP'
                    });
                })
                .catch(error => {
                    console.error('❌ Error ubicación por IP:', error);
                    reject(error);
                });
        });
    }

    // =============================================
    // FUNCIONES PRINCIPALES DE CONTROL DE TIEMPO
    // =============================================

    // Función para iniciar registro CON geolocalización
    function iniciarRegistroTiempo(datosGeolocalizacion) {
        console.log('🚀 Iniciando registro con datos:', datosGeolocalizacion);
        
        // Actualizar mensaje rápidamente
        Swal.update({
            title: 'Iniciando tiempo...',
            text: 'Registrando en el sistema'
        });

        const datosEnvio = {
            _token: '{{ csrf_token() }}',
            latitud: datosGeolocalizacion.latitud,
            longitud: datosGeolocalizacion.longitud,
            direccion: datosGeolocalizacion.direccion,
            ciudad: datosGeolocalizacion.ciudad,
            pais: datosGeolocalizacion.pais,
            precision: datosGeolocalizacion.precision || null
        };

        return new Promise((resolve, reject) => {
            $.ajax({
                url: `/empleado/registro/${empleadoId}/start`,
                method: 'POST',
                data: datosEnvio,
                timeout: 10000, // 10 segundos máximo
                success: function(response) {
                    console.log('✅ Servidor respondió:', response);
                    
                    Swal.close();
                    
                    if (response.success) {
                        let mensajeUbicacion = `
                            <div class="text-left">
                                <strong>✅ Tiempo Iniciado</strong><br>
                                <small class="text-success">📍 ${datosGeolocalizacion.ciudad}, ${datosGeolocalizacion.pais}</small>
                        `;
                        
                        if (datosGeolocalizacion.precision) {
                            mensajeUbicacion += `<br><small class="text-info">📊 Precisión: ${datosGeolocalizacion.precision}m</small>`;
                        }
                        
                        mensajeUbicacion += `</div>`;

                        Swal.fire({
                            title: '¡Listo!',
                            html: mensajeUbicacion,
                            icon: 'success',
                            timer: 3000,
                            showConfirmButton: false
                        });

                        // Actualizar interfaz inmediatamente
                        btnStart.hide();
                        btnGroupActive.show();
                        estadoActual.text('Estado: Activo');
                        
                        // Recargar datos rápidamente
                        setTimeout(() => {
                            recargarDatosCompletos();
                            checkEstado();
                        }, 500);
                        
                        resolve(response);
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error'
                        });
                        reject(new Error(response.message));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Error servidor:', error);
                    Swal.fire({
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor',
                        icon: 'error'
                    });
                    reject(new Error(error));
                }
            });
        });
    }

    // Función para iniciar SIN geolocalización
    function iniciarSinGeolocalizacion() {
        console.log('⚠️ Iniciando registro SIN geolocalización');
        
        Swal.fire({
            title: 'Iniciando tiempo...',
            text: 'Sin datos de ubicación',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        obtenerUbicacionPorIP()
            .then(ubicacionIP => {
                console.log('✅ Usando ubicación por IP:', ubicacionIP);
                return iniciarRegistroTiempo(ubicacionIP);
            })
            .catch((error) => {
                console.warn('❌ Falló ubicación por IP, usando datos mínimos:', error);
                
                $.ajax({
                    url: `/empleado/registro/${empleadoId}/start`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        latitud: null,
                        longitud: null,
                        direccion: 'Ubicación no disponible - Permiso denegado o GPS desactivado',
                        ciudad: 'Ubicación no registrada',
                        pais: 'Permiso de ubicación denegado'
                    },
                    success: function(response) {
                        Swal.close();
                        
                        if (response.success) {
                            Swal.fire({
                                title: '✅ Tiempo Iniciado',
                                html: `Tiempo registrado correctamente<br>
                                      <small class="text-warning">⚠️ Ubicación no disponible</small>`,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            });

                            btnStart.hide();
                            btnGroupActive.show();
                            estadoActual.text('Estado: Activo');
                            
                            recargarDatosCompletos();
                            checkEstado();
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error de conexión',
                            text: 'No se pudo iniciar el tiempo',
                            icon: 'error'
                        });
                    }
                });
            });
    }

    // Fallback: Geolocalización del navegador
    function usarGeolocalizacionNavegador() {
        console.log('📱 Usando geolocalización del navegador como fallback...');
        
        if (!navigator.geolocation) {
            Swal.fire({
                title: 'Geolocalización no disponible',
                text: 'Tu navegador no soporta geolocalización',
                icon: 'error',
                confirmButtonText: 'Iniciar sin ubicación'
            }).then(() => {
                iniciarSinGeolocalizacion();
            });
            return;
        }

        const opciones = {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
        };

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const latitud = position.coords.latitude;
                const longitud = position.coords.longitude;
                const precision = Math.round(position.coords.accuracy);
                
                console.log('📍 Ubicación navegador obtenida:', { latitud, longitud, precision });
                
                Swal.fire({
                    title: 'Ubicación obtenida!',
                    text: `Precisión: ${precision} metros`,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });

                obtenerDireccionGoogle(latitud, longitud)
                    .then(direccion => {
                        iniciarRegistroTiempo({
                            latitud: latitud,
                            longitud: longitud,
                            direccion: direccion.direccion,
                            ciudad: direccion.ciudad,
                            pais: direccion.pais,
                            precision: precision
                        });
                    })
                    .catch((error) => {
                        console.warn('Error obteniendo dirección:', error);
                        iniciarRegistroTiempo({
                            latitud: latitud,
                            longitud: longitud,
                            direccion: `Ubicación GPS: ${latitud.toFixed(6)}, ${longitud.toFixed(6)}`,
                            ciudad: 'Por coordenadas GPS',
                            pais: 'Ubicación por GPS',
                            precision: precision
                        });
                    });
            },
            (error) => {
                console.error('❌ Error geolocalización navegador:', error);
                
                let mensajeError = obtenerMensajeErrorGeolocalizacion(error);
                
                Swal.fire({
                    title: 'Ubicación no disponible',
                    html: `
                        <p>${mensajeError}</p>
                        <p><strong>¿Deseas iniciar el tiempo sin ubicación?</strong></p>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, iniciar sin ubicación',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        iniciarSinGeolocalizacion();
                    }
                });
            },
            opciones
        );
    }

    // =============================================
    // EVENTOS DE CONTROL DE TIEMPO
    // =============================================

    // Evento START con Google Maps Geolocation
    btnStart.click(function() {
    console.log('=== INICIANDO PROCESO COMPLETO ===');
    
    // Deshabilitar botón inmediatamente
    const originalHtml = btnStart.html();
    btnStart.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> OBTENIENDO UBICACIÓN...');

    // Mostrar loading inmediato
    Swal.fire({
        title: 'Obteniendo ubicación...',
        text: 'Buscando tu ubicación precisa con GPS',
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const inicioProceso = Date.now();
    
    // Proceso optimizado
    obtenerUbicacionGoogleMaps()
        .then(ubicacion => {
            const tiempoGPS = Date.now() - inicioProceso;
            console.log(`📍 GPS listo en ${tiempoGPS}ms`);
            
            // Actualizar mensaje
            Swal.update({
                title: 'Obteniendo dirección...',
                text: 'Consultando datos de ubicación'
            });
            
            return Promise.all([
                ubicacion,
                obtenerDireccionGoogle(ubicacion.latitud, ubicacion.longitud)
            ]);
        })
        .then(([ubicacion, direccionCompleta]) => {
            const tiempoTotal = Date.now() - inicioProceso;
            console.log(`✅ Proceso completo en ${tiempoTotal}ms`);
            
            const datosFinales = {
                latitud: ubicacion.latitud,
                longitud: ubicacion.longitud,
                direccion: direccionCompleta.direccion,
                ciudad: direccionCompleta.ciudad,
                pais: direccionCompleta.pais,
                precision: ubicacion.precision
            };
            
            console.log('🎯 Datos listos para enviar:', datosFinales);
            return iniciarRegistroTiempo(datosFinales);
        })
        .catch(error => {
            const tiempoTotal = Date.now() - inicioProceso;
            console.error(`❌ Error después de ${tiempoTotal}ms:`, error);
            
            Swal.fire({
                title: 'Ubicación no disponible',
                html: `No se pudo obtener tu ubicación completa.<br>
                      <strong>¿Deseas iniciar el tiempo con ubicación básica?</strong>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, iniciar igual',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    iniciarSinGeolocalizacion();
                } else {
                    // Rehabilitar botón
                    btnStart.prop('disabled', false).html(originalHtml);
                }
            });
        });
});

    // Evento PAUSE
    btnPause.click(function() {
        console.log('⏸️ Solicitando pausa...');
        
        // Mostrar loading
        const originalHtml = btnPause.html();
        btnPause.html('<i class="fas fa-spinner fa-spin mr-2"></i> PAUSANDO...');
        
        $.ajax({
            url: `/empleado/registro/${empleadoId}/pause`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            timeout: 10000,
            success: function(response) {
                console.log('✅ Respuesta PAUSE:', response);
                btnPause.html(originalHtml); // Restaurar botón
                
                if (response.success) {
                    // Actualizar estado localmente inmediatamente
                    if (response.estado === 'pausado') {
                        detenerActualizacionTiempoReal();
                        // Mantener el tiempo actual pero mostrar como pausado
                        const tiempoActual = tiempoTranscurridoElement.text().replace('Tiempo: ', '').replace(' (Pausado)', '');
                        tiempoTranscurridoElement.text(`Tiempo: ${tiempoActual} (Pausado)`);
                        estadoActual.text('Estado: Pausado');
                    } else if (response.estado === 'activo') {
                        // Reanudar - obtener el tiempo actual del servidor
                        checkEstado();
                    }
                    
                    // Recargar datos
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
            error: function(xhr, status, error) {
                console.error('❌ Error PAUSE:', error);
                btnPause.html(originalHtml); // Restaurar botón
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo pausar el tiempo'
                });
            }
        });
    });

    // Evento STOP
    btnStop.click(function() {
        console.log('=== STOP - CÁLCULO CON PAUSA MANUAL ===');
        
        $.ajax({
            url: `/empleado/registro/${empleadoId}/estado`,
            method: 'GET',
            success: function(estadoResponse) {
                console.log('Respuesta estado:', estadoResponse);
                
                if (estadoResponse.activo) {
                    const inicio = new Date(estadoResponse.inicio);
                    const fin = new Date();
                    
                    const diferenciaMs = fin - inicio;
                    const segundosBrutos = Math.floor(diferenciaMs / 1000);
                    
                    let segundosPausa = 0;
                    
                    if (estadoResponse.debug && estadoResponse.debug.pausa_inicio_bd) {
                        const pausaInicio = new Date(estadoResponse.debug.pausa_inicio_bd);
                        const pausaFin = estadoResponse.debug.pausa_fin_bd ? 
                            new Date(estadoResponse.debug.pausa_fin_bd) : fin;
                        
                        const pausaMs = pausaFin - pausaInicio;
                        segundosPausa = Math.floor(pausaMs / 1000);
                    } else {
                        segundosPausa = estadoResponse.tiempo_pausa_total || 0;
                    }
                    
                    const segundosNetos = Math.max(0, segundosBrutos - segundosPausa);
                    
                    const tiempoBrutoFormateado = formatTime(segundosBrutos);
                    const pausaFormateada = formatTime(segundosPausa);
                    const tiempoNetoFormateado = formatTime(segundosNetos);
                    
                    console.log('Cálculo final modal:', {
                        segundosBrutos,
                        segundosPausa,
                        segundosNetos,
                        tiempoBrutoFormateado,
                        pausaFormateada,
                        tiempoNetoFormateado
                    });

                    let contenidoModal = `
                        <div class="mb-3">
                            <strong class="h4 text-primary">${tiempoNetoFormateado}</strong>
                        </div>
                        <div class="small text-muted mb-3">
                            <div>🕐 <strong>Inicio:</strong> ${new Date(estadoResponse.inicio).toLocaleTimeString()}</div>
                            <div>🛑 <strong>Fin:</strong> ${fin.toLocaleTimeString()}</div>
                            <div>⏱️ <strong>Duración bruta:</strong> ${Math.floor(segundosBrutos / 60)} minutos ${segundosBrutos % 60} segundos</div>
                        </div>
                        <div class="small">
                            <div>⏱️ <strong>Tiempo bruto:</strong> ${tiempoBrutoFormateado}</div>
                            <div>⏸️ <strong>Tiempo pausa:</strong> ${pausaFormateada}</div>
                            <div>📊 <strong>Fórmula:</strong> (${tiempoBrutoFormateado} bruto) - (${pausaFormateada} pausa) = ${tiempoNetoFormateado} neto</div>
                        </div>
                    `;
                    
                    if (estadoResponse.debug && estadoResponse.debug.pausa_inicio_bd) {
                        const pausaInicioTime = new Date(estadoResponse.debug.pausa_inicio_bd).toLocaleTimeString();
                        const pausaFinTime = estadoResponse.debug.pausa_fin_bd ? 
                            new Date(estadoResponse.debug.pausa_fin_bd).toLocaleTimeString() : 'En pausa';
                        
                        contenidoModal += `
                            <div class="mt-2 small text-info">
                                <i class="fas fa-pause-circle"></i> 
                                Pausa registrada: ${pausaInicioTime} - ${pausaFinTime} (${formatTime(segundosPausa)})
                            </div>
                        `;
                    }
                    
                    $('#tiempo-final').html(contenidoModal);
                    $('#confirmStopModal').modal('show');
                    $('#confirm-stop').data('tiempo-total', segundosNetos);
                    $('#confirm-stop').data('tiempo-formateado', tiempoNetoFormateado);
                    
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
                    btnStart.show();
                    btnGroupActive.hide();
                    estadoActual.text('Estado: No iniciado');
                    tiempoTranscurridoElement.text('Tiempo: 00:00:00');
                    detenerActualizacionTiempoReal();
                    
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

    // =============================================
    // FUNCIONES DE ESTADO Y ACTUALIZACIÓN
    // =============================================


    // Función para mejorar datos de ubicación cuando Google no da buena información
    function mejorarDatosUbicacion(datos) {
        // Si tenemos dirección pero no ciudad/pais, intentar extraerlos
        if (datos.direccion && datos.direccion.includes(',')) {
            const partes = datos.direccion.split(',');
            
            // La última parte usualmente es el país
            if (partes.length > 1) {
                datos.pais = partes[partes.length - 1].trim();
            }
            
            // La penúltima parte usualmente es la ciudad/provincia
            if (partes.length > 2) {
                datos.ciudad = partes[partes.length - 2].trim();
            } else if (partes.length > 1) {
                datos.ciudad = partes[0].trim();
            }
        }
        
        // Si aún no tenemos buena información, usar coordenadas formateadas
        if (datos.ciudad === 'Ciudad desconocida' || datos.ciudad === 'Ubicación GPS') {
            datos.ciudad = `Coordenadas ${datos.latitud.toFixed(4)}`;
        }
        
        if (datos.pais === 'País desconocido' || datos.pais === 'GPS') {
            datos.pais = `${datos.longitud.toFixed(4)}`;
        }
        
        return datos;
    }

    // Función para verificar estado
    function checkEstado() {
        console.log('🔄 Verificando estado del tiempo...');
        
        $.ajax({
            url: `/empleado/registro/${empleadoId}/estado`,
            method: 'GET',
            timeout: 8000, // 8 segundos máximo
            success: function(response) {
                console.log('✅ Estado recibido:', response);
                
                if (response && response.activo !== undefined) {
                    // Respuesta válida del servidor
                    if (response.activo) {
                        btnStart.hide();
                        btnGroupActive.show();
                        estadoActual.text(`Estado: ${response.estado ? response.estado.charAt(0).toUpperCase() + response.estado.slice(1) : 'Activo'}`);
                        
                        // Manejar el contador de tiempo
                        if (response.estado === 'activo') {
                            const tiempoInicial = response.tiempo_transcurrido || 0;
                            console.log(`⏱️ Iniciando contador desde: ${tiempoInicial} segundos`);
                            iniciarActualizacionTiempoReal(tiempoInicial);
                        } else if (response.estado === 'pausado') {
                            detenerActualizacionTiempoReal();
                            // Mostrar el tiempo pausado
                            if (response.tiempo_formateado) {
                                tiempoTranscurridoElement.text(`Tiempo: ${response.tiempo_formateado} (Pausado)`);
                            } else {
                                tiempoTranscurridoElement.text('Tiempo: 00:00:00 (Pausado)');
                            }
                        }
                    } else {
                        // No hay tiempo activo
                        btnStart.show();
                        btnGroupActive.hide();
                        estadoActual.text('Estado: No iniciado');
                        tiempoTranscurridoElement.text('Tiempo: 00:00:00');
                        detenerActualizacionTiempoReal();
                    }
                } else {
                    // Respuesta inválida del servidor
                    console.error('❌ Respuesta inválida del servidor:', response);
                    manejarErrorEstado('Respuesta inválida del servidor');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error al verificar estado:', error);
                console.log('Status:', status);
                console.log('XHR:', xhr);
                
                if (status === 'timeout') {
                    manejarErrorEstado('Timeout al verificar estado');
                } else if (xhr.status === 404) {
                    manejarErrorEstado('Endpoint no encontrado');
                } else if (xhr.status === 500) {
                    manejarErrorEstado('Error interno del servidor');
                } else {
                    manejarErrorEstado('Error de conexión: ' + error);
                }
            }
        });
    }

    // Función para manejar errores de estado
    function manejarErrorEstado(mensaje) {
        console.error('🚨 Error de estado:', mensaje);
        
        // Mostrar estado de error pero mantener la interfaz actual
        estadoActual.text('Estado: Error de conexión');
        tiempoTranscurridoElement.text('Tiempo: --:--:--');
        
        // No cambiar los botones para no perder el estado actual
        // Solo mostrar notificación si es necesario
        if (!btnStart.is(':visible')) {
            // Si estaba activo, mostrar advertencia
            Swal.fire({
                title: 'Error de conexión',
                text: 'No se puede verificar el estado actual del tiempo',
                icon: 'warning',
                timer: 3000,
                showConfirmButton: false
            });
        }
    }

    // Función para actualización en tiempo real
    function iniciarActualizacionTiempoReal(tiempoInicial) {
        console.log('▶️ Iniciando actualización en tiempo real. Tiempo inicial:', tiempoInicial);
        
        // Detener cualquier intervalo anterior
        detenerActualizacionTiempoReal();
        
        let segundosTranscurridos = Math.max(0, parseInt(tiempoInicial) || 0);
        
        // Actualizar inmediatamente
        actualizarDisplayTiempo(segundosTranscurridos);
        
        // Iniciar intervalo
        intervaloActualizacion = setInterval(function() {
            segundosTranscurridos++;
            actualizarDisplayTiempo(segundosTranscurridos);
        }, 1000);
        
        console.log('✅ Contador en tiempo real iniciado');
    }


    // Función auxiliar para actualizar el display
    function actualizarDisplayTiempo(segundos) {
        const horas = Math.floor(segundos / 3600);
        const minutos = Math.floor((segundos % 3600) / 60);
        const segs = segundos % 60;
        
        const tiempoFormateado = `${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}:${segs.toString().padStart(2, '0')}`;
        tiempoTranscurridoElement.text(`Tiempo: ${tiempoFormateado}`);
        
        // Debug cada 30 segundos
        if (segundos % 30 === 0) {
            console.log(`⏱️ Contador activo: ${tiempoFormateado} (${segundos} segundos)`);
        }
    }

    function detenerActualizacionTiempoReal() {
        if (intervaloActualizacion) {
            console.log('⏹️ Deteniendo contador en tiempo real');
            clearInterval(intervaloActualizacion);
            intervaloActualizacion = null;
        }
    }

    // Función para manejar estado pausado
    function manejarEstadoPausado(tiempoFormateado) {
        console.log('⏸️ Cambiando a estado pausado');
        detenerActualizacionTiempoReal();
        
        if (tiempoFormateado) {
            tiempoTranscurridoElement.text(`Tiempo: ${tiempoFormateado} (Pausado)`);
        } else {
            // Mantener el tiempo actual y agregar (Pausado)
            const tiempoActual = tiempoTranscurridoElement.text()
                .replace('Tiempo: ', '')
                .replace(' (Pausado)', '');
            tiempoTranscurridoElement.text(`Tiempo: ${tiempoActual} (Pausado)`);
        }
        
        estadoActual.text('Estado: Pausado');
        btnPause.html('<i class="fas fa-play mr-2"></i>REANUDAR');
    }

    // =============================================
    // FUNCIONES UTILITARIAS
    // =============================================

    // Función para formatear tiempo
    function formatTime(seconds) {
        seconds = Math.max(0, parseInt(seconds));
        
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        
        if (hours > 0) {
            return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        } else {
            return `${minutes}:${secs.toString().padStart(2, '0')}`;
        }
    }

    // Función para recargar datos completos
    function recargarDatosCompletos() {
        // Recargar DataTable de manera forzada
        if (dataTable && typeof dataTable.ajax !== 'undefined') {
            dataTable.ajax.reload();
        }
        
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
                $('.stats-number').first().text(response.total_registros || '0');
                $('.stats-number').last().text((response.total_horas || '0.00') + 'h');
                $('.text-muted small').text('Promedio diario: ' + (response.promedio_horas || '0.00') + 'h');
            },
            error: function(xhr) {
                console.error('Error al actualizar estadísticas:', xhr);
            }
        });
    }

    // =============================================
    // INICIALIZACIÓN
    // =============================================

    // Verificar estado al cargar la página
    initializeDataTable();

    checkEstado();

    // ... (el resto de tu código para DataTable, filtros, etc. permanece igual)
    // Solo asegúrate de que las funciones estén definidas antes de ser usadas

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

/* Badge para indicar ubicación registrada */
.badge-location {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
}


</style>
@endsection