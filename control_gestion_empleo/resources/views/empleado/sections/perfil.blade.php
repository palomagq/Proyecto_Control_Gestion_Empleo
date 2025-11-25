@extends('empleado.dashboard_empleado')

@section('content')
<style>
    table.dataTable.dtr-column>tbody>tr>td.dtr-control:before, 
    table.dataTable.dtr-column>tbody>tr>th.dtr-control:before, 
    table.dataTable.dtr-column>tbody>tr>td.control:before, 
    table.dataTable.dtr-column>tbody>tr>th.control:before {
        top: 50%;
        left: unset !important;
        height: .8em;
        width: .8em;
        margin-top: -0.5em;
        margin-left: -0.5em;
        display: block;
        position: absolute;
        color: white;
        border: .15em solid white;
        border-radius: 1em;
        box-shadow: 0 0 .2em #444;
        box-sizing: content-box;
        text-align: center;
        text-indent: 0 !important;
        font-family: "Courier New", Courier, monospace;
        line-height: 1em;
        content: "+";
        background-color: #0275d8;
    }
    @media (max-width:767px) {
        .fecha-con-control{
            padding-left: 1rem;
        } 

        /* Líneas divisorias para detalles responsive */
        .dtr-details-grid .dtr-detail-item {
            border-bottom: 1px solid #dee2e6 !important;
            padding: 0.5em 0;
            width: 100%;
        }
        
        /* Eliminar borde del último elemento (Acciones) */
        .dtr-details-grid .dtr-detail-item:last-child {
            border-bottom: none !important;
        }
    }

    @media (min-width:768px) and (max-width:1023px) {
        .fecha-con-control{
            padding-left: 1rem;
        } 
        table.dataTable>tbody>tr.child ul.dtr-details {
            display: grid;
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        table.dataTable>tbody>tr.child ul.dtr-details>li {
            border-bottom: 1px solid #efefef;
            padding: .5em 0;
            text-align: center;
        }

        /* Líneas divisorias para detalles responsive */
        .dtr-details-grid {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 20px !important;
            box-sizing: border-box !important;
            justify-self: center;
        }
        
        .dtr-detail-item {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            width: 100% !important;
            border-bottom: 1px solid #dee2e6 !important;
            padding: 12px 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
        }
        
        /* Eliminar borde del último elemento (Acciones) */
        .dtr-details-grid .dtr-detail-item:last-child {
            border-bottom: none !important;
        }
    }

    @media (min-width:1024px) and (max-width:1199px) {
        .fecha-con-control{
            padding-left: 1rem;
        } 

        table.dataTable>tbody>tr.child ul.dtr-details {
            display: grid;
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        table.dataTable>tbody>tr.child ul.dtr-details>li {
            border-bottom: 1px solid #efefef;
            padding: .5em 0;
            text-align: center;
        }

       .dtr-details-grid {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 20px !important;
            box-sizing: border-box !important;
            justify-self: center;
        }
        
        .dtr-detail-item {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            width: 100% !important;
            border-bottom: 1px solid #dee2e6 !important;
            padding: 12px 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
        }
        
        /* Eliminar borde del último elemento (Acciones) */
        .dtr-details-grid .dtr-detail-item:last-child {
            border-bottom: none !important;
        }
    }

</style>

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
                        <div class="col-12">
                            <div class="stats-card">
                                <div class="stats-number">{{ $estadisticasMes['total_registros'] }}</div>
                                <div class="stats-label">Registros</div>
                            </div>
                        </div>
                        <div class="col-12">
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
                                <input type="text" class="form-control air-datepicker-input" id="filterMes" 
                                    placeholder="Seleccione mes/año" readonly style="background-color: white; cursor: pointer;">
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
                            <thead class="thead-dark">
                                <tr>
                                    <th class="all">Fecha</th>
                                    <th class="all">Hora Inicio</th>
                                    <th class="min-tablet">Hora Fin</th>
                                    <th class="min-desktop">Pausa Inicio</th>
                                    <th class="min-desktop">Pausa Fin</th>
                                    <th class="min-tablet-lg">Tiempo Pausa</th>
                                    <th class="min-tablet">Duración</th>
                                    <th class="min-desktop">Dirección</th>
                                    <th class="min-desktop">Estado</th>
                                    <th class="min-desktop">Acciones</th>
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

                        <!-- Sección de Tareas del Empleado -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">
                                                <i class="fas fa-tasks mr-2"></i>Mis Tareas
                                                <span class="badge badge-light ml-2" id="total-tareas-badge">{{ $tareasEmpleado['estadisticas']['total'] ?? 0 }}</span>
                                            </h5>
                                            <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#crearTareaModal">
                                                <i class="fas fa-plus mr-1"></i> Nueva Tarea
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            
                                            <!-- Estadísticas Rápidas - DEBEN ESTAR FUERA DEL DATATABLE -->
                                            <div class="row mb-4" id="estadisticas-tareas">
                                                <div class="col-md-2 col-6 text-center">
                                                    <div class="stat-card">
                                                        <div class="stat-number text-primary" id="stat-total">{{ $tareasEmpleado['estadisticas']['total'] ?? 0 }}</div>
                                                        <div class="stat-label">Total</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-6 text-center">
                                                    <div class="stat-card">
                                                        <div class="stat-number text-warning" id="stat-pendientes">{{ $tareasEmpleado['estadisticas']['pendientes'] ?? 0 }}</div>
                                                        <div class="stat-label">Pendientes</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-6 text-center">
                                                    <div class="stat-card">
                                                        <div class="stat-number text-info" id="stat-en-progreso">{{ $tareasEmpleado['estadisticas']['en_progreso'] ?? 0 }}</div>
                                                        <div class="stat-label">En Progreso</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-6 text-center">
                                                    <div class="stat-card">
                                                        <div class="stat-number text-success" id="stat-completadas">{{ $tareasEmpleado['estadisticas']['completadas'] ?? 0 }}</div>
                                                        <div class="stat-label">Completadas</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-6 text-center">
                                                    <div class="stat-card">
                                                        <div class="stat-number text-secondary" id="stat-creadas">{{ $tareasEmpleado['estadisticas']['creadas_count'] ?? 0 }}</div>
                                                        <div class="stat-label">Creadas por mí</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-6 text-center">
                                                    <div class="stat-card">
                                                        <div class="stat-number text-dark" id="stat-asignadas">{{ $tareasEmpleado['estadisticas']['asignadas_count'] ?? 0 }}</div>
                                                        <div class="stat-label">Asignadas</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- DataTable de Tareas -->
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="tareasDataTable">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th width="5%">ID</th>
                                                            <th width="25%">Título</th>
                                                            <th width="10%">Tipo</th>
                                                            <th width="10%">Prioridad</th>
                                                            <th width="10%">Estado</th>
                                                            <th width="10%">Fecha</th>
                                                            <th width="10%">Duración</th>
                                                            <th width="10%">Origen</th>
                                                            <th width="10%">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Los datos se cargarán via AJAX -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

<!-- Modal para Crear Tarea -->
<div class="modal fade" id="crearTareaModal" tabindex="-1" role="dialog" aria-labelledby="crearTareaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success bg-gradient text-white">
                <h5 class="modal-title" id="crearTareaModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i> Crear Nueva Tarea
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="crearTareaForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="titulo_tarea" class="font-weight-bold">Título de la Tarea *</label>
                                <input type="text" class="form-control" id="titulo_tarea" name="titulo" required 
                                       placeholder="Ingrese el título de la tarea">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_tarea_id" class="font-weight-bold">Tipo de Tarea *</label>
                                <select class="form-control" id="tipo_tarea_id" name="tipo_tarea_id" required>
                                    <option value="">Seleccione un tipo</option>
                                    <!-- Se llenará dinámicamente -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion_tarea" class="font-weight-bold">Descripción</label>
                        <textarea class="form-control" id="descripcion_tarea" name="descripcion" rows="3" 
                                  placeholder="Describa los detalles de la tarea..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="prioridad_tarea" class="font-weight-bold">Prioridad *</label>
                                <select class="form-control" id="prioridad_tarea" name="prioridad" required>
                                    <option value="media">Media</option>
                                    <option value="baja">Baja</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_tarea" class="font-weight-bold">Fecha Tarea *</label>
                                <input type="date" class="form-control" id="fecha_tarea" name="fecha_tarea" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="horas_tarea" class="font-weight-bold">Horas Tarea *</label>
                                <input type="number" class="form-control" id="horas_tarea" name="horas_tarea" 
                                    step="0.25" min="0.25" max="24" required 
                                    placeholder="Ej: 1.5">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="area_tarea" class="font-weight-bold">Área/Proyecto</label>
                                <input type="text" class="form-control" id="area_tarea" name="area" 
                                    placeholder="Ej: Desarrollo, Marketing...">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            Los campos marcados con * son obligatorios. La tarea se auto-asignará a tu perfil.
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" onclick="crearTareaEmpleado()">
                    <i class="fas fa-save mr-1"></i> Crear Tarea
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Tarea -->
<div class="modal fade" id="verTareaModal" tabindex="-1" role="dialog" aria-labelledby="verTareaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info bg-gradient text-white">
                <h5 class="modal-title" id="verTareaModalLabel">
                    <i class="fas fa-eye mr-2"></i> Detalles de la Tarea
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenidoTareaModal">
                <!-- Contenido cargado dinámicamente -->
                <div class="text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                    <p>Cargando detalles de la tarea...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cerrar
                </button>
                <button type="button" class="btn btn-warning" id="btnEditarDesdeVista" style="display: none;">
                    <i class="fas fa-edit mr-1"></i> Editar Tarea
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Tarea -->
<div class="modal fade" id="editarTareaModal" tabindex="-1" role="dialog" aria-labelledby="editarTareaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning bg-gradient text-white">
                <h5 class="modal-title" id="editarTareaModalLabel">
                    <i class="fas fa-edit mr-2"></i> Editar Tarea
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editarTareaForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editar_tarea_id" name="tarea_id">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="editar_titulo_tarea" class="font-weight-bold">Título de la Tarea *</label>
                                <input type="text" class="form-control" id="editar_titulo_tarea" name="titulo" required 
                                       placeholder="Ingrese el título de la tarea">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="editar_tipo_tarea_id" class="font-weight-bold">Tipo de Tarea *</label>
                                <select class="form-control" id="editar_tipo_tarea_id" name="tipo_tarea_id" required>
                                    <option value="">Seleccione un tipo</option>
                                    <!-- Se llenará dinámicamente -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="editar_descripcion_tarea" class="font-weight-bold">Descripción</label>
                        <textarea class="form-control" id="editar_descripcion_tarea" name="descripcion" rows="3" 
                                  placeholder="Describa los detalles de la tarea..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="editar_prioridad_tarea" class="font-weight-bold">Prioridad *</label>
                                <select class="form-control" id="editar_prioridad_tarea" name="prioridad" required>
                                    <option value="media">Media</option>
                                    <option value="baja">Baja</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="editar_fecha_tarea" class="font-weight-bold">Fecha Tarea *</label>
                                <input type="date" class="form-control" id="editar_fecha_tarea" name="fecha_tarea" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="editar_horas_tarea" class="font-weight-bold">Horas Tarea *</label>
                                <input type="number" class="form-control" id="editar_horas_tarea" name="horas_tarea" 
                                    step="0.25" min="0.25" max="24" required 
                                    placeholder="Ej: 1.5">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="editar_area_tarea" class="font-weight-bold">Área/Proyecto</label>
                                <input type="text" class="form-control" id="editar_area_tarea" name="area" 
                                    placeholder="Ej: Desarrollo, Marketing...">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            Solo puedes editar tareas que hayas creado tú mismo.
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-warning" onclick="actualizarTareaEmpleado()">
                    <i class="fas fa-save mr-1"></i> Actualizar Tarea
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- ***********************************************************************  JS ****************************************************************************************************-->
<!-- jQuery completo (NO slim) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Air Datepicker (después de jQuery) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.es.min.js"></script>

@section('scripts')
<script>
$(document).ready(function() {
    const empleadoId = {{ $empleado->id }};
    let dataTable;
    let datepickerInstance;

    // Función para formatear fecha de mm/yyyy a Y-m (para el servidor)
    function formatDateForServer(dateStr) {
        if (!dateStr) return '';
        const parts = dateStr.split('/');
        if (parts.length === 2) {
            const month = parts[0].padStart(2, '0');
            const year = parts[1];
            return `${year}-${month}`;
        }
        return dateStr;
    }

    // Función para formatear fecha de Y-m a mm/yyyy (para mostrar)
    function formatDateForDisplay(dateStr) {
        if (!dateStr) return '';
        const parts = dateStr.split('-');
        if (parts.length === 2) {
            const year = parts[0];
            const month = parts[1];
            return `${month}/${year}`;
        }
        return dateStr;
    }


    // Inicializar Air Datepicker para selector de mes y año
    function initializeDatepicker() {
        const $filterMes = $('#filterMes');
        
        datepickerInstance = $filterMes.datepicker({
            language: 'es',
            dateFormat: 'yyyy-mm',
            minDate: new Date(2020, 0, 1),
            maxDate: new Date(),
            view: 'months',
            minView: 'months',
            selectOtherMonths: false,
            moveToOtherMonthsOnSelect: false,
            
            // CONFIGURACIÓN CLAVE
            autoClose: false, // ❌ NO cerrar automáticamente
            toggleSelected: true,
            
            onShow: function(dp, animationCompleted) {
                if (!animationCompleted) {
                    $('.datepicker--cells.days').hide();
                    $('.datepicker--content').addClass('months-only');
                }
            },
            
            onSelect: function(formattedDate, date, inst) {
                if (date) {
                    $('#btn-apply-filters').prop('disabled', false);
                    mostrarInfoFiltro(formattedDate);
                    
                    // ✅ CERRAR MANUALMENTE solo cuando se selecciona mes/año
                    setTimeout(() => {
                        inst.hide();
                    }, 100);
                }
            },
            
            // ✅ PREVENIR que se cierre al hacer clic en navegación
            onHide: function(dp, animationCompleted) {
                // Solo permitir cerrar si fue por selección (manejado en onSelect)
                if (!animationCompleted) {
                    return false; // Prevenir cierre
                }
            }
        }).data('datepicker');
        
        // Manejo normal del input
        $filterMes.on('click', function() {
            if (!datepickerInstance.visible) {
                datepickerInstance.show();
            }
        });

        // Cerrar al hacer clic fuera
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.datepicker, #filterMes').length) {
                if (datepickerInstance && datepickerInstance.visible) {
                    datepickerInstance.hide();
                }
            }
        });
    }

    // Inicializar el datepicker
    initializeDatepicker();

    // Actualizar conexión inmediatamente
    actualizarEstadoConexion();
    
    // Actualizar cada 1 minuto para mantener el estado activo
    setInterval(actualizarEstadoConexion, 60000);
    
    // También actualizar cuando se interactúa con la página
    $(document).on('click keypress scroll', function() {
        actualizarEstadoConexion();
    });
    
    // Mostrar estado actual de conexión
    setInterval(function() {
        $.ajax({
            url: `/empleado/${empleadoId}/conexion/estado`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    //console.log('Estado conexión:', response.data);
                }
            }
        });
    }, 30000);

    initializeTareasDataTable();


    // Inicializar DataTable con manejo de estado vacío
  function initializeDataTable() {
    console.log('🔄 Inicializando DataTable responsive...');

    // Destruir si ya existe
    if ($.fn.DataTable.isDataTable('#historial-table')) {
        dataTable.destroy();
        $('#historial-table').empty();
    }

    try {
        dataTable = $('#historial-table').DataTable({
            serverSide: true,
            processing: true,
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
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
                    return d;
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
                    className: 'all control', // Añadir clase control aquí
                    responsivePriority: 1,
                    render: function(data) {
                        const fechaFormateada = data ? new Date(data).toLocaleDateString('es-ES', {
                            weekday: 'short',
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        }) : '-';
                        
                        return `<div class="fecha-con-control">${fechaFormateada}</div>`;
                    }
                },
                { 
                    data: 'inicio',
                    name: 'inicio',
                    className: 'all',
                    responsivePriority: 2,
                    render: function(data) {
                        return data ? new Date(data).toLocaleTimeString('es-ES') : '-';
                    }
                },
                { 
                    data: 'fin',
                    name: 'fin',
                    className: 'min-tablet',
                    responsivePriority: 6,
                    render: function(data) {
                        return data ? new Date(data).toLocaleTimeString('es-ES') : 'En progreso';
                    }
                },
                { 
                    data: 'pausa_inicio',
                    name: 'pausa_inicio',
                    className: 'min-desktop',
                    responsivePriority: 8,
                    render: function(data) {
                        return data ? new Date(data).toLocaleTimeString('es-ES') : '-';
                    }
                },
                { 
                    data: 'pausa_fin',
                    name: 'pausa_fin',
                    className: 'min-desktop',
                    responsivePriority: 9,
                    render: function(data) {
                        return data ? new Date(data).toLocaleTimeString('es-ES') : '-';
                    }
                },
                { 
                    data: 'tiempo_pausa_total',
                    name: 'tiempo_pausa_total',
                    className: 'min-tablet-lg',
                    responsivePriority: 7,
                    render: function(data, type, row) {
                        let tiempoPausa = Math.max(0, parseInt(data || 0));
                        
                        if (tiempoPausa === 0 && row.pausa_inicio && row.pausa_fin) {
                            const inicio = new Date(row.pausa_inicio);
                            const fin = new Date(row.pausa_fin);
                            const diferenciaMs = fin - inicio;
                            tiempoPausa = Math.max(0, Math.floor(diferenciaMs / 1000));
                        }
                        
                        if (tiempoPausa === 0) {
                            if (row.pausa_inicio || row.pausa_fin) {
                                return '<span class="text-warning">00:00</span>';
                            }
                            return '<span class="text-muted">Sin pausas</span>';
                        }
                        
                        return `<span class="text-info font-weight-bold">${formatTimeForTable(tiempoPausa)}</span>`;
                    }
                },
                { 
                    data: 'tiempo_total',
                    name: 'tiempo_total',
                    className: 'min-tablet',
                    responsivePriority: 4,
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
                    className: 'min-desktop',
                    responsivePriority: 10,
                    render: function(data, type, row) {
                        const ciudad = row.ciudad || '';
                        const pais = row.pais || '';
                        
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
                        
                        return '<span class="text-muted">Sin ubicación</span>';
                    }
                },
                { 
                    data: 'estado',
                    name: 'estado',
                    className: 'min-desktop',
                    responsivePriority: 5,
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
                    className: 'min-desktop',
                    responsivePriority: 11,
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return data ? `
                            <button class="btn btn-sm btn-outline-primary" onclick="viewDetails(${data})" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                        ` : '';
                    }
                }
            ],
            language: {
                url: "{{ asset('js/datatables/Spanish.json') }}",
                emptyTable: 'No hay registros para el mes seleccionado',
                zeroRecords: 'No se encontraron registros que coincidan'
            },
            order: [[0, 'desc']], // Ordenar por la primera columna (fecha)
            responsive: {
                details: {
                    type: 'column',
                    target: 0, // Usar la columna de control (ícono +/-) como objetivo
                    renderer: function (api, rowIdx, columns) {
                        const data = $.map(columns, function (col, i) {
                            // Excluir la columna de control y acciones de los detalles
                            if (col.columnIndex === 0 || col.columnIndex === 10) return '';
                            
                            return col.hidden ? 
                                `<div class="dtr-detail-item">
                                    <span class="dtr-title">${col.title}</span>
                                    <span class="dtr-data">${col.data}</span>
                                </div>` : 
                                '';
                        }).join('');

                        return data ? 
                            $(`<div class="dtr-details-grid">${data}</div>`) : 
                            false;
                    }
                }
            },
            autoWidth: false,
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
            },
            initComplete: function(settings, json) {
                console.log('✅ DataTable responsive inicializado correctamente');
                console.log('🔍 Configuración responsive activa en columna de fecha');
            }
        });
    } catch (error) {
        console.error('❌ Error inicializando DataTable responsive:', error);
        
        // Fallback básico sin responsive
        //initializeBasicDataTable();
    }
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


    // ✅ FUNCIÓN PARA ACTUALIZAR ÍCONOS DE EXPANSIÓN
    function updateExpandIcons() {
        console.log('🔄 Actualizando íconos de expansión...');
        
        if (!dataTable) {
            console.warn('⚠️ DataTable de registros no disponible');
            return;
        }
        
        // Forzar redibujado de las filas
        dataTable.rows().every(function() {
            const node = this.node();
            const isShown = this.child.isShown();
            const expandIcon = $(node).find('.dtr-control').find(':before');
            
            if (isShown) {
                // Si está expandido, mostrar "-"
                $(node).find('.dtr-control').attr('data-expanded', 'true');
            } else {
                // Si está colapsado, mostrar "+"
                $(node).find('.dtr-control').attr('data-expanded', 'false');
            }
        });
        
        console.log('✅ Íconos de expansión actualizados');
    }


    // Resetear filtros
    $('#btn-reset-filters').click(function() {
        if (datepickerInstance) {
            datepickerInstance.clear();
        } else {
            $('#filterMes').val(''); // Fallback
        }
        
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
        if (datepickerInstance) {
            datepickerInstance.clear();
        } else {
            $('#filterMes').val(''); // Fallback
        }
        
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

    // Actualizar estado de conexión cuando el empleado accede
    function actualizarEstadoConexion() {
        const empleadoId = {{ $empleado->id }};
        
        $.ajax({
            url: `/empleado/${empleadoId}/conexion/actualizar`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
               /* if (response.success) {
                    console.log('✅ Estado de conexión actualizado - Empleado CONECTADO');
                }*/
            },
            error: function(xhr, status, error) {
                console.error('❌ Error actualizando conexión:', error);
            }
        });
    }

    // Actualizar resumen del período
    function updatePeriodSummary() {
        const selectedDate = $('#filterMes').val();
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
                //console.log('Respuesta resumen:', response);
                
                // Usar la función mejorada que incluye días
                const totalHorasFormateadas = formatTotalHoursWithDays(response.total_horas);
                const promedioFormateado = formatTotalHoursWithDays(response.promedio_diario);
                
                $('#total-horas-periodo').html(totalHorasFormateadas);
                $('#total-registros-periodo').text(response.total_registros);
                $('#promedio-diario-periodo').html(promedioFormateado);
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
        //console.log('🔍 Cargando detalles del registro:', registroId);
        
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
            //console.log('🔍 Iniciando geolocalización...');
            
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
       /* if (segundos % 30 === 0) {
            console.log(`⏱️ Contador activo: ${tiempoFormateado} (${segundos} segundos)`);
        }*/
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
            
            // Usar la función mejorada que incluye días
            const horasFormateadas = formatTotalHoursWithDays(response.total_horas);
            $('.stats-number').last().html(horasFormateadas);
            
            // Formatear promedio diario
            const promedioFormateado = formatTotalHoursWithDays(response.promedio_horas);
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

    // Función específica para convertir formato decimal "1.18h" a "1h 11m" o "1d 2h 30m"
    function formatDecimalHoursToHM(decimalHoursStr) {
        // Extraer el número decimal del string (quitando la 'h')
        const decimalHours = safeParseFloat(decimalHoursStr);
        
        if (decimalHours === 0) return '0h 00m';
        
        // Si supera las 24 horas, convertir a días
        if (decimalHours >= 24) {
            const dias = Math.floor(decimalHours / 24);
            const horasRestantes = decimalHours % 24;
            const horas = Math.floor(horasRestantes);
            const minutosDecimal = (horasRestantes - horas) * 60;
            const minutos = Math.round(minutosDecimal);
            
            // Si los minutos son 60, sumar una hora
            if (minutos === 60) {
                return `${dias}d ${horas + 1}h 00m`;
            }
            
            return `${dias}d ${horas}h ${minutos.toString().padStart(2, '0')}m`;
        } else {
            // Formato normal para menos de 24 horas
            const horas = Math.floor(decimalHours);
            const minutosDecimal = (decimalHours - horas) * 60;
            const minutos = Math.round(minutosDecimal);
            
            // Si los minutos son 60, sumar una hora
            if (minutos === 60) {
                return `${horas + 1}h 00m`;
            }
            
            return `${horas}h ${minutos.toString().padStart(2, '0')}m`;
        }
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


// =============================================
// FUNCIONES PARA GESTIÓN DE TAREAS DEL EMPLEADO
// =============================================

let tiposTareaCargados = false;

// Cargar tipos de tarea al abrir el modal
$('#crearTareaModal').on('show.bs.modal', function() {
    if (!tiposTareaCargados) {
        cargarTiposTareaEmpleado();
    }
});

// Función para cargar tipos de tarea
function cargarTiposTareaEmpleado() {
    $.ajax({
        url: '/empleado/tipos-tarea',
        type: 'GET',
        success: function(response) {
            if (response.success && response.data) {
                $('#tipo_tarea_id').empty().append('<option value="">Seleccione un tipo</option>');
                response.data.forEach(function(tipo) {
                    $('#tipo_tarea_id').append(`<option value="${tipo.id}">${tipo.descripcion || tipo.nombre}</option>`);
                });
                tiposTareaCargados = true;
            }
        },
        error: function() {
            console.error('Error cargando tipos de tarea');
        }
    });
}

// Función para crear tarea
window.crearTareaEmpleado = function() {
    console.log('📝 Creando nueva tarea...');
    
    const formData = new FormData(document.getElementById('crearTareaForm'));
    
    // Validaciones básicas
    if (!$('#titulo_tarea').val().trim()) {
        Swal.fire('Error', 'El título es obligatorio', 'error');
        $('#titulo_tarea').focus();
        return;
    }

    if (!$('#tipo_tarea_id').val()) {
        Swal.fire('Error', 'Debe seleccionar un tipo de tarea', 'error');
        $('#tipo_tarea_id').focus();
        return;
    }

    if (!$('#fecha_tarea').val()) {
        Swal.fire('Error', 'Debe seleccionar una fecha para la tarea', 'error');
        $('#fecha_tarea').focus();
        return;
    }

    if (!$('#horas_tarea').val() || $('#horas_tarea').val() <= 0) {
        Swal.fire('Error', 'Debe ingresar el número de horas de la tarea', 'error');
        $('#horas_tarea').focus();
        return;
    }

    // Mostrar loading
    Swal.fire({
        title: 'Creando tarea...',
        text: 'Por favor espere',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: `/empleado/{{ $empleado->id }}/tareas/crear`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            Swal.close();
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                $('#crearTareaModal').modal('hide');
                document.getElementById('crearTareaForm').reset();
                
                // Recargar el DataTable de tareas
                recargarTareasDataTable();
                
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            
            let errorMessage = 'Error al crear la tarea';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                let errorList = '';
                for (const field in errors) {
                    errorList += `<strong>${field}:</strong> ${errors[field].join(', ')}<br>`;
                }
                Swal.fire({
                    title: 'Errores de validación',
                    html: errorList,
                    icon: 'error'
                });
                return;
            }

            Swal.fire('Error', errorMessage, 'error');
        }
    });
}


// DataTable para Tareas del Empleado
function initializeTareasDataTable() {
    const empleadoId = {{ $empleado->id }};
    
    window.tareasDataTable = $('#tareasDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: `/empleado/${empleadoId}/tareas/datatable`,
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('Error loading tareas DataTable:', error);
                // Mostrar mensaje de error en la tabla
                $('#tareasDataTable tbody').html(`
                    <tr>
                        <td colspan="9" class="text-center text-danger py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                            <h6>Error al cargar las tareas</h6>
                            <button class="btn btn-sm btn-primary" onclick="window.tareasDataTable.ajax.reload()">
                                <i class="fas fa-redo mr-1"></i> Reintentar
                            </button>
                        </td>
                    </tr>
                `);
            }
        },
        columns: [
            { 
                data: 'id',
                name: 'id',
                width: '5%',
                className: 'text-center',
                orderable: true
            },
            { 
                data: 'titulo',
                name: 'titulo',
                width: '25%',
                orderable: true,
                render: function(data, type, row) {
                    let html = `<strong class="text-dark">${data || 'Sin título'}</strong>`;
                    if (row.descripcion) {
                        html += `<br><small class="text-muted">${row.descripcion.substring(0, 50)}${row.descripcion.length > 50 ? '...' : ''}</small>`;
                    }
                    return html;
                }
            },
            { 
                data: 'tipo_tarea',
                name: 'tipo_tarea',
                width: '10%',
                orderable: true,
                render: function(data, type, row) {
                    return `<span class="badge badge-light border" style="border-left: 3px solid ${row.color} !important;">${data}</span>`;
                }
            },
            { 
                data: 'prioridad',
                name: 'prioridad',
                width: '10%',
                orderable: true,
                render: function(data) {
                    const badges = {
                        'baja': 'badge-success',
                        'media': 'badge-info', 
                        'alta': 'badge-warning',
                        'urgente': 'badge-danger'
                    };
                    return `<span class="badge ${badges[data] || 'badge-secondary'}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                }
            },
            { 
                data: 'estado',
                name: 'estado',
                width: '10%',
                orderable: true,
                render: function(data) {
                    const estados = {
                        'pendiente': 'badge-secondary',
                        'en_progreso': 'badge-primary',
                        'completada': 'badge-success',
                        'cancelada': 'badge-danger'
                    };
                    const estadoTexto = data.replace('_', ' ');
                    return `<span class="badge ${estados[data] || 'badge-secondary'}">${estadoTexto.charAt(0).toUpperCase() + estadoTexto.slice(1)}</span>`;
                }
            },
            { 
                data: 'fecha_tarea',
                name: 'fecha_tarea',
                width: '10%',
                orderable: true,
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString('es-ES') : '-';
                }
            },
            { 
                data: 'horas_tarea',
                name: 'horas_tarea',
                width: '10%',
                className: 'text-center',
                orderable: true,
                render: function(data) {
                    return `${data}h`;
                }
            },
            { 
                data: 'creador_tipo',
                name: 'creador_tipo',
                width: '10%',
                orderable: true,
                render: function(data, type, row) {
                    if (row.creador_tipo === 'empleado') {
                        return '<span class="badge badge-info">Creada por mí</span>';
                    } else {
                        return '<span class="badge badge-warning">Asignada por admin</span>';
                    }
                }
            },
            {
                data: 'id',
                name: 'acciones',
                width: '10%',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    //console.log('Renderizando acciones para tarea:', row);
        
                    // VERIFICACIÓN CORREGIDA: El empleado puede editar/eliminar si:
                    // 1. La tarea fue creada por un empleado (creador_tipo === 'empleado')
                    // 2. Y el empleado creador coincide con el empleado actual
                    const puedeEditarEliminar = row.creador_tipo === 'empleado' && 
                                            row.empleado_creador_id === {{ $empleado->id }};
                    
                    /*console.log('Permisos de edición/eliminación:', {
                        tareaId: row.id,
                        creador_tipo: row.creador_tipo,
                        empleado_creador_id: row.empleado_creador_id,
                        empleado_actual_id: {{ $empleado->id }},
                        puedeEditarEliminar: puedeEditarEliminar
                    });*/
                    
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-info" onclick="verTareaEmpleado(${data})" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                            ${puedeEditarEliminar ? 
                                `<button class="btn btn-warning" onclick="editarTareaEmpleado(${data})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>` : ''
                            }
                                ${puedeEditarEliminar ? `
                                <button class="btn btn-danger" onclick="eliminarTareaEmpleado(${data})" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            ` : ''}
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" title="Más acciones">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <h6 class="dropdown-header">Cambiar Estado</h6>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="cambiarEstadoTarea(${data}, 'pendiente')">
                                        <i class="fas fa-clock mr-2 text-secondary"></i>Pendiente
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="cambiarEstadoTarea(${data}, 'en_progreso')">
                                        <i class="fas fa-spinner mr-2 text-primary"></i>En Progreso
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="cambiarEstadoTarea(${data}, 'completada')">
                                        <i class="fas fa-check mr-2 text-success"></i>Completada
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="cambiarEstadoTarea(${data}, 'cancelada')">
                                        <i class="fas fa-times mr-2 text-danger"></i>Cancelada
                                    </a>
                                </div>
                            </div> 
                        </div>
                    `;
                }
            }
        ],
        language: {
            url: "{{ asset('js/datatables/Spanish.json') }}",
            emptyTable: 'No hay tareas registradas',
            zeroRecords: 'No se encontraron tareas que coincidan'
        },
        order: [[0, 'asc']], // ORDEN POR DEFECTO: Columna 0 (ID) ASCENDENTE
        pageLength: 5,
        lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
        responsive: true,
        autoWidth: false,
        scrollX: true,
        drawCallback: function(settings) {
            // Actualizar estadísticas cuando se carga/recarga la tabla
            updateTareasStats();
            
            // Mostrar información de debug
            console.log('Tareas DataTable dibujado:', {
                total: settings.json?.recordsTotal,
                filtradas: settings.json?.recordsFiltered,
                mostrando: this.api().rows({page: 'current'}).count()
            });
        },
        initComplete: function(settings, json) {
            console.log('Tareas DataTable inicializado correctamente');
            // Actualizar estadísticas iniciales
            updateTareasStats();
        }
    });
}


// Función para actualizar estadísticas
function updateTareasStats() {
    const empleadoId = {{ $empleado->id }};
    
    $.ajax({
        url: `/empleado/${empleadoId}/tareas/estadisticas`,
        type: 'GET',
        success: function(response) {
            if (response.success && response.data) {
                const stats = response.data;
                
                // Actualizar todas las estadísticas
                $('#total-tareas-badge').text(stats.total || 0);
                $('#stat-total').text(stats.total || 0);
                $('#stat-pendientes').text(stats.pendientes || 0);
                $('#stat-en-progreso').text(stats.en_progreso || 0);
                $('#stat-completadas').text(stats.completadas || 0);
                $('#stat-creadas').text(stats.creadas_count || 0);
                $('#stat-asignadas').text(stats.asignadas_count || 0);
                
                console.log('Estadísticas de tareas actualizadas:', stats);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error actualizando estadísticas de tareas:', error);
            // Mantener los valores iniciales de Blade si hay error
        }
    });
}
// Función para recargar el DataTable después de acciones
function recargarTareasDataTable() {
    if (window.tareasDataTable) {
        window.tareasDataTable.ajax.reload(null, false);
    }
}

// Función para ver detalles de tarea
// =============================================
// FUNCIONES PARA GESTIÓN DE TAREAS - EMPLEADO
// =============================================

// Función para ver tarea (empleado)
window.verTareaEmpleado = function(tareaId) {
    console.log('🔍 Empleado viendo tarea ID:', tareaId);
    
    // Guardar el ID para uso posterior
    window.tareaActualId = tareaId;
    
    // Mostrar loading en el modal mismo, no con SweetAlert
    $('#contenidoTareaModal').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
            <p>Cargando detalles de la tarea...</p>
        </div>
    `);
    
    // Mostrar modal inmediatamente
    $('#verTareaModal').modal('show');
    
    // Obtener datos de la tarea
    $.ajax({
        url: '/empleado/' + {{ $empleado->id }} + '/tareas/' + tareaId + '/detalles',
        type: 'GET',
        timeout: 15000, // 15 segundos máximo
        success: function(response) {
            console.log('✅ Datos de tarea recibidos:', response);
            
            if (response.success && response.data) {
                const tarea = response.data.tarea;
                const empleados = response.data.empleados_asignados || [];
                
                // Determinar si el empleado puede editar esta tarea
                const puedeEditar = tarea.creador_tipo === 'empleado' && tarea.empleado_creador_id === {{ $empleado->id }};
                
                // Construir el contenido
                let contenidoHTML = `
                <div class="container-fluid">
                    <style>
                        .modal-tarea-card {
                            border: 1px solid #e0e0e0;
                            border-radius: 10px;
                            margin-bottom: 20px;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        }
                        .modal-tarea-header {
                            padding: 15px 20px;
                            border-radius: 10px 10px 0 0;
                            font-weight: 600;
                            margin: -1px -1px 15px -1px;
                            border-bottom: 2px solid;
                        }
                        .header-fechas {
                            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
                            color: #0d47a1;
                            border-bottom-color: #2196f3;
                        }
                        .header-adicional {
                            background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
                            color: #1b5e20;
                            border-bottom-color: #4caf50;
                        }
                        .header-empleados {
                            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
                            color: #e65100;
                            border-bottom-color: #ff9800;
                        }
                        .modal-tarea-body {
                            padding: 20px;
                        }
                        .info-row {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 10px;
                            padding: 8px 0;
                            border-bottom: 1px solid #f5f5f5;
                        }
                        .info-label {
                            font-weight: 600;
                            color: #495057;
                        }
                        .info-value {
                            color: #6c757d;
                            text-align: right;
                        }
                        .badge-modal {
                            padding: 6px 12px;
                            border-radius: 20px;
                            font-weight: 600;
                        }
                        .tarea-titulo {
                            color: #4361ee;
                            font-weight: 700;
                            margin-bottom: 10px;
                        }
                        .tarea-descripcion {
                            color: #6c757d;
                            line-height: 1.6;
                            margin-bottom: 20px;
                        }
                    </style>

                    <!-- Título y Descripción -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="tarea-titulo">${tarea.titulo || 'Sin título'}</h4>
                            <p class="tarea-descripcion">${tarea.descripcion || 'Sin descripción'}</p>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Información de Fechas -->
                        <div class="col-md-6">
                            <div class="modal-tarea-card">
                                <div class="modal-tarea-header header-fechas">
                                    <h6 class="mb-0">
                                        <i class="fas fa-calendar-alt mr-2"></i>Información de Fechas
                                    </h6>
                                </div>
                                <div class="modal-tarea-body">
                                    <div class="info-row">
                                        <div class="info-label">Fecha Tarea:</div>
                                        <div class="info-value">${formatFecha(tarea.fecha_tarea) || 'No especificada'}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Duración:</div>
                                        <div class="info-value">${formatHorasTarea(tarea.horas_tarea)}</div>
                                    </div>
                                    <div class="info-row" style="border-bottom: none;">
                                        <div class="info-label">Fecha Creación:</div>
                                        <div class="info-value">${formatFechaCompleta(tarea.created_at)}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Adicional -->
                        <div class="col-md-6">
                            <div class="modal-tarea-card">
                                <div class="modal-tarea-header header-adicional">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle mr-2"></i>Información Adicional
                                    </h6>
                                </div>
                                <div class="modal-tarea-body">
                                    <div class="info-row">
                                        <div class="info-label">Tipo de Tarea:</div>
                                        <div class="info-value">
                                            <span class="badge badge-modal" style="background: ${tarea.tipo_tarea_color || '#6c757d'}; color: black;">
                                                ${tarea.tipo_tarea_nombre || 'No especificado'}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Prioridad:</div>
                                        <div class="info-value">${getBadgePrioridad(tarea.prioridad)}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Estado:</div>
                                        <div class="info-value">${getBadgeEstado(tarea.estado)}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Área/Proyecto:</div>
                                        <div class="info-value">${tarea.area || 'No especificado'}</div>
                                    </div>
                                    <div class="info-row" style="border-bottom: none;">
                                        <div class="info-label">Creada por:</div>
                                        <div class="info-value">
                                            <span class="badge ${tarea.creador_tipo === 'empleado' ? 'badge-info' : 'badge-warning'} badge-modal">
                                                ${tarea.creador_tipo === 'empleado' ? 'Yo mismo' : 'Administrador'}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empleados Asignados -->
                    <div class="row">
                        <div class="col-12">
                            <div class="modal-tarea-card">
                                <div class="modal-tarea-header header-empleados">
                                    <h6 class="mb-0">
                                        <i class="fas fa-users mr-2"></i>Empleados Asignados
                                    </h6>
                                </div>
                                <div class="modal-tarea-body">
                `;

                if (empleados.length > 0) {
                    contenidoHTML += '<div class="d-flex flex-wrap gap-2">';
                    empleados.forEach(function(emp) {
                        contenidoHTML += `
                            <span class="badge badge-primary badge-modal">
                                <i class="fas fa-user mr-1"></i>
                                ${emp.nombre_completo || 'Empleado'}
                            </span>
                        `;
                    });
                    contenidoHTML += '</div>';
                } else {
                    contenidoHTML += `
                        <p class="text-muted mb-0 text-center">
                            <i class="fas fa-users fa-lg mb-2 d-block"></i>
                            No hay empleados asignados a esta tarea.
                        </p>
                    `;
                }

                contenidoHTML += `
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;

                $('#contenidoTareaModal').html(contenidoHTML);
                
                // Mostrar/ocultar botón de editar
                if (puedeEditar) {
                    $('#btnEditarDesdeVista').show().off('click').on('click', function() {
                        $('#verTareaModal').modal('hide');
                        setTimeout(function() {
                            editarTareaEmpleado(tareaId);
                        }, 300);
                    });
                } else {
                    $('#btnEditarDesdeVista').hide();
                }
                
            } else {
                $('#contenidoTareaModal').html(`
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5>Error al cargar la tarea</h5>
                        <p class="text-muted">${response.message || 'No se pudieron cargar los detalles de la tarea'}</p>
                        <button class="btn btn-secondary mt-2" data-dismiss="modal">Cerrar</button>
                    </div>
                `);
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error cargando tarea:', error);
            $('#contenidoTareaModal').html(`
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5>Error de conexión</h5>
                    <p class="text-muted">No se pudo cargar la información de la tarea.</p>
                    <button class="btn btn-secondary mt-2" data-dismiss="modal">Cerrar</button>
                </div>
            `);
        }
    });
};

// Función para editar tarea (empleado)
window.editarTareaEmpleado = function(tareaId) {
    console.log('✏️ Abriendo modal de edición para tarea ID:', tareaId);
    
    // Mostrar loading
    Swal.fire({
        title: 'Cargando tarea...',
        text: 'Por favor espere',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Obtener datos de la tarea
    $.ajax({
        url: `/empleado/{{ $empleado->id }}/tareas/${tareaId}/detalles`,
        type: 'GET',
        success: function(response) {
            Swal.close();
            
            if (response.success && response.data) {
                const tarea = response.data.tarea;
                
                console.log('📋 Datos de tarea recibidos:', {
                    id: tarea.id,
                    titulo: tarea.titulo,
                    tipo_tarea_id: tarea.tipo_tarea_id,
                    fecha_tarea: tarea.fecha_tarea,
                    fecha_original: tarea.fecha_tarea
                });
                
                // Verificar que el empleado puede editar esta tarea
                if (tarea.creador_tipo !== 'empleado') {
                    Swal.fire({
                        icon: 'error',
                        title: 'No autorizado',
                        text: 'Solo puedes editar tareas que hayas creado tú mismo.'
                    });
                    return;
                }

                // Llenar los campos básicos primero
                $('#editar_tarea_id').val(tarea.id);
                $('#editar_titulo_tarea').val(tarea.titulo);
                $('#editar_descripcion_tarea').val(tarea.descripcion || '');
                $('#editar_prioridad_tarea').val(tarea.prioridad);
                $('#editar_horas_tarea').val(tarea.horas_tarea);
                $('#editar_area_tarea').val(tarea.area || '');
                
                // CORREGIR FECHA: Manejar zona horaria
                if (tarea.fecha_tarea) {
                    // Crear fecha en UTC para evitar desfase
                    const fechaUTC = new Date(tarea.fecha_tarea + 'T00:00:00Z');
                    const fechaFormateada = fechaUTC.toISOString().split('T')[0];
                    $('#editar_fecha_tarea').val(fechaFormateada);
                    
                    console.log('📅 Corrección fecha:', {
                        'original': tarea.fecha_tarea,
                        'utc': fechaUTC,
                        'formateada': fechaFormateada,
                        'input_value': $('#editar_fecha_tarea').val()
                    });
                }
                
                // Cargar tipos de tarea y establecer el valor
                cargarTiposTareaParaEdicion(tarea.tipo_tarea_id);
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'No se pudieron cargar los datos de la tarea'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            console.error('❌ Error cargando tarea:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo cargar la información de la tarea: ' + error
            });
        }
    });
}

// Función para cargar tipos de tarea en el modal de edición
function cargarTiposTareaParaEdicion(tipoTareaId = null) {
    console.log('🔄 Cargando tipos de tarea para edición, ID a establecer:', tipoTareaId);
    
    $.ajax({
        url: '/empleado/tipos-tarea',
        type: 'GET',
        success: function(response) {
            if (response.success && response.data) {
                $('#editar_tipo_tarea_id').empty().append('<option value="">Seleccione un tipo</option>');
                
                // Log de todos los tipos disponibles
                console.log('📊 Tipos de tarea disponibles:', response.data);
                
                response.data.forEach(function(tipo) {
                    $('#editar_tipo_tarea_id').append(
                        `<option value="${tipo.id}">${tipo.descripcion || tipo.nombre}</option>`
                    );
                });
                
                // Establecer el valor y verificar
                if (tipoTareaId) {
                    setTimeout(() => {
                        $('#editar_tipo_tarea_id').val(tipoTareaId);
                        
                        // Verificar que se estableció correctamente
                        const valorEstablecido = $('#editar_tipo_tarea_id').val();
                        console.log('✅ Intento de establecer tipo:', {
                            'esperado': tipoTareaId,
                            'establecido': valorEstablecido,
                            'coincide': valorEstablecido == tipoTareaId
                        });
                        
                        // Si no coincide, forzar selección
                        if (valorEstablecido != tipoTareaId) {
                            console.warn('⚠️ El valor no se estableció correctamente, forzando...');
                            $('#editar_tipo_tarea_id option').each(function() {
                                if ($(this).val() == tipoTareaId) {
                                    $(this).prop('selected', true);
                                    console.log('🔄 Valor forzado:', $(this).val());
                                    return false;
                                }
                            });
                        }
                        
                        // Mostrar el modal cuando todo esté listo
                        $('#editarTareaModal').modal('show');
                        
                    }, 200);
                } else {
                    $('#editarTareaModal').modal('show');
                }
                
            } else {
                console.error('❌ No se pudieron cargar los tipos de tarea');
                $('#editarTareaModal').modal('show');
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error cargando tipos de tarea para edición:', error);
            $('#editarTareaModal').modal('show');
        }
    });
}

// Función para actualizar la tarea
window.actualizarTareaEmpleado = function() {
    const tareaId = $('#editar_tarea_id').val();
    const formData = new FormData(document.getElementById('editarTareaForm'));
    
    console.log('🔄 Actualizando tarea ID:', tareaId);
    
    // Validaciones básicas
    if (!$('#editar_titulo_tarea').val().trim()) {
        Swal.fire('Error', 'El título es obligatorio', 'error');
        $('#editar_titulo_tarea').focus();
        return;
    }

    if (!$('#editar_tipo_tarea_id').val()) {
        Swal.fire('Error', 'Debe seleccionar un tipo de tarea', 'error');
        $('#editar_tipo_tarea_id').focus();
        return;
    }

    if (!$('#editar_fecha_tarea').val()) {
        Swal.fire('Error', 'Debe seleccionar una fecha para la tarea', 'error');
        $('#editar_fecha_tarea').focus();
        return;
    }

    if (!$('#editar_horas_tarea').val() || $('#editar_horas_tarea').val() <= 0) {
        Swal.fire('Error', 'Debe ingresar el número de horas de la tarea', 'error');
        $('#editar_horas_tarea').focus();
        return;
    }

    // Mostrar loading
    Swal.fire({
        title: 'Actualizando tarea...',
        text: 'Por favor espere',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: `/empleado/{{ $empleado->id }}/tareas/${tareaId}/actualizar`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Swal.close();
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                $('#editarTareaModal').modal('hide');
                
                // Recargar el DataTable de tareas
                recargarTareasDataTable();
                
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            
            let errorMessage = 'Error al actualizar la tarea';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                let errorList = '';
                for (const field in errors) {
                    errorList += `<strong>${field}:</strong> ${errors[field].join(', ')}<br>`;
                }
                Swal.fire({
                    title: 'Errores de validación',
                    html: errorList,
                    icon: 'error'
                });
                return;
            }

            Swal.fire('Error', errorMessage, 'error');
        }
    });
}

// Cargar tipos de tarea cuando se abre el modal de edición
$('#editarTareaModal').on('show.bs.modal', function() {
    if ($('#editar_tipo_tarea_id option').length <= 1) {
        cargarTiposTareaParaEdicion();
    }
});

// Limpiar formulario cuando se cierra el modal
$('#editarTareaModal').on('hidden.bs.modal', function() {
    $('#editarTareaForm')[0].reset();
    $('#editar_tarea_id').val('');
});

// FUNCIONES AUXILIARES - TAMBIÉN DEBEN ESTAR EN EL ÁMBITO GLOBAL
function formatFechaCompleta(fecha) {
    if (!fecha) return 'N/A';
    const date = new Date(fecha);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatHorasTarea(horas) {
    if (!horas || horas == 0) return '0h';
    
    const horasEntero = Math.floor(horas);
    const minutos = Math.round((horas - horasEntero) * 60);
    
    let resultado = '';
    if (horasEntero > 0) resultado += `${horasEntero}h`;
    if (minutos > 0) resultado += ` ${minutos}m`;
    
    return resultado || '0h';
}

// Función para obtener badge de prioridad
function getBadgePrioridad(prioridad) {
    const badges = {
        'baja': '<span class="badge badge-success badge-modal">Baja</span>',
        'media': '<span class="badge badge-info badge-modal">Media</span>',
        'alta': '<span class="badge badge-warning badge-modal">Alta</span>',
        'urgente': '<span class="badge badge-danger badge-modal">Urgente</span>'
    };
    return badges[prioridad] || '<span class="badge badge-secondary badge-modal">N/A</span>';
}

// Función para obtener badge de estado (INCLUYE CANCELADA)
function getBadgeEstado(estado) {
    const badges = {
        'pendiente': '<span class="badge badge-secondary badge-modal">Pendiente</span>',
        'en_progreso': '<span class="badge badge-primary badge-modal">En Progreso</span>',
        'completada': '<span class="badge badge-success badge-modal">Completada</span>',
        'cancelada': '<span class="badge badge-danger badge-modal">Cancelada</span>'
    };
    return badges[estado] || '<span class="badge badge-secondary badge-modal">N/A</span>';
}

// Función para formatear fecha simple
function formatFecha(fecha) {
    if (!fecha) return 'N/A';
    const date = new Date(fecha);
    return date.toLocaleDateString('es-ES');
}

// Función para cambiar estado de tarea
window.cambiarEstadoTarea = function(tareaId, nuevoEstado) {
    console.log('🔄 Cambiando estado de tarea:', { tareaId, nuevoEstado });
    
    const empleadoId = {{ $empleado->id }};
    
    // Mostrar confirmación
    Swal.fire({
        title: '¿Cambiar estado?',
        text: `¿Estás seguro de que quieres cambiar el estado a ${nuevoEstado.replace('_', ' ')}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Cambiando estado...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Hacer la petición AJAX
            $.ajax({
                url: `/empleado/${empleadoId}/tareas/${tareaId}/estado`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    estado: nuevoEstado
                },
                success: function(response) {
                    Swal.close();
                    
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Estado actualizado!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Recargar la DataTable de tareas
                        recargarTareasDataTable();
                        
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Error al cambiar el estado'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    console.error('Error cambiando estado:', error);
                    
                    let errorMessage = 'Error al cambiar el estado';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                }
            });
        }
    });
}

// Función para eliminar tarea (solo las creadas por el empleado)
window.eliminarTareaEmpleado = function(tareaId) {
    console.log('🗑️ Empleado eliminando tarea ID:', tareaId);
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer. La tarea será eliminada permanentemente.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Eliminando...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `/empleado/{{ $empleado->id }}/tareas/${tareaId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminada!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        // Recargar el DataTable
                        recargarTareasDataTable();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    Swal.fire('Error', 'Error al eliminar la tarea: ' + error, 'error');
                }
            });
        }
    });
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

.bg-gradient {
    background-image: var(--bs-gradient) !important;
}

/* Para personalizar el gradiente si no te gusta el por defecto */
.modal-header.bg-info.bg-gradient {
    background: linear-gradient(45deg, #17a2b8, #138496) !important;
}

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

@media (min-width: 1200px) {
    body .container, body .container-lg, body .container-md, body .container-sm, body .container-xl {
        max-width: 1900px !important;
    }
}

.bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #138496) !important;
}

/* Agrega esto a tu sección de estilos CSS general */
#verTareaModal .modal-header.bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #138496) !important;
    color: white;
}

#verTareaModal .modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

#verTareaModal .modal-body {
    padding: 25px;
    max-height: 70vh;
    overflow-y: auto;
}

/* Responsive */
@media (max-width: 768px) {
    #verTareaModal .modal-body {
        padding: 15px;
    }
    
    .info-row {
        flex-direction: column;
    }
    
    .info-value {
        text-align: left !important;
        margin-top: 5px;
    }
}
/* Estilos para el modal de edición */
#editarTareaModal .modal-header.bg-warning.bg-gradient {
    background: linear-gradient(45deg, #ffc107, #ff8f00) !important;
    color: white;
}

#editarTareaModal .modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

#editarTareaModal .form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

#editarTareaModal .btn-warning {
    background: linear-gradient(45deg, #ffc107, #ff8f00);
    border: none;
    color: white;
    font-weight: 600;
}

#editarTareaModal .btn-warning:hover {
    background: linear-gradient(45deg, #ff8f00, #ff6f00);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
}

/* Estilos para Air Datepicker personalizado */
.datepicker--cell.-month-:hover,
.datepicker--cell.-year-:hover {
    background: #4361ee;
    color: white;
}

.datepicker--cell.-current- {
    color: #4361ee;
    font-weight: bold;
}

.datepicker--cell.-selected-,
.datepicker--cell.-selected-.-current- {
    background: #4361ee;
    color: white;
}

.datepicker--nav {
    border-bottom: 1px solid #4361ee;
}

.datepicker--nav-title {
    color: #4361ee;
    font-weight: 600;
}

.datepicker--nav-action:hover {
    background: rgba(67, 97, 238, 0.1);
}

/* Input personalizado para el datepicker */
.air-datepicker-input {
    background-color: white;
    cursor: pointer;
    font-weight: 500;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
}

.air-datepicker-input:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}

</style>
@endsection