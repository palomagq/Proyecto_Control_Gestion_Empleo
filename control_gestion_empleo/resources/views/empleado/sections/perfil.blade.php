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
                    
                    <h5 class="mb-1">{{ substr(auth()->user()->name, 0, 1) }}</h5>
                    <p class="text-muted mb-3">{{ substr(auth()->user()->email, 0, 1) }}</p>
                    
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
                        <small class="text-muted">Promedio diario: {{ $estadisticasMes['promedio_horas_formateado'] }}</small>
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

             <!-- Tarjeta de Progreso Semanal -->
            <div class="card glass-effect animated-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line mr-2"></i>Progreso Semanal</h5>
                    <small class="">
                        {{ Carbon\Carbon::now()->startOfWeek()->format('d M') }} - 
                        {{ Carbon\Carbon::now()->endOfWeek()->format('d M Y') }}
                    </small>
                </div>
                <div class="card-body">
                    @if(array_sum(array_column($progresoSemanal, 'total_segundos')) > 0)
                    <div class="progress-container">
                        @foreach($progresoSemanal as $dia)
                        <div class="progress-item mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small font-weight-bold">{{ $dia['nombre'] }}</span>
                                <div class="text-right">
                                    <span class="small text-muted">{{ $dia['horas'] }}h</span>
                                    @if($dia['registros'] > 0)
                                    <br><small class="text-info">{{ $dia['registros'] }} reg.</small>
                                    @endif
                                </div>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $dia['color'] }}" 
                                    style="width: {{ $dia['porcentaje'] }}%"
                                    title="{{ $dia['nombre'] }}: {{ $dia['horas'] }} horas ({{ number_format($dia['porcentaje'], 0) }}%)">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Resumen semanal -->
                    @php
                        $totalHorasSemana = array_sum(array_column($progresoSemanal, 'horas'));
                        $diasConRegistros = count(array_filter($progresoSemanal, function($dia) {
                            return $dia['registros'] > 0;
                        }));
                        $promedioDiario = $diasConRegistros > 0 ? $totalHorasSemana / $diasConRegistros : 0;
                    @endphp
                    <div class="mt-3 pt-3 border-top">
                        <div class="row text-center">
                            <div class="col-4">
                                <small class="text-muted d-block">Total</small>
                                <strong class="text-primary">{{ number_format($totalHorasSemana, 1) }}h</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Promedio</small>
                                <strong class="text-info">{{ number_format($promedioDiario, 1) }}h</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Días</small>
                                <strong class="text-success">{{ $diasConRegistros }}/7</strong>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Sin registros esta semana</h6>
                        <small class="text-muted">Cuando trabajes esta semana, verás tu progreso aquí.</small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tarjeta de Logros -->
            <div class="card border-warning animated-card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-trophy mr-2"></i>Logros</h5>
                </div>
                <div class="card-body">
                    <div class="achievements-grid">
                        @foreach($logros as $logro)
                        <div class="achievement-item text-center mb-3">
                            <div class="achievement-icon text-{{ $logro['color'] }} mb-2">
                                <i class="fas fa-{{ $logro['icono'] }} fa-2x"></i>
                                @if($logro['completado'] ?? false)
                                <div class="badge badge-success badge-completed">✓</div>
                                @endif
                            </div>
                            <small class="text-dark">{{ $logro['texto'] }}</small>
                            
                            @if(isset($logro['progreso']))
                            <div class="progress mt-1" style="height: 4px;">
                                <div class="progress-bar bg-{{ $logro['color'] }}" style="width: {{ $logro['progreso'] }}%"></div>
                            </div>
                            @endif
                        </div>
                        @endforeach
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

<!-- Modal de Detalles del Registro -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailsModalLabel">
                    <i class="fas fa-clock mr-2"></i>Detalles Completos del Registro
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal-loading" class="text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                    <p class="text-muted">Cargando detalles del registro...</p>
                </div>
                
                <div id="modal-content" style="display: none;">
                    <!-- El contenido se cargará aquí dinámicamente -->
                </div>
                
                <div id="modal-error" class="text-center py-4" style="display: none;">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>Error al cargar detalles</h5>
                    <p class="text-muted" id="error-message">No se pudieron cargar los detalles del registro.</p>
                    <button class="btn btn-secondary mt-2" data-dismiss="modal">Cerrar</button>
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
            //$('#historial-table').empty();
        }
        
        dataTable = $('#historial-table').DataTable({
            serverSide: true,
            processing: true,
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50], // ✅ SIN ARRAYS ANIDADOS
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
            // DEBUG: Verificar parámetros de paginación
                    console.log('🔍 Parámetros DataTable:', {
                        start: d.start,
                        length: d.length,
                        pageLength: d.length,
                        draw: d.draw,
                        month: d.month,
                        year: d.year
                    });
                    
                    return d;                
                },
                dataSrc: function (json) {
                    console.log('📥 Datos recibidos DataTable:', json);
                    // Verificar que el servidor esté respetando la paginación
                    if (json && json.data) {
                        console.log(`📊 Mostrando ${json.data.length} registros de ${json.recordsTotal} totales`);
                    }
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
                        
                        let tiempoPausa = Math.max(0, parseInt(data || 0));
                        
                        if (tiempoPausa === 0 && row.pausa_inicio && row.pausa_fin) {
                            const inicio = new Date(row.pausa_inicio);
                            const fin = new Date(row.pausa_fin);
                            const diferenciaMs = fin - inicio;
                            tiempoPausa = Math.max(0, Math.floor(diferenciaMs / 1000));
                        }
                        
                        if (tiempoPausa === 0) {
                            if (row.pausa_inicio || row.pausa_fin) {
                                return '<span class="text-warning" title="Hubo pausas pero tiempo calculado es 0">00:00</span>';
                            }
                            return '<span class="text-muted">Sin pausas</span>';
                        }
                        
                        return `<span class="text-info font-weight-bold">${formatTimeForTable(tiempoPausa)}</span>`;
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
                         return formatTimeWithLabels(tiempoPositivo);
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
            responsive: true,
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
            console.log('Respuesta resumen:', response);
            
            // Formatear horas totales de "1.18h" a "1h 11m"
            const totalHorasFormateadas = formatTotalHoursWithDays(response.total_horas);
            
            // Formatear promedio diario
            const promedioFormateado = formatDecimalHoursToHM(response.promedio_diario);
            
           // Usar las versiones formateadas del backend
            $('#total-horas-periodo').html(response.total_horas_formateado);
            $('#total-registros-periodo').text(response.total_registros);
            $('#promedio-diario-periodo').html(response.promedio_diario_formateado);
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


    // Función mejorada para ver detalles del registro
    window.viewDetails = function(registroId) {
        console.log('🔍 Cargando detalles del registro:', registroId);
        
        // Resetear modal
        $('#modal-loading').show();
        $('#modal-content').hide();
        $('#modal-error').hide();
        
        // Mostrar modal inmediatamente
        $('#detailsModal').modal('show');
        
        // Obtener datos del registro via AJAX
        $.ajax({
            url: `/empleado/registro/${empleadoId}/detalles/${registroId}`,
            method: 'GET',
            timeout: 10000,
            success: function(response) {
                console.log('✅ Respuesta detalles:', response);
                
                if (response.success && response.registro) {
                    mostrarDetallesCompletos(response.registro, response.estadisticasDia);
                } else {
                    mostrarErrorModal(response.message || 'No se pudieron cargar los detalles del registro.');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error al cargar detalles:', error);
                
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
                
                mostrarErrorModal(mensajeError);
            }
        });
    };


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
    
    // Proceso optimizado con manejo de errores mejorado
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
            
            // IMPORTANTE: Siempre restaurar el botón en caso de error
            btnStart.prop('disabled', false).html(originalHtml);
            
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
                    // Ya restauramos el botón arriba, pero por si acaso
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
    btnPause.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> PROCESANDO...');
    
    $.ajax({
        url: `/empleado/registro/${empleadoId}/pause`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        timeout: 10000,
        success: function(response) {
            console.log('✅ Respuesta PAUSE:', response);
            btnPause.prop('disabled', false).html(originalHtml);
            
            if (response.success) {
                // Actualizar estado localmente inmediatamente
                if (response.estado === 'pausado') {
                    detenerActualizacionTiempoReal();
                    // Mantener el tiempo actual pero mostrar como pausado
                    const tiempoActual = tiempoTranscurridoElement.text().replace('Tiempo: ', '').replace(' (Pausado)', '');
                    tiempoTranscurridoElement.text(`Tiempo: ${tiempoActual} (Pausado)`);
                    estadoActual.text('Estado: Pausado');
                    btnPause.html('<i class="fas fa-play mr-2"></i>REANUDAR');
                } else if (response.estado === 'activo') {
                    // Reanudar - obtener el tiempo actual del servidor
                    checkEstado();
                    btnPause.html('<i class="fas fa-pause mr-2"></i>PAUSAR');
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
            console.error('❌ Error completo PAUSE:', {
                status: status,
                error: error,
                xhr: xhr,
                responseText: xhr.responseText
            });
            
            btnPause.prop('disabled', false).html(originalHtml);
            
            let mensajeError = 'Error desconocido';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                mensajeError = xhr.responseJSON.message;
            } else if (xhr.status === 500) {
                mensajeError = 'Error interno del servidor';
            } else if (xhr.status === 404) {
                mensajeError = 'Endpoint no encontrado';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error al pausar',
                html: `
                    <div class="text-left">
                        <strong>No se pudo procesar la pausa</strong><br>
                        <small>Error: ${mensajeError}</small><br>
                        <small>Status: ${xhr.status}</small>
                    </div>
                `,
                confirmButtonText: 'Reintentar'
            });
        }
    });
});

    // Evento STOP
    btnStop.click(function() {
    console.log('🛑 Solicitando detención...');
    
    // Mostrar loading inmediato
    Swal.fire({
        title: 'Calculando tiempo...',
        text: 'Preparando para detener el registro',
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: `/empleado/registro/${empleadoId}/estado`,
        method: 'GET',
        success: function(estadoResponse) {
            console.log('✅ Estado recibido para STOP:', estadoResponse);
            
            if (estadoResponse.activo) {
                Swal.close();
                
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
                const tiempoNetoFormateado = formatTime(segundosNetos);
                const tiempoConEtiquetas = formatTimeWithLabels(segundosNetos);
                
                console.log('Cálculo final modal:', {
                    segundosBrutos,
                    segundosPausa,
                    segundosNetos,
                    tiempoNetoFormateado,
                    tiempoConEtiquetas
                });

                let contenidoModal = `
                    <div class="mb-3">
                        <strong class="h4 text-primary">${tiempoConEtiquetas}</strong>
                        <div class="small text-muted">${formatTime(segundosNetos)}</div>
                    </div>
                    <div class="small text-muted mb-3">
                        <div>🕐 <strong>Inicio:</strong> ${new Date(estadoResponse.inicio).toLocaleTimeString()}</div>
                        <div>🛑 <strong>Fin:</strong> ${fin.toLocaleTimeString()}</div>
                    </div>
                    <div class="small">
                        <div>⏱️ <strong>Tiempo bruto:</strong> ${formatTime(segundosBrutos)}</div>
                        <div>⏸️ <strong>Tiempo pausa:</strong> ${formatTime(segundosPausa)}</div>
                    </div>
                `;
                
                $('#tiempo-final').html(contenidoModal);
                $('#confirmStopModal').modal('show');
                $('#confirm-stop').data('tiempo-total', segundosNetos);
                $('#confirm-stop').data('tiempo-formateado', tiempoNetoFormateado);
                
            } else {
                Swal.close();
                Swal.fire({
                    icon: 'warning',
                    title: 'No hay tiempo activo',
                    text: 'No hay un registro de tiempo activo para detener.'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener estado para STOP:', error);
            Swal.close();
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

    console.log('🛑 Enviando STOP al servidor...', { tiempoTotal, empleadoId });

    $.ajax({
        url: `/empleado/registro/${empleadoId}/stop`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            tiempo_total: tiempoTotal
        },
        timeout: 15000,
        success: function(response) {
            console.log('✅ Respuesta STOP:', response);
            
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
                    text: response.message || 'Error al detener el tiempo'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error completo STOP:', {
                status: status,
                error: error,
                xhr: xhr,
                responseText: xhr.responseText
            });
            
            let mensajeError = 'Error desconocido';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                mensajeError = xhr.responseJSON.message;
            } else if (xhr.status === 500) {
                mensajeError = 'Error interno del servidor';
            } else if (xhr.status === 404) {
                mensajeError = 'Endpoint no encontrado';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error al detener',
                html: `
                    <div class="text-left">
                        <strong>No se pudo detener el tiempo</strong><br>
                        <small>Error: ${mensajeError}</small><br>
                        <small>Status: ${xhr.status}</small>
                    </div>
                `,
                confirmButtonText: 'Reintentar'
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
        const tiempoFormateado = formatTime(segundos);
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

// Función para actualizar progreso semanal
function actualizarProgresoSemanal() {
    console.log('📊 Actualizando progreso semanal...');
    $.ajax({
        url: `/empleado/registro/${empleadoId}/progreso-semanal`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                console.log('✅ Progreso semanal actualizado:', response);
                // Aquí podrías actualizar la UI dinámicamente si quieres
                // Por ahora, recargamos la página para ver cambios inmediatos
                // location.reload(); // Descomenta si quieres recarga completa
            }
        },
        error: function(xhr) {
            console.error('❌ Error al actualizar progreso semanal:', xhr);
        }
    });
}

// Actualizar cada 5 minutos
setInterval(actualizarProgresoSemanal, 300000);

    // =============================================
    // FUNCIONES UTILITARIAS
    // =============================================

// Función para formatear tiempo (DÍAS, HORAS Y MINUTOS) - MEJORADA
function formatTime(seconds) {
    seconds = Math.max(0, parseInt(seconds));
    
    if (seconds === 0) return '00:00';
    
    // Calcular horas, minutos y segundos
    const horas = Math.floor(seconds / 3600);
    const minutosRestantes = seconds % 3600;
    const minutos = Math.floor(minutosRestantes / 60);
    const segundosRestantes = minutosRestantes % 60;
    
    // Si hay horas, mostrar formato completo HH:MM:SS
    if (horas > 0) {
        return `${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}:${segundosRestantes.toString().padStart(2, '0')}`;
    }
    
    // Si solo hay minutos y segundos, mostrar MM:SS
    return `${minutos.toString().padStart(2, '0')}:${segundosRestantes.toString().padStart(2, '0')}`;
}

    // Función para recargar datos completos
    function recargarDatosCompletos() {
    console.log('🔄 Recargando datos completos...');
    
    // Recargar DataTable de manera forzada
    if (dataTable && $.fn.DataTable.isDataTable('#historial-table')) {
        dataTable.ajax.reload(null, false); // false = mantener página actual
    } else {
        console.warn('DataTable no está inicializado');
    }
    
    // Actualizar resumen
    updatePeriodSummary();
    
    // Actualizar estadísticas del perfil
    actualizarEstadisticasPerfil();
    
    // ACTUALIZAR PROGRESO SEMANAL - NUEVO
    actualizarProgresoSemanal();
    
    // Verificar estado del tiempo
    setTimeout(() => {
        checkEstado();
    }, 1000);
}

    // Función para actualizar estadísticas del perfil
    function actualizarEstadisticasPerfil() {
        $.ajax({
            url: `/empleado/registro/${empleadoId}/estadisticas-mes`,
            method: 'GET',
            success: function(response) {
                console.log('Estadísticas perfil:', response);
                
                // Actualizar total de registros
                $('.stats-number').first().text(response.total_registros || '0');
                
                // Formatear horas totales de "1.18h" a "1h 11m"
                const horasFormateadas = formatDecimalHoursToHM(response.total_horas);
                $('.stats-number').last().html(horasFormateadas);
                
                // Formatear promedio diario
                const promedioFormateado = formatDecimalHoursToHM(response.promedio_horas);
                $('.text-muted small').html('Promedio diario: ' + promedioFormateado);
            },
            error: function(xhr) {
                console.error('Error al actualizar estadísticas:', xhr);
            }
        });
    }


    // Función para mostrar error en el modal
function mostrarErrorModal(mensaje) {
    $('#modal-loading').hide();
    $('#error-message').text(mensaje);
    $('#modal-error').show();
}

// Función para formatear tiempo en formato tabla (HH:MM)
    function formatTimeForTable(seconds) {
        seconds = Math.max(0, parseInt(seconds));
        
        if (seconds === 0) return '00:00';
        
        const horas = Math.floor(seconds / 3600);
        const minutos = Math.floor((seconds % 3600) / 60);
        
        if (horas > 0) {
            return `${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}`;
        }
        
        return `${minutos.toString().padStart(2, '0')}:00`;
    }

// Función para mostrar tiempo con etiquetas (días, horas, minutos) - MEJORADA
function formatTimeWithLabels(seconds) {
    seconds = Math.max(0, parseInt(seconds));
    
    // Calcular días, horas y minutos
    const dias = Math.floor(seconds / 86400); // 86400 segundos en un día
    const horasRestantes = seconds % 86400;
    const horas = Math.floor(horasRestantes / 3600);
    const minutos = Math.floor((horasRestantes % 3600) / 60);
    
    let resultado = '';
    
    // Si hay días, mostrarlos
    if (dias > 0) {
        resultado += `${dias} día${dias !== 1 ? 's' : ''} `;
    }
    
    // Si hay horas, mostrarlas
    if (horas > 0) {
        resultado += `${horas} hora${horas !== 1 ? 's' : ''} `;
    }
    
    // Siempre mostrar minutos (aunque sean 0)
    resultado += `${minutos} minuto${minutos !== 1 ? 's' : ''}`;
    
    return resultado.trim() || '0 minutos';
}

// Función para formatear horas decimales a horas:minutos
function formatDecimalHours(decimalHours) {
    if (!decimalHours || decimalHours === 0) return '0:00';
    
    const horas = Math.floor(decimalHours);
    const minutos = Math.round((decimalHours - horas) * 60);
    
    return `${horas}:${minutos.toString().padStart(2, '0')}`;
}

// Función para formatear horas totales con días si es necesario
function formatTotalHours(decimalHours) {
    if (!decimalHours || decimalHours === 0) return '0:00';
    
    if (decimalHours >= 24) {
        const dias = Math.floor(decimalHours / 24);
        const horasRestantes = decimalHours % 24;
        const horas = Math.floor(horasRestantes);
        const minutos = Math.round((horasRestantes - horas) * 60);
        
        return `${dias}d ${horas}:${minutos.toString().padStart(2, '0')}`;
    } else {
        const horas = Math.floor(decimalHours);
        const minutos = Math.round((decimalHours - horas) * 60);
        
        return `${horas}:${minutos.toString().padStart(2, '0')}`;
    }
}

// Función específica para convertir formato decimal "1.18h" a "1h 11m"
function formatDecimalHoursToHM(decimalHoursStr) {
    // Extraer el número decimal del string (quitando la 'h')
    const decimalHours = safeParseFloat(decimalHoursStr);
    
    if (decimalHours === 0) return '0h 00m';
    
    const horas = Math.floor(decimalHours);
    const minutosDecimal = (decimalHours - horas) * 60;
    const minutos = Math.round(minutosDecimal);
    
    // Si los minutos son 60, sumar una hora
    if (minutos === 60) {
        return `${horas + 1}h 00m`;
    }
    
    return `${horas}h ${minutos.toString().padStart(2, '0')}m`;
}

// Función para formatear horas totales con días si es necesario - MEJORADA
function formatTotalHoursWithDays(decimalHoursStr) {
    const decimalHours = safeParseFloat(decimalHoursStr);
    
    if (decimalHours === 0) return '0h 00m';
    
    // Si supera las 24 horas, convertir a días
    if (decimalHours >= 24) {
        const dias = Math.floor(decimalHours / 24);
        const horasRestantes = decimalHours % 24;
        const horas = Math.floor(horasRestantes);
        const minutosDecimal = (horasRestantes - horas) * 60;
        const minutos = Math.round(minutosDecimal);
        
        if (minutos === 60) {
            return `${dias}d ${horas + 1}h 00m`;
        }
        
        return `${dias}d ${horas}h ${minutos.toString().padStart(2, '0')}m`;
    } else {
        return formatDecimalHoursToHM(decimalHoursStr);
    }
}

// Función mejorada para parsear números decimales de formato "1.18h"
function safeParseFloat(value) {
    if (typeof value === 'number') return value;
    if (typeof value === 'string') {
        // Remover 'h' y cualquier caracter no numérico excepto punto decimal
        const cleaned = value.replace(/[^\d.,]/g, '').replace(',', '.');
        const parsed = parseFloat(cleaned);
        return isNaN(parsed) ? 0 : parsed;
    }
    return 0;
}

// Función para mostrar detalles completos en el modal - CORREGIDA
function mostrarDetallesCompletos(registro, estadisticasDia) {
    console.log('📊 Mostrando detalles completos:', registro);
    
    // Formatear fechas y tiempos
    const fechaCompleta = registro.created_at ? new Date(registro.created_at).toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }) : '-';
    
    const fechaCorta = registro.created_at ? new Date(registro.created_at).toLocaleDateString('es-ES') : '-';
    const inicio = registro.inicio ? new Date(registro.inicio).toLocaleTimeString('es-ES') : '-';
    const fin = registro.fin ? new Date(registro.fin).toLocaleTimeString('es-ES') : 'En progreso';
    const pausaInicio = registro.pausa_inicio ? new Date(registro.pausa_inicio).toLocaleTimeString('es-ES') : 'No hubo pausas';
    const pausaFin = registro.pausa_fin ? new Date(registro.pausa_fin).toLocaleTimeString('es-ES') : (registro.pausa_inicio ? 'Pausa activa' : 'No hubo pausas');
    
    // CORREGIDO: Usar formatDecimalHoursToHM para todas las horas
    const totalHorasDia = formatDecimalHoursToHM(estadisticasDia.total_horas_dia);
    const promedioPorRegistro = formatDecimalHoursToHM(estadisticasDia.promedio_por_registro);

    // CORREGIDO: Calcular duración en formato x h x m
    const tiempoTotalSegundos = registro.tiempo_total || 0;
    const tiempoTotalFormateado = formatTimeWithLabels(tiempoTotalSegundos);
    
    const tiempoPausaSegundos = registro.tiempo_pausa_total || 0;
    const tiempoPausaFormateado = formatTimeWithLabels(tiempoPausaSegundos);
    
    // Calcular tiempo activo (tiempo total - tiempo pausa)
    const tiempoActivoSegundos = Math.max(0, tiempoTotalSegundos - tiempoPausaSegundos);
    const tiempoActivoFormateado = formatTimeWithLabels(tiempoActivoSegundos);
    
    // Calcular eficiencia
    let eficiencia = '-';
    let eficienciaColor = 'text-muted';
    if (tiempoTotalSegundos > 0 && tiempoPausaSegundos > 0) {
        const porcentaje = ((tiempoActivoSegundos / tiempoTotalSegundos) * 100).toFixed(1);
        eficiencia = `${porcentaje}%`;
        
        if (porcentaje >= 90) {
            eficienciaColor = 'text-success';
            eficiencia += ' ⭐ Excelente';
        } else if (porcentaje >= 70) {
            eficienciaColor = 'text-warning';
            eficiencia += ' 👍 Bueno';
        } else {
            eficienciaColor = 'text-danger';
            eficiencia += ' 👎 Bajo';
        }
    } else if (tiempoTotalSegundos > 0) {
        eficiencia = '100% ⭐ Excelente';
        eficienciaColor = 'text-success';
    }
    
    // Estado con colores e iconos
    let estadoBadge = '';
    let estadoIcon = '';
    switch(registro.estado) {
        case 'activo':
            estadoBadge = 'badge-success';
            estadoIcon = '🔴';
            break;
        case 'pausado':
            estadoBadge = 'badge-warning';
            estadoIcon = '⏸️';
            break;
        case 'completado':
            estadoBadge = 'badge-primary';
            estadoIcon = '✅';
            break;
        default:
            estadoBadge = 'badge-secondary';
            estadoIcon = '❓';
    }
    
    // Construir el contenido HTML completo
    const contenidoHTML = `
        <div class="row">
            <!-- Información Principal -->
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Información del Registro</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="font-weight-bold" style="width: 40%">ID Registro:</td>
                                <td>#${registro.id}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Fecha:</td>
                                <td>${fechaCompleta}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Estado:</td>
                                <td><span class="badge ${estadoBadge}">${estadoIcon} ${registro.estado ? registro.estado.charAt(0).toUpperCase() + registro.estado.slice(1) : 'Desconocido'}</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Duración Total:</td>
                                <!-- CORREGIDO: Usar formato x h x m -->
                                <td><span class="font-weight-bold text-primary">${tiempoTotalFormateado}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Línea de Tiempo -->
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-history mr-2"></i>Línea de Tiempo</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="font-weight-bold" style="width: 40%">Inicio:</td>
                                <td>${inicio}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Fin:</td>
                                <td>${fin}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Tiempo Activo:</td>
                                <!-- CORREGIDO: Usar formato x h x m -->
                                <td><span class="font-weight-bold text-success">${tiempoActivoFormateado}</span></td>
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
                    <div class="card-header bg-warning text-white">
                        <h6 class="mb-0"><i class="fas fa-pause-circle mr-2"></i>Información de Pausas</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="font-weight-bold" style="width: 50%">Pausa Inicio:</td>
                                        <td>${pausaInicio}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Pausa Fin:</td>
                                        <td>${pausaFin}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="font-weight-bold" style="width: 50%">Tiempo en Pausa:</td>
                                        <!-- CORREGIDO: Usar formato x h x m -->
                                        <td><span class="text-info font-weight-bold">${tiempoPausaFormateado}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Eficiencia:</td>
                                        <td><span class="${eficienciaColor} font-weight-bold">${eficiencia}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        ${registro.latitud && registro.longitud ? `
        <!-- Información de Geolocalización -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-map-marker-alt mr-2"></i>Información de Ubicación</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="font-weight-bold" style="width: 40%">Dirección:</td>
                                        <td>${registro.direccion || 'No disponible'}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Ciudad:</td>
                                        <td>${registro.ciudad || 'No disponible'}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">País:</td>
                                        <td>${registro.pais || 'No disponible'}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="font-weight-bold" style="width: 40%">Coordenadas:</td>
                                        <td><small class="text-muted">${registro.latitud || 'N/A'}, ${registro.longitud || 'N/A'}</small></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Precisión:</td>
                                        <td><small class="text-muted">${registro.precision_gps ? registro.precision_gps + ' metros' : 'N/A'}</small></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Dispositivo:</td>
                                        <td><small class="text-muted">${registro.dispositivo || 'No registrado'}</small></td>
                                    </tr>
                                </table>
                                ${registro.latitud && registro.longitud ? `
                                <div class="mt-2 text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="verEnMapa(${registro.latitud}, ${registro.longitud})">
                                        <i class="fas fa-map mr-1"></i>Ver en Google Maps
                                    </button>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ` : ''}

        <!-- Estadísticas del Día -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="fas fa-chart-bar mr-2"></i>Estadísticas del Día ${fechaCorta}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="stat-item">
                                    <div class="stat-number text-primary">${estadisticasDia ? totalHorasDia : '0h 00m'}</div>
                                    <div class="stat-label small">Total del Día</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-item">
                                    <div class="stat-number text-success">${estadisticasDia ? estadisticasDia.total_registros_dia : '0'}</div>
                                    <div class="stat-label small">Registros del Día</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-item">
                                    <div class="stat-number text-info">${estadisticasDia ? promedioPorRegistro : '0h 00m'}</div>
                                    <div class="stat-label small">Promedio por Registro</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-item">
                                    <!-- CORREGIDO: Usar formato x h x m -->
                                    <div class="stat-number text-warning">${tiempoTotalFormateado}</div>
                                    <div class="stat-label small">Duración Este Registro</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Actualizar el modal
    $('#modal-content').html(contenidoHTML);
    $('#modal-loading').hide();
    $('#modal-content').show();
    
    // Actualizar título del modal con ID del registro
    $('#detailsModalLabel').html(`<i class="fas fa-clock mr-2"></i>Detalles del Registro #${registro.id}`);
}

// Función para abrir Google Maps
/*function verEnMapa(latitud, longitud) {
    const url = `https://www.google.com/maps?q=${latitud},${longitud}`;
    window.open(url, '_blank');
}*/

    // =============================================
    // INICIALIZACIÓN
    // =============================================

    // Verificar estado al cargar la página
    initializeDataTable();



 // Formatear valores iniciales del perfil (desde Blade)
    const totalHorasInicial = '{{ $estadisticasMes["total_horas"] ?? "0" }}';
    const promedioInicial = '{{ $estadisticasMes["promedio_horas"] ?? "0" }}';

    $('.stats-number').first().text('{{ $estadisticasMes["total_registros"] ?? 0 }}');
    $('.stats-number').last().html(formatDecimalHoursToHM(totalHorasInicial));
    $('.text-muted small').html('Promedio diario: ' + formatDecimalHoursToHM(promedioInicial));

    // También formatear el resumen inicial
    setTimeout(() => {
        updatePeriodSummary();
    }, 100);

    // =============================================
    // VERIFICAR ESTADO Y CONTINUAR
    // =============================================

    checkEstado();
});


// Función para abrir Google Maps con las coordenadas
function verEnMapa(latitud, longitud) {
    console.log('🗺️ Abriendo Google Maps:', { latitud, longitud });
    
    // Validar que las coordenadas sean números válidos
    if (typeof latitud !== 'number' || typeof longitud !== 'number' || 
        isNaN(latitud) || isNaN(longitud)) {
        console.error('❌ Coordenadas inválidas:', { latitud, longitud });
        Swal.fire({
            icon: 'error',
            title: 'Coordenadas inválidas',
            text: 'No se pueden abrir las coordenadas en el mapa'
        });
        return;
    }
    
    // Formatear la URL de Google Maps
    const url = `https://www.google.com/maps?q=${latitud},${longitud}&z=15`;
    
    // Abrir en nueva pestaña
    window.open(url, '_blank', 'noopener,noreferrer');
    
    // Opcional: Mostrar confirmación
    Swal.fire({
        icon: 'success',
        title: 'Google Maps abierto',
        text: 'Se ha abierto Google Maps en una nueva pestaña',
        timer: 2000,
        showConfirmButton: false
    });
}


// Función para imprimir detalles del registro
function imprimirDetalles() {
    console.log('🖨️ Iniciando impresión de detalles...');
    
    // Obtener el contenido del modal
    const modalContent = $('#modal-content').html();
    const registroId = $('#detailsModalLabel').text().match(/#(\d+)/)?.[1] || 'Desconocido';
    
    // Crear ventana de impresión
    const ventanaImpresion = window.open('', '_blank', 'width=800,height=600');
    
    if (!ventanaImpresion) {
        Swal.fire({
            icon: 'error',
            title: 'Popup bloqueado',
            text: 'Por favor permite popups para imprimir los detalles'
        });
        return;
    }
    
    const contenido = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Detalles del Registro #${registroId} - Sistema de Control de Tiempos</title>
            <meta charset="UTF-8">
            <style>
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                    margin: 20px; 
                    font-size: 14px; 
                    line-height: 1.4;
                    color: #333;
                }
                .header { 
                    text-align: center; 
                    border-bottom: 3px solid #4361ee; 
                    padding-bottom: 15px; 
                    margin-bottom: 25px; 
                }
                .header h1 { 
                    color: #4361ee; 
                    margin-bottom: 5px; 
                    font-size: 24px;
                }
                .header p { 
                    color: #666; 
                    margin: 0; 
                }
                .section { 
                    margin-bottom: 20px; 
                    border: 1px solid #ddd; 
                    padding: 15px; 
                    border-radius: 8px; 
                    page-break-inside: avoid;
                }
                .section-title { 
                    background: linear-gradient(135deg, #f8f9fa, #e9ecef); 
                    padding: 10px 15px; 
                    font-weight: bold; 
                    border-left: 4px solid #4361ee; 
                    margin: -15px -15px 15px -15px; 
                    color: #4361ee;
                    font-size: 16px;
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                }
                td { 
                    padding: 8px 5px; 
                    border-bottom: 1px solid #eee; 
                    vertical-align: top;
                }
                .badge { 
                    padding: 4px 8px; 
                    border-radius: 4px; 
                    color: white; 
                    font-size: 12px; 
                    font-weight: bold;
                }
                .badge-success { background: #28a745; }
                .badge-warning { background: #ffc107; color: black; }
                .badge-primary { background: #4361ee; }
                .badge-secondary { background: #6c757d; }
                .stats { 
                    display: flex; 
                    justify-content: space-around; 
                    text-align: center; 
                    margin-top: 20px; 
                }
                .stat-item { 
                    padding: 15px; 
                    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                    border-radius: 8px;
                    flex: 1;
                    margin: 0 5px;
                }
                .stat-number { 
                    font-size: 20px; 
                    font-weight: bold; 
                    color: #4361ee;
                    margin-bottom: 5px;
                }
                .stat-label { 
                    color: #6c757d; 
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                .text-primary { color: #4361ee !important; }
                .text-success { color: #28a745 !important; }
                .text-info { color: #17a2b8 !important; }
                .text-warning { color: #ffc107 !important; }
                .text-danger { color: #dc3545 !important; }
                .font-weight-bold { font-weight: bold !important; }
                .ubicacion-info { 
                    background: #e8f5e8; 
                    padding: 8px; 
                    border-radius: 4px; 
                    border-left: 3px solid #28a745;
                }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                    .section { break-inside: avoid; }
                    .header { margin-top: 0; }
                }
                .footer {
                    text-align: center;
                    margin-top: 30px;
                    padding-top: 15px;
                    border-top: 1px solid #ddd;
                    color: #6c757d;
                    font-size: 12px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Detalles del Registro de Tiempo</h1>
                <p><strong>Registro #${registroId}</strong> - Sistema de Control de Tiempos</p>
                <p>Generado el ${new Date().toLocaleString('es-ES', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                })}</p>
            </div>
            ${modalContent}
            <div class="footer">
                <p>Documento generado automáticamente por el Sistema de Control de Tiempos</p>
            </div>
        </body>
        </html>
    `;
    
    ventanaImpresion.document.write(contenido);
    ventanaImpresion.document.close();
    
    // Esperar a que se cargue el contenido antes de imprimir
    ventanaImpresion.onload = function() {
        setTimeout(() => {
            ventanaImpresion.print();
            // Cerrar la ventana después de imprimir (opcional)
            // ventanaImpresion.close();
        }, 500);
    };
    
    // Mostrar confirmación
    Swal.fire({
        icon: 'success',
        title: 'Preparando impresión',
        text: 'Se abrirá una ventana de impresión',
        timer: 2000,
        showConfirmButton: false
    });
}


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

/* Responsive */
@media (max-width: 768px) {
    .modal-xl {
        margin: 10px;
    }
    
    .stat-number {
        font-size: 1.4rem;
    }
}

/* Estilos para las tarjetas de progreso y logros */
.glass-effect {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.animated-card {
    transition: all 0.3s ease;
}

.animated-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.progress-container {
    max-height: 300px;
    overflow-y: auto;
}

.progress-item {
    padding: 5px 0;
}

.progress {
    background-color: #f8f9fa;
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.6s ease;
}

.achievements-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.achievement-item {
    padding: 15px;
    border-radius: 10px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    transition: all 0.3s ease;
}

.achievement-item:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.achievement-icon {
    margin-bottom: 8px;
}

/* Colores personalizados para las barras de progreso */
.bg-success { background: linear-gradient(135deg, #28a745, #20c997) !important; }
.bg-info { background: linear-gradient(135deg, #17a2b8, #6f42c1) !important; }
.bg-warning { background: linear-gradient(135deg, #ffc107, #fd7e14) !important; }
.bg-danger { background: linear-gradient(135deg, #dc3545, #e83e8c) !important; }
.bg-primary { background: linear-gradient(135deg, #007bff, #6610f2) !important; }
.bg-secondary { background: linear-gradient(135deg, #6c757d, #495057) !important; }
.bg-dark { background: linear-gradient(135deg, #343a40, #212529) !important; }

/* Responsive */
@media (max-width: 768px) {
    .achievements-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .achievement-item {
        padding: 12px;
    }
}

</style>
@endsection