
@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-tasks mr-2"></i>Gestión de Tareas
                </h1>
                <p class="lead text-muted">Administra y asigna tareas a los empleados</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Tareas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalTareas">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pendientes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="tareasPendientes">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                En Progreso
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="tareasProgreso">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completadas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="tareasCompletadas">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus-circle mr-2"></i>Acciones Rápidas
                    </h6>
                    <div class="btn-group">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tareaModal">
                            <i class="fas fa-plus mr-1"></i> Crear Nueva Tarea
                        </button>
                        <button type="button" class="btn btn-info btn-sm ml-2" data-toggle="modal" data-target="#tipoTareaModal">
                            <i class="fas fa-tag mr-1"></i> Gestionar Tipos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-light py-3">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-filter mr-2"></i>Filtros de Búsqueda
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filterEstado" class="font-weight-bold text-dark">
                                    <i class="fas fa-filter mr-1"></i>Filtrar por Estado:
                                </label>
                                <select class="form-control" id="filterEstado">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en_progreso">En Progreso</option>
                                    <option value="completada">Completada</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filterPrioridad" class="font-weight-bold text-dark">
                                    <i class="fas fa-exclamation-circle mr-1"></i>Filtrar por Prioridad:
                                </label>
                                <select class="form-control" id="filterPrioridad">
                                    <option value="">Todas las prioridades</option>
                                    <option value="baja">Baja</option>
                                    <option value="media">Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filterTipo" class="font-weight-bold text-dark">
                                    <i class="fas fa-tag mr-1"></i>Filtrar por Tipo:
                                </label>
                                <select class="form-control" id="filterTipo">
                                    <option value="">Todos los tipos</option>
                                    <!-- Se llenará dinámicamente -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filterFecha" class="font-weight-bold text-dark">
                                    <i class="fas fa-calendar mr-1"></i>Filtrar por Fecha:
                                </label>
                                <select class="form-control" id="filterFecha">
                                    <option value="">Todas las fechas</option>
                                    <option value="hoy">Hoy</option>
                                    <option value="semana">Esta semana</option>
                                    <option value="mes">Este mes</option>
                                    <option value="proximas">Próximas</option>
                                    <option value="vencidas">Vencidas</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-primary btn-sm" onclick="aplicarFiltrosTareas()">
                                <i class="fas fa-filter mr-1"></i> Aplicar Filtros
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="limpiarFiltrosTareas()">
                                <i class="fas fa-broom mr-1"></i> Limpiar Filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-table mr-2"></i>Lista de Tareas
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="tareasTable" class="table table-hover table-bordered mb-0" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="18%">Título</th>
                                    <th width="10%">Tipo</th>
                                    <th width="8%">Prioridad</th>
                                    <th width="10%">Estado</th>
                                    <th width="12%">Fecha Inicio</th>
                                    <th width="12%">Fecha Fin</th>
                                    <th width="15%">Empleados Asignados</th>
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
@endsection

@section('modals')
<!-- Modal para Crear Tarea -->
<div class="modal fade" id="tareaModal" tabindex="-1" role="dialog" aria-labelledby="tareaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title" id="tareaModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i> Crear Nueva Tarea
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tareaForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="titulo" class="font-weight-bold">Título de la Tarea *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required 
                                       placeholder="Ingrese el título de la tarea">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_tarea_id" class="font-weight-bold">Tipo de Tarea *</label>
                                <select class="form-control select2-tipo" id="tipo_tarea_id" name="tipo_tarea_id" required style="width: 100%;">
                                    <option value="">Seleccione un tipo</option>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion" class="font-weight-bold">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" 
                                  placeholder="Describa los detalles de la tarea..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="prioridad" class="font-weight-bold">Prioridad *</label>
                                <select class="form-control" id="prioridad" name="prioridad" required>
                                    <option value="media">Media</option>
                                    <option value="baja">Baja</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_inicio" class="font-weight-bold">Fecha Inicio *</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_fin" class="font-weight-bold">Fecha Fin *</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="area" class="font-weight-bold">Área/Proyecto</label>
                                <input type="text" class="form-control" id="area" name="area" 
                                       placeholder="Ej: Desarrollo, Marketing...">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hora_inicio" class="font-weight-bold">Hora Inicio</label>
                                <input type="time" class="form-control" id="hora_inicio" name="hora_inicio">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hora_fin" class="font-weight-bold">Hora Fin</label>
                                <input type="time" class="form-control" id="hora_fin" name="hora_fin">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="empleados_asignados" class="font-weight-bold">Empleados Asignados *</label>
                        <select class="form-control select2-empleados" id="empleados_asignados" name="empleados_asignados[]" multiple="multiple" required style="width: 100%;">
                            <!-- Se llenará dinámicamente -->
                        </select>
                        <small class="form-text text-muted">Seleccione uno o más empleados. Use el buscador para encontrar empleados específicos.</small>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle"></i> Los campos marcados con * son obligatorios.
                            La fecha fin debe ser igual o posterior a la fecha inicio.
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" onclick="submitTareaForm()">
                    <i class="fas fa-save mr-1"></i> Crear Tarea
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Gestionar Tipos de Tarea -->
<div class="modal fade" id="tipoTareaModal" tabindex="-1" role="dialog" aria-labelledby="tipoTareaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info text-white">
                <h5 class="modal-title" id="tipoTareaModalLabel">
                    <i class="fas fa-tags mr-2"></i> Gestionar Tipos de Tarea
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-12">
                        <button type="button" class="btn btn-success btn-sm" onclick="mostrarFormTipoTarea()">
                            <i class="fas fa-plus mr-1"></i> Nuevo Tipo de Tarea
                        </button>
                    </div>
                </div>

                <!-- Formulario para crear/editar tipo de tarea (inicialmente oculto) -->
                <div id="formTipoTarea" style="display: none;">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0" id="formTipoTareaTitle">Nuevo Tipo de Tarea</h6>
                        </div>
                        <div class="card-body">
                            <form id="tipoTareaForm">
                                @csrf
                                <input type="hidden" id="tipo_tarea_id" name="id">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nombre_tipo" class="font-weight-bold">Nombre *</label>
                                            <input type="text" class="form-control" id="nombre_tipo" name="nombre" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="color_tipo" class="font-weight-bold">Color *</label>
                                            <input type="color" class="form-control" id="color_tipo" name="color" value="#3498db" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="descripcion_tipo" class="font-weight-bold">Descripción</label>
                                    <textarea class="form-control" id="descripcion_tipo" name="descripcion" rows="2"></textarea>
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="ocultarFormTipoTarea()">
                                        Cancelar
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm" onclick="submitTipoTareaForm()">
                                        Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Lista de tipos de tarea -->
                <div id="listaTiposTarea">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Color</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tiposTareaBody">
                                <!-- Se llenará dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Tarea -->
<div class="modal fade" id="editTareaModal" tabindex="-1" role="dialog" aria-labelledby="editTareaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-warning text-white">
                <h5 class="modal-title" id="editTareaModalLabel">
                    <i class="fas fa-edit mr-2"></i> Editar Tarea
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editTareaForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_tarea_id" name="tarea_id">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="edit_titulo" class="font-weight-bold">Título de la Tarea *</label>
                                <input type="text" class="form-control" id="edit_titulo" name="titulo" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_tipo_tarea_id" class="font-weight-bold">Tipo de Tarea *</label>
                                <select class="form-control select2" id="edit_tipo_tarea_id" name="tipo_tarea_id" required style="width: 100%;">
                                    <!-- Se llenará dinámicamente -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_descripcion" class="font-weight-bold">Descripción</label>
                        <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="edit_prioridad" class="font-weight-bold">Prioridad *</label>
                                <select class="form-control" id="edit_prioridad" name="prioridad" required>
                                    <option value="baja">Baja</option>
                                    <option value="media">Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="edit_estado" class="font-weight-bold">Estado *</label>
                                <select class="form-control" id="edit_estado" name="estado" required>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en_progreso">En Progreso</option>
                                    <option value="completada">Completada</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="edit_fecha_inicio" class="font-weight-bold">Fecha Inicio *</label>
                                <input type="date" class="form-control" id="edit_fecha_inicio" name="fecha_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="edit_fecha_fin" class="font-weight-bold">Fecha Fin *</label>
                                <input type="date" class="form-control" id="edit_fecha_fin" name="fecha_fin" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_hora_inicio" class="font-weight-bold">Hora Inicio</label>
                                <input type="time" class="form-control" id="edit_hora_inicio" name="hora_inicio">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_hora_fin" class="font-weight-bold">Hora Fin</label>
                                <input type="time" class="form-control" id="edit_hora_fin" name="hora_fin">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_area" class="font-weight-bold">Área/Proyecto</label>
                        <input type="text" class="form-control" id="edit_area" name="area">
                    </div>

                    <div class="form-group">
                        <label for="edit_empleados_asignados" class="font-weight-bold">Empleados Asignados</label>
                        <select class="form-control select2-multiple" id="edit_empleados_asignados" name="empleados_asignados[]" multiple="multiple" style="width: 100%;">
                            <!-- Se llenará dinámicamente -->
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-warning" onclick="updateTarea()">
                    <i class="fas fa-save mr-1"></i> Actualizar Tarea
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Tarea - COMPLETO -->

<div class="modal fade" id="viewTareaModal" tabindex="-1" role="dialog" aria-labelledby="viewTareaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info text-white">
                <h5 class="modal-title" id="viewTareaModalLabel">
                    <i class="fas fa-eye mr-2"></i> Detalles de la Tarea
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4 id="view_titulo" class="text-primary mb-2"></h4>
                        <p id="view_descripcion" class="text-muted mb-0"></p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div id="view_prioridad" class="mb-2"></div>
                        <div id="view_estado"></div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-light shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-dark">
                                    <i class="fas fa-calendar-alt mr-2"></i>Información de Fechas
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6 font-weight-bold text-dark">Fecha Inicio:</div>
                                    <div class="col-6 text-right" id="view_fecha_inicio"></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 font-weight-bold text-dark">Fecha Fin:</div>
                                    <div class="col-6 text-right" id="view_fecha_fin"></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 font-weight-bold text-dark">Hora Inicio:</div>
                                    <div class="col-6 text-right" id="view_hora_inicio"></div>
                                </div>
                                <div class="row">
                                    <div class="col-6 font-weight-bold text-dark">Hora Fin:</div>
                                    <div class="col-6 text-right" id="view_hora_fin"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-light shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-dark">
                                    <i class="fas fa-info-circle mr-2"></i>Información Adicional
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6 font-weight-bold text-dark">Tipo de Tarea:</div>
                                    <div class="col-6 text-right" id="view_tipo_tarea"></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 font-weight-bold text-dark">Área/Proyecto:</div>
                                    <div class="col-6 text-right" id="view_area"></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 font-weight-bold text-dark">Creada por:</div>
                                    <div class="col-6 text-right" id="view_creador"></div>
                                </div>
                                <div class="row">
                                    <div class="col-6 font-weight-bold text-dark">Fecha Creación:</div>
                                    <div class="col-6 text-right" id="view_created_at"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-light shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-dark">
                                    <i class="fas fa-users mr-2"></i>Empleados Asignados
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="view_empleados_asignados" class="d-flex flex-wrap gap-2">
                                    <!-- Se llenará dinámicamente -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="editarTareaDesdeVista()">
                    <i class="fas fa-edit mr-1"></i> Editar Tarea
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Gestionar Asignaciones -->
<div class="modal fade" id="asignarEmpleadosModal" tabindex="-1" role="dialog" aria-labelledby="asignarEmpleadosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="asignarEmpleadosModalLabel">
                    <i class="fas fa-user-plus mr-2"></i> Gestionar Asignaciones
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    Selecciona los empleados que deseas asignar a esta tarea. Puedes seleccionar múltiples empleados.
                </div>
                
                <div class="form-group">
                    <label for="empleados_asignacion" class="font-weight-bold text-dark">
                        <i class="fas fa-users mr-1"></i>Empleados Disponibles:
                    </label>
                    <select class="form-control select2-multiple" id="empleados_asignacion" name="empleados[]" multiple="multiple" style="width: 100%;">
                        <!-- Se llenará dinámicamente -->
                    </select>
                </div>

                <div class="card mt-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-dark">
                            <i class="fas fa-list mr-2"></i>Empleados Actualmente Asignados
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="empleados_actuales" class="d-flex flex-wrap gap-2">
                            <!-- Se llenará dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" onclick="guardarAsignaciones()">
                    <i class="fas fa-save mr-1"></i> Guardar Asignaciones
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Confirmación Rápida -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" id="confirmModalHeader">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Acción</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="confirmModalBody">
                ¿Estás seguro de que deseas realizar esta acción?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn" id="confirmModalButton">
                    <i class="fas fa-check mr-1"></i> Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- ************************************************************************************************ JS ******************************************************************************************* -->


@section('scripts')

<script>
// =============================================
// FUNCIONES DE INICIALIZACIÓN Y CARGA DE DATOS
// =============================================

function cargarTiposTarea() {
    console.log('Cargando tipos de tarea...');
    
    $.ajax({
        url: '{{ route("admin.tareas.tipos") }}',
        type: 'GET',
        success: function(response) {
            console.log('Tipos de tarea cargados:', response);
            
            if (response.success && response.data) {
                // Limpiar selects
                $('#filterTipo').empty().append('<option value="">Todos los tipos</option>');
                $('#tipo_tarea_id').empty().append('<option value="">Seleccione un tipo</option>');
                $('#edit_tipo_tarea_id').empty().append('<option value="">Seleccione un tipo</option>');
                
                // Llenar con datos - MOSTRAR DESCRIPCIÓN EN LUGAR DE NOMBRE
                response.data.forEach(function(tipo) {
                    const textoMostrar = tipo.descripcion || tipo.nombre; // Usar descripción si existe, sino nombre
                    $('#filterTipo').append(`<option value="${tipo.id}">${tipo.nombre}</option>`);
                    $('#tipo_tarea_id').append(`<option value="${tipo.id}" title="${tipo.descripcion || ''}">${textoMostrar}</option>`);
                    $('#edit_tipo_tarea_id').append(`<option value="${tipo.id}" title="${tipo.descripcion || ''}">${textoMostrar}</option>`);
                });

                // Inicializar Select2 si está disponible
                if (typeof $.fn.select2 !== 'undefined') {
                    $('#tipo_tarea_id').trigger('change.select2');
                    $('#edit_tipo_tarea_id').trigger('change.select2');
                }
            } else {
                console.error('Error en respuesta de tipos:', response);
                mostrarErrorSelect('tipo_tarea_id', 'Error al cargar tipos de tarea');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error cargando tipos de tarea:', error);
            mostrarErrorSelect('tipo_tarea_id', 'Error de conexión');
        }
    });
}

function cargarEmpleados() {
    console.log('Cargando empleados...');
    
    $.ajax({
        url: '{{ route("admin.tareas.empleados") }}',
        type: 'GET',
        success: function(response) {
            console.log('Empleados cargados:', response);
            
            if (response.success && response.data && response.data.length > 0) {
                // Limpiar selects
                $('#empleados_asignados').empty();
                $('#edit_empleados_asignados').empty();
                $('#empleados_asignacion').empty();
                
                // Llenar con datos
                response.data.forEach(function(empleado) {
                    const optionText = `${empleado.nombre_completo} - ${empleado.dni}`;
                    $('#empleados_asignados').append(`<option value="${empleado.id}">${optionText}</option>`);
                    $('#edit_empleados_asignados').append(`<option value="${empleado.id}">${optionText}</option>`);
                    $('#empleados_asignacion').append(`<option value="${empleado.id}">${optionText}</option>`);
                });

                console.log('Empleados cargados en selects:', response.data.length);
                
                // Inicializar Select2 para empleados (múltiple con buscador)
                if (typeof $.fn.select2 !== 'undefined') {
                    $('#empleados_asignados').select2({
                        placeholder: "Busque y seleccione empleados",
                        allowClear: true,
                        width: '100%',
                        language: 'es',
                        multiple: true
                    });
                    
                    $('#edit_empleados_asignados').select2({
                        placeholder: "Busque y seleccione empleados",
                        allowClear: true,
                        width: '100%',
                        language: 'es',
                        multiple: true
                    });
                    
                    $('#empleados_asignacion').select2({
                        placeholder: "Busque y seleccione empleados",
                        allowClear: true,
                        width: '100%',
                        language: 'es',
                        multiple: true
                    });
                    
                    console.log('Select2 para empleados inicializado');
                }
            } else {
                console.error('No hay empleados disponibles:', response);
                mostrarEmpleadosVacios();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error cargando empleados:', error);
            mostrarEmpleadosVacios();
        }
    });
}

function mostrarEmpleadosVacios() {
    const mensaje = 'No hay empleados disponibles';
    $('#empleados_asignados').empty().append(`<option value="">${mensaje}</option>`);
    $('#edit_empleados_asignados').empty().append(`<option value="">${mensaje}</option>`);
    $('#empleados_asignacion').empty().append(`<option value="">${mensaje}</option>`);
}

function mostrarErrorSelect(selectId, mensaje) {
    $(`#${selectId}`).empty().append(`<option value="">${mensaje}</option>`);
}

// =============================================
// FUNCIONES DE INICIALIZACIÓN DE SELECT2
// =============================================

function inicializarSelect2() {
    // Verificar si Select2 está disponible
    if (typeof $.fn.select2 === 'undefined') {
        console.warn('Select2 no está disponible. Los selects funcionarán de forma nativa.');
        return;
    }
    
    // Inicializar Select2 para tipos de tarea (select simple SIN buscador)
    $('.select2-tipo').select2({
        placeholder: "Seleccione un tipo",
        allowClear: true,
        width: '100%',
        language: 'es',
        minimumResultsForSearch: -1 // OCULTAR BUSCADOR para tipos de tarea
    });

    // Inicializar Select2 para empleados (select múltiple CON buscador)
    $('.select2-empleados').select2({
        placeholder: "Busque y seleccione empleados",
        allowClear: true,
        width: '100%',
        language: 'es',
        multiple: true // MÚLTIPLE CHOICE
    });
    
    console.log('Select2 inicializado correctamente');
}

// =============================================
// FUNCIONES DE GESTIÓN DE ASIGNACIONES
// =============================================

function removerEmpleadoAsignacion(empleadoId) {
    const empleadosActuales = $('#empleados_asignacion').val();
    const nuevosEmpleados = empleadosActuales ? empleadosActuales.filter(id => id != empleadoId) : [];
    
    if (typeof $.fn.select2 !== 'undefined') {
        $('#empleados_asignacion').val(nuevosEmpleados).trigger('change.select2');
    } else {
        $('#empleados_asignacion').val(nuevosEmpleados);
    }
    
    // Actualizar visualización
    $(`.empleado-badge:has(.badge-remove[onclick="removerEmpleadoAsignacion(${empleadoId})"])`).remove();
    
    if (nuevosEmpleados.length === 0) {
        $('#empleados_actuales').html('<span class="text-muted">No hay empleados asignados actualmente</span>');
    }
}

function gestionarAsignaciones(id) {
    window.currentTareaId = id;
    
    // Cargar empleados disponibles
    $.ajax({
        url: '{{ route("admin.tareas.empleados") }}',
        type: 'GET',
        success: function(response) {
            if (response.success && response.data && response.data.length > 0) {
                $('#empleados_asignacion').empty();
                response.data.forEach(function(empleado) {
                    $('#empleados_asignacion').append(
                        `<option value="${empleado.id}">${empleado.nombre_completo} - ${empleado.dni}</option>`
                    );
                });
                
                // Inicializar Select2 si está disponible (múltiple con buscador)
                if (typeof $.fn.select2 !== 'undefined') {
                    $('#empleados_asignacion').select2({
                        placeholder: "Busque y seleccione empleados",
                        allowClear: true,
                        width: '100%',
                        language: 'es',
                        multiple: true
                    });
                }
            } else {
                $('#empleados_asignacion').empty().append('<option value="">No hay empleados disponibles</option>');
            }
        }
    });
    
    // Cargar empleados actualmente asignados
    $.ajax({
        url: `/admin/tareas/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const empleados = response.data.empleados_asignados;
                let html = '';
                
                if (empleados.length > 0) {
                    empleados.forEach(emp => {
                        html += `<span class="empleado-badge">
                            ${emp.nombre_completo}
                            <span class="badge-remove" onclick="removerEmpleadoAsignacion(${emp.id})">
                                <i class="fas fa-times"></i>
                            </span>
                        </span>`;
                    });
                    // Seleccionar empleados en el select
                    const empleadosIds = empleados.map(emp => emp.id);
                    if (typeof $.fn.select2 !== 'undefined') {
                        $('#empleados_asignacion').val(empleadosIds).trigger('change.select2');
                    } else {
                        $('#empleados_asignados').val(empleadosIds);
                    }
                } else {
                    html = '<span class="text-muted">No hay empleados asignados actualmente</span>';
                }
                
                $('#empleados_actuales').html(html);
            }
        }
    });
    
    $('#asignarEmpleadosModal').modal('show');
}

function guardarAsignaciones() {
    const empleadosSeleccionados = $('#empleados_asignacion').val();
    const tareaId = window.currentTareaId;
    
    if (!empleadosSeleccionados || empleadosSeleccionados.length === 0) {
        Swal.fire('Advertencia', 'Debe seleccionar al menos un empleado', 'warning');
        return;
    }
    
    $.ajax({
        url: `/admin/tareas/${tareaId}/asignar`,
        type: 'POST',
        data: {
            empleados: empleadosSeleccionados,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                Swal.fire('¡Éxito!', response.message, 'success');
                $('#asignarEmpleadosModal').modal('hide');
                window.tareasTable.ajax.reload();
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Error', 'Error al guardar las asignaciones', 'error');
        }
    });
}

// =============================================
// FUNCIONES DE GESTIÓN DE TAREAS (CRUD)
// =============================================

function submitTareaForm() {
    // Validar que los selects tengan datos
    if ($('#tipo_tarea_id').val() === '' || $('#tipo_tarea_id').val() === null) {
        Swal.fire('Error', 'Debe seleccionar un tipo de tarea', 'error');
        return;
    }

    const empleadosSeleccionados = $('#empleados_asignados').val();
    if (!empleadosSeleccionados || empleadosSeleccionados.length === 0) {
        Swal.fire('Error', 'Debe seleccionar al menos un empleado', 'error');
        return;
    }

    const formData = new FormData(document.getElementById('tareaForm'));
    
    $.ajax({
        url: '{{ route("admin.tareas.store") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire('¡Éxito!', response.message, 'success');
                $('#tareaModal').modal('hide');
                window.tareasTable.ajax.reload();
                document.getElementById('tareaForm').reset();
                // Limpiar selects múltiples
                if (typeof $.fn.select2 !== 'undefined') {
                    $('#empleados_asignados').val(null).trigger('change.select2');
                } else {
                    $('#empleados_asignados').val(null);
                }
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Error', 'Error al crear la tarea', 'error');
        }
    });
}

function verTarea(id) {
    $.ajax({
        url: `/admin/tareas/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const tarea = response.data.tarea;
                const empleados = response.data.empleados_asignados;
                
                // Llenar modal de vista
                $('#view_titulo').text(tarea.titulo);
                $('#view_descripcion').text(tarea.descripcion || 'Sin descripción');
                $('#view_prioridad').html(getBadgePrioridad(tarea.prioridad));
                $('#view_estado').html(getBadgeEstado(tarea.estado));
                $('#view_fecha_inicio').text(formatFecha(tarea.fecha_inicio));
                $('#view_fecha_fin').text(formatFecha(tarea.fecha_fin));
                $('#view_hora_inicio').text(tarea.hora_inicio || 'No especificada');
                $('#view_hora_fin').text(tarea.hora_fin || 'No especificada');
                
                // Mostrar descripción del tipo de tarea
                const tipoTareaTexto = tarea.tipo_tarea?.descripcion || tarea.tipo_tarea?.nombre || 'N/A';
                $('#view_tipo_tarea').text(tipoTareaTexto);
                
                $('#view_area').text(tarea.area || 'No especificada');
                $('#view_creador').text(tarea.creador_tipo === 'admin' ? 'Administrador' : 'Empleado');
                $('#view_created_at').text(formatFecha(tarea.created_at));
                
                // Llenar empleados asignados
                let empleadosHtml = '';
                if (empleados.length > 0) {
                    empleados.forEach(emp => {
                        empleadosHtml += `<span class="badge badge-primary mr-1 mb-1">${emp.nombre_completo}</span>`;
                    });
                } else {
                    empleadosHtml = '<span class="text-muted">No hay empleados asignados</span>';
                }
                $('#view_empleados_asignados').html(empleadosHtml);
                
                $('#viewTareaModal').modal('show');
            }
        }
    });
}

function editarTarea(id) {
    $.ajax({
        url: `/admin/tareas/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const tarea = response.data.tarea;
                const empleados = response.data.empleados_asignados;
                
                // Llenar formulario de edición
                $('#edit_tarea_id').val(tarea.id);
                $('#edit_titulo').val(tarea.titulo);
                $('#edit_descripcion').val(tarea.descripcion);
                $('#edit_tipo_tarea_id').val(tarea.tipo_tarea_id);
                $('#edit_prioridad').val(tarea.prioridad);
                $('#edit_estado').val(tarea.estado);
                $('#edit_fecha_inicio').val(tarea.fecha_inicio);
                $('#edit_fecha_fin').val(tarea.fecha_fin);
                $('#edit_hora_inicio').val(tarea.hora_inicio);
                $('#edit_hora_fin').val(tarea.hora_fin);
                $('#edit_area').val(tarea.area);
                
                // Seleccionar empleados asignados
                const empleadosIds = empleados.map(emp => emp.id);
                if (typeof $.fn.select2 !== 'undefined') {
                    $('#edit_empleados_asignados').val(empleadosIds).trigger('change.select2');
                    $('#edit_tipo_tarea_id').trigger('change.select2');
                } else {
                    $('#edit_empleados_asignados').val(empleadosIds);
                }
                
                $('#editTareaModal').modal('show');
            }
        }
    });
}

function updateTarea() {
    const formData = new FormData(document.getElementById('editTareaForm'));
    const tareaId = $('#edit_tarea_id').val();
    
    $.ajax({
        url: `/admin/tareas/${tareaId}`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-HTTP-Method-Override': 'PUT'
        },
        success: function(response) {
            if (response.success) {
                Swal.fire('¡Éxito!', response.message, 'success');
                $('#editTareaModal').modal('hide');
                window.tareasTable.ajax.reload();
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Error', 'Error al actualizar la tarea', 'error');
        }
    });
}

function eliminarTarea(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/tareas/${id}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('¡Eliminado!', response.message, 'success');
                        window.tareasTable.ajax.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Error al eliminar la tarea', 'error');
                }
            });
        }
    });
}

// =============================================
// FUNCIONES DE GESTIÓN DE TIPOS DE TAREA
// =============================================

function mostrarFormTipoTarea() {
    $('#formTipoTarea').show();
    $('#formTipoTareaTitle').text('Nuevo Tipo de Tarea');
    document.getElementById('tipoTareaForm').reset();
    $('#tipo_tarea_id').val('');
}

function ocultarFormTipoTarea() {
    $('#formTipoTarea').hide();
}

function submitTipoTareaForm() {
    const formData = new FormData(document.getElementById('tipoTareaForm'));
    const tipoId = $('#tipo_tarea_id').val();
    const url = tipoId ? `/admin/tipos-tarea/${tipoId}` : '{{ route("admin.tipos-tarea.store") }}';
    const method = tipoId ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: method === 'PUT' ? { 'X-HTTP-Method-Override': 'PUT' } : {},
        success: function(response) {
            if (response.success) {
                Swal.fire('¡Éxito!', response.message, 'success');
                ocultarFormTipoTarea();
                cargarTiposTarea();
                cargarListaTiposTarea();
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }
    });
}

function cargarListaTiposTarea() {
    console.log('Cargando lista de tipos de tarea para el modal...');
    
    $.ajax({
        url: '{{ route("admin.tareas.tipos") }}',
        type: 'GET',
        success: function(response) {
            console.log('Lista de tipos cargada:', response);
            
            if (response.success && response.data) {
                let html = '';
                response.data.forEach(tipo => {
                    // Mostrar descripción en la lista también
                    const descripcionMostrar = tipo.descripcion || 'Sin descripción';
                    html += `
                    <tr>
                        <td>${tipo.nombre}</td>
                        <td><span class="badge" style="background-color: ${tipo.color}">${tipo.color}</span></td>
                        <td>${descripcionMostrar}</td>
                        <td>${tipo.activo ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>'}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editarTipoTarea(${tipo.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarTipoTarea(${tipo.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
                $('#tiposTareaBody').html(html);
            } else {
                console.error('Error al cargar lista de tipos:', response);
                $('#tiposTareaBody').html('<tr><td colspan="5" class="text-center text-muted">No hay tipos de tarea disponibles</td></tr>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error cargando lista de tipos:', error);
            $('#tiposTareaBody').html('<tr><td colspan="5" class="text-center text-danger">Error al cargar los tipos de tarea</td></tr>');
        }
    });
}

function editarTipoTarea(id) {
    $.ajax({
        url: `/admin/tipos-tarea/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const tipo = response.data;
                $('#tipo_tarea_id').val(tipo.id);
                $('#nombre_tipo').val(tipo.nombre);
                $('#color_tipo').val(tipo.color);
                $('#descripcion_tipo').val(tipo.descripcion);
                $('#formTipoTareaTitle').text('Editar Tipo de Tarea');
                $('#formTipoTarea').show();
            }
        }
    });
}

function eliminarTipoTarea(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/tipos-tarea/${id}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('¡Eliminado!', response.message, 'success');
                        cargarListaTiposTarea();
                        cargarTiposTarea();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                }
            });
        }
    });
}

// =============================================
// FUNCIONES AUXILIARES
// =============================================

function getBadgePrioridad(prioridad) {
    const badges = {
        'baja': '<span class="badge badge-success">Baja</span>',
        'media': '<span class="badge badge-info">Media</span>',
        'alta': '<span class="badge badge-warning">Alta</span>',
        'urgente': '<span class="badge badge-danger">Urgente</span>'
    };
    return badges[prioridad] || '<span class="badge badge-secondary">N/A</span>';
}

function getBadgeEstado(estado) {
    const badges = {
        'pendiente': '<span class="badge badge-secondary">Pendiente</span>',
        'en_progreso': '<span class="badge badge-primary">En Progreso</span>',
        'completada': '<span class="badge badge-success">Completada</span>',
        'cancelada': '<span class="badge badge-danger">Cancelada</span>'
    };
    return badges[estado] || '<span class="badge badge-secondary">N/A</span>';
}

function formatFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES');
}

function aplicarFiltrosTareas() {
    window.tareasTable.ajax.reload();
}

function limpiarFiltrosTareas() {
    $('#filterEstado').val('');
    $('#filterPrioridad').val('');
    $('#filterTipo').val('');
    $('#filterFecha').val('');
    aplicarFiltrosTareas();
}

// =============================================
// INICIALIZACIÓN PRINCIPAL
// =============================================

$(document).ready(function() {
    console.log('Inicializando módulo de tareas...');
    
    // INICIALIZAR SELECT2 (si está disponible)
    inicializarSelect2();
    
    // CARGAR DATOS INICIALES
    cargarTiposTarea();
    cargarEmpleados();

    // CONFIGURACIÓN DE DATATABLE
    window.tareasTable = $('#tareasTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.tareas.datatable") }}',
            data: function(d) {
                d.estado = $('#filterEstado').val();
                d.prioridad = $('#filterPrioridad').val();
                d.tipo = $('#filterTipo').val();
                d.fecha = $('#filterFecha').val();
            }
        },
        columns: [
            { data: 'id', width: '5%' },
            { 
                data: 'titulo', 
                width: '18%',
                render: function(data, type, row) {
                    return '<span class="font-weight-bold text-dark">' + data + '</span>';
                }
            },
            { 
                data: 'tipo_tarea', 
                width: '10%',
                render: function(data, type, row) {
                    return '<span class="badge badge-light border">' + data + '</span>';
                }
            },
            { data: 'prioridad', width: '8%' },
            { data: 'estado', width: '10%' },
            { 
                data: 'fecha_inicio', 
                width: '12%',
                render: function(data) {
                    return '<span class="text-nowrap">' + data + '</span>';
                }
            },
            { 
                data: 'fecha_fin', 
                width: '12%',
                render: function(data) {
                    return '<span class="text-nowrap">' + data + '</span>';
                }
            },
            { 
                data: 'empleados_asignados', 
                width: '15%',
                render: function(data) {
                    return data || '<span class="text-muted">Sin asignar</span>';
                }
            },
            {
                data: 'acciones', 
                width: '15%',
                orderable: false, 
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return `
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-info btn-sm" onclick="verTarea(${row.id})" title="Ver Detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="editarTarea(${row.id})" title="Editar Tarea">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-success btn-sm" onclick="gestionarAsignaciones(${row.id})" title="Gestionar Asignaciones">
                            <i class="fas fa-users"></i>
                        </button>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="duplicarTarea(${row.id})">
                                    <i class="fas fa-copy mr-2"></i>Duplicar
                                </a>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Cambiar Estado</h6>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="cambiarEstadoTarea(${row.id}, 'pendiente')">
                                    <i class="fas fa-clock mr-2"></i>Pendiente
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="cambiarEstadoTarea(${row.id}, 'en_progreso')">
                                    <i class="fas fa-spinner mr-2"></i>En Progreso
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="cambiarEstadoTarea(${row.id}, 'completada')">
                                    <i class="fas fa-check mr-2"></i>Completada
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="cambiarEstadoTarea(${row.id}, 'cancelada')">
                                    <i class="fas fa-times mr-2"></i>Cancelada
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="eliminarTarea(${row.id})">
                                    <i class="fas fa-trash mr-2"></i>Eliminar
                                </a>
                            </div>
                        </div>
                    </div>`;
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        },
        order: [[0, 'desc']],
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]]
    });

    // EVENT LISTENERS
    $('#tareaModal').on('show.bs.modal', function() {
        // Forzar recarga si es necesario
        if ($('#tipo_tarea_id').find('option').length <= 1) {
            cargarTiposTarea();
        }
        if ($('#empleados_asignados').find('option').length <= 0) {
            cargarEmpleados();
        }
    });
    
    $('#tipoTareaModal').on('show.bs.modal', function() {
        cargarListaTiposTarea();
    });

    $('#tareaModal').on('hidden.bs.modal', function() {
        document.getElementById('tareaForm').reset();
        if (typeof $.fn.select2 !== 'undefined') {
            $('#empleados_asignados').val(null).trigger('change.select2');
        } else {
            $('#empleados_asignados').val(null);
        }
    });

    // Configuración de fecha mínima para fecha_fin
    $('#fecha_inicio, #edit_fecha_inicio').on('change', function() {
        const fechaInicio = $(this).val();
        const targetId = $(this).attr('id') === 'fecha_inicio' ? '#fecha_fin' : '#edit_fecha_fin';
        $(targetId).attr('min', fechaInicio);
    });
});
</script>
@endsection

<!-- ************************************************************************************************ CSS ******************************************************************************************* -->

@section('css')
<style>
/* Estilos personalizados para el módulo de tareas */
.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    border-bottom: 1px solid #e3e6f0;
}

.badge-pill {
    font-size: 0.8rem;
    padding: 0.5rem 0.75rem;
}

/* Colores para prioridades */
.badge-prioridad-baja { background-color: #28a745; color: white; }
.badge-prioridad-media { background-color: #17a2b8; color: white; }
.badge-prioridad-alta { background-color: #ffc107; color: #212529; }
.badge-prioridad-urgente { background-color: #dc3545; color: white; }

/* Colores para estados */
.badge-estado-pendiente { background-color: #6c757d; color: white; }
.badge-estado-en_progreso { background-color: #007bff; color: white; }
.badge-estado-completada { background-color: #28a745; color: white; }
.badge-estado-cancelada { background-color: #dc3545; color: white; }

/* Mejoras para la tabla */
#tareasTable_wrapper {
    padding: 0;
}

#tareasTable thead th {
    border-bottom: 2px solid #e3e6f0;
    font-weight: 600;
    color: #5a5c69;
}

#tareasTable tbody tr:hover {
    background-color: #f8f9fc;
}

/* Estilos para los modales */
.modal-header {
    border-bottom: 1px solid #e3e6f0;
}

.modal-footer {
    border-top: 1px solid #e3e6f0;
}

/* Estilos para los badges de empleados */
.empleado-badge {
    background-color: #e9ecef;
    border: 1px solid #dee2e6;
    color: #495057;
    padding: 0.375rem 0.75rem;
    border-radius: 0.35rem;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    margin: 0.125rem;
}

.empleado-badge .badge-remove {
    margin-left: 0.5rem;
    cursor: pointer;
    color: #6c757d;
}

.empleado-badge .badge-remove:hover {
    color: #dc3545;
}

/* Estilos para los filtros */
.filter-card .card-header {
    background-color: #f8f9fc !important;
}

/* Mejoras para los select2 */
.select2-container--default .select2-selection--multiple {
    border: 1px solid #d1d3e2;
    border-radius: 0.35rem;
}

.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #bac8f3;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

/* Animaciones suaves */
.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive improvements */
@media (max-width: 768px) {
    .btn-group .btn {
        margin-bottom: 0.25rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
}

/* Estilos para las cards de estadísticas */
.card-border-left {
    border-left: 0.25rem solid !important;
}

.card-border-left-primary {
    border-left-color: #4e73df !important;
}

.card-border-left-success {
    border-left-color: #1cc88a !important;
}

.card-border-left-info {
    border-left-color: #36b9cc !important;
}

.card-border-left-warning {
    border-left-color: #f6c23e !important;
}

/* Mejoras visuales para los badges */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.35em 0.65em;
}

/* Estilos para el formulario de tipos de tarea */
.color-preview {
    width: 30px;
    height: 30px;
    border-radius: 4px;
    display: inline-block;
    margin-right: 10px;
    border: 1px solid #ddd;
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Custom scrollbar para modales */
.modal-body {
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 #f1f1f1;
}

.modal-body::-webkit-scrollbar {
    width: 6px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

@media (min-width: 1200px) {
    .container, .container-lg, .container-md, .container-sm, .container-xl {
        max-width: 1800px !important;
    }
}
</style>
@endsection