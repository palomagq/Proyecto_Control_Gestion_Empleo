
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
        <div class="col-md-2 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
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

        <div class="col-md-2 mb-3">
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

        <div class="col-md-2 mb-3">
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

        <div class="col-md-2 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
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

        <!-- ✅ NUEVAS CARDS PARA CREADORES -->
        <div class="col-md-2 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Creadas por Admin
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="tareasAdmin">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Creadas por Empleados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="tareasEmpleados">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="filterEstado" class="font-weight-bold text-dark">
                                    <i class="fas fa-filter mr-1"></i>Estado:
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
                                    <i class="fas fa-exclamation-circle mr-1"></i>Prioridad:
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
                                    <i class="fas fa-tag mr-1"></i>Tipo:
                                </label>
                                <select class="form-control" id="filterTipo">
                                    <option value="">Todos los tipos</option>
                                    <!-- Se llenará dinámicamente -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="filterEmpleados" class="font-weight-bold text-dark">
                                    <i class="fas fa-users mr-1"></i>Empleados:
                                </label>
                                <select class="form-control select2-multiple" id="filterEmpleados" multiple="multiple" style="width: 100%;">
                                    <!-- Se llenará dinámicamente -->
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
                                    <th width="10%">Creador</th>
                                    <th width="12%">Fecha Tarea</th>
                                    <th width="12%">Horas Tarea</th>
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
                                <label for="area" class="font-weight-bold">Área/Proyecto</label>
                                <input type="text" class="form-control" id="area" name="area" 
                                    placeholder="Ej: Desarrollo, Marketing...">
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
                                <label for="edit_fecha_tarea" class="font-weight-bold">Fecha Tarea *</label>
                                <input type="date" class="form-control" id="edit_fecha_tarea" name="fecha_tarea" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="edit_horas_tarea" class="font-weight-bold">Horas de Tarea *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="edit_horas_tarea" name="horas_tarea" 
                                        step="0.25" min="0.25" max="24" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">horas</span>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    0.25 = 15min, 0.5 = 30min, 1.5 = 1h 30min
                                </small>
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
                                    <div class="col-6 font-weight-bold text-dark">Fecha Tarea:</div>
                                    <div class="col-6 text-right" id="view_fecha_tarea"></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 font-weight-bold text-dark">Duración:</div>
                                    <div class="col-6 text-right" id="view_horas_tarea"></div>
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
                                    <div class="col-6 text-right" id="view_creador">
                                        <!-- Se llenará dinámicamente -->
                                    </div>
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
                <!-- Botón para agregar nuevo tipo -->
                <div class="row mb-4">
                    <div class="col-12">
                        <button type="button" class="btn btn-success btn-sm" onclick="mostrarFormTipoTarea()">
                            <i class="fas fa-plus mr-1"></i> Nuevo Tipo de Tarea
                        </button>
                    </div>
                </div>

                <!-- Formulario para crear/editar tipo de tarea (inicialmente oculto) -->
                <div id="formTipoTarea" style="display: none;">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 text-dark" id="formTipoTareaTitle">
                                <i class="fas fa-plus-circle mr-2"></i> Nuevo Tipo de Tarea
                            </h6>
                        </div>
                        <div class="card-body">
                            <form id="tipoTareaForm">
                                @csrf
                                <input type="hidden" id="tipo_tarea_id" name="id">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nombre_tipo" class="font-weight-bold text-dark">
                                                <i class="fas fa-tag mr-1"></i> Nombre del Tipo *
                                            </label>
                                            <input type="text" class="form-control" id="nombre_tipo" name="nombre" 
                                                   required placeholder="Ej: Reunión, Desarrollo, Documentación...">
                                            <small class="form-text text-muted">Nombre único para identificar el tipo de tarea</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="color_tipo" class="font-weight-bold text-dark">
                                                <i class="fas fa-palette mr-1"></i> Color *
                                            </label>
                                            <div class="input-group">
                                                <input type="color" class="form-control" id="color_tipo" name="color" 
                                                       value="#3498db" required style="height: 38px;">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="color_preview" 
                                                          style="background-color: #3498db; width: 40px;"></span>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">Color para identificar visualmente este tipo</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="descripcion_tipo" class="font-weight-bold text-dark">
                                        <i class="fas fa-align-left mr-1"></i> Descripción
                                    </label>
                                    <textarea class="form-control" id="descripcion_tipo" name="descripcion" 
                                              rows="3" placeholder="Describe el propósito de este tipo de tarea..."></textarea>
                                    <small class="form-text text-muted">Esta descripción se mostrará en los selects al crear tareas</small>
                                </div>
                                
                                <div class="alert alert-info py-2">
                                    <small>
                                        <i class="fas fa-info-circle mr-1"></i> 
                                        Los campos marcados con * son obligatorios. La descripción es opcional pero recomendada.
                                    </small>
                                </div>
                                
                                <div class="text-right">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="ocultarFormTipoTarea()">
                                        <i class="fas fa-times mr-1"></i> Cancelar
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm" onclick="submitTipoTareaForm()">
                                        <i class="fas fa-save mr-1"></i> Guardar Tipo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Lista de tipos de tarea existentes -->
                <div id="listaTiposTarea">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 text-dark">
                                <i class="fas fa-list mr-2"></i> Tipos de Tarea Existentes
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="20%">Nombre</th>
                                            <th width="15%">Color</th>
                                            <th width="35%">Descripción</th>
                                            <th width="15%">Estado</th>
                                            <th width="15%" class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tiposTareaBody">
                                        <!-- Se llenará dinámicamente -->
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                                Cargando tipos de tarea...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
            //console.log('Tipos de tarea cargados:', response);
            
            if (response.success && response.data) {
                // Limpiar selects
                $('#filterTipo').empty().append('<option value="">Todos los tipos</option>');
                $('#tipo_tarea_id').empty().append('<option value="">Seleccione un tipo</option>');
                $('#edit_tipo_tarea_id').empty().append('<option value="">Seleccione un tipo</option>');
                
                // ✅ CORREGIDO: Mostrar DESCRIPCIÓN en lugar de nombre
                response.data.forEach(function(tipo) {
                    // Usar la descripción si existe, si no usar el nombre
                    const textoMostrar = tipo.descripcion || tipo.nombre;
                    
                    $('#filterTipo').append(`<option value="${tipo.id}">${textoMostrar}</option>`);
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
    //console.log('Cargando empleados...');
    
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

                //console.log('Empleados cargados en selects:', response.data.length);
                
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
                    
                    //console.log('Select2 para empleados inicializado');
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


function cargarEmpleadosParaFiltros() {
    //console.log('Cargando empleados para filtros...');
    
    $.ajax({
        url: '{{ route("admin.tareas.empleados") }}',
        type: 'GET',
        success: function(response) {
            //console.log('Empleados para filtros cargados:', response);
            
            if (response.success && response.data && response.data.length > 0) {
                // Limpiar select de filtros
                $('#filterEmpleados').empty();
                
                // Llenar con datos
                response.data.forEach(function(empleado) {
                    const optionText = `${empleado.nombre_completo} - ${empleado.dni}`;
                    $('#filterEmpleados').append(`<option value="${empleado.id}">${optionText}</option>`);
                });

                //console.log('Empleados cargados en filtro:', response.data.length);
                
                // ✅ INICIALIZAR SELECT2 DESPUÉS DE CARGAR DATOS
                inicializarSelect2Filtros();
                
            } else {
                console.error('No hay empleados disponibles para filtros:', response);
                $('#filterEmpleados').empty().append('<option value="">No hay empleados disponibles</option>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error cargando empleados para filtros:', error);
            $('#filterEmpleados').empty().append('<option value="">Error al cargar empleados</option>');
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
    
    //console.log('Select2 inicializado correctamente');
}


// =============================================
// FUNCIÓN ESPECÍFICA PARA FILTROS
// =============================================

function inicializarSelect2Filtros() {
    if (typeof $.fn.select2 === 'undefined') {
        console.warn('Select2 no está disponible para filtros');
        return;
    }
    
    // Destruir cualquier instancia previa
    if ($('#filterEmpleados').hasClass('select2-hidden-accessible')) {
        $('#filterEmpleados').select2('destroy');
    }
    
    // Inicializar Select2 para filtro de empleados
    $('#filterEmpleados').select2({
        placeholder: "Seleccione empleados",
        allowClear: true,
        width: '100%',
        language: 'es',
        multiple: true,
        closeOnSelect: false
    });
    
    //console.log('Select2 para filtros inicializado correctamente');
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
                
                // ✅ ACTUALIZAR ESTADÍSTICAS DESPUÉS DE ASIGNAR
                actualizarEstadisticasDespuesDeAccion();

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

    // Validar campos nuevos
    if (!$('#fecha_tarea').val()) {
        Swal.fire('Error', 'Debe seleccionar una fecha para la tarea', 'error');
        return;
    }

    if (!$('#horas_tarea').val() || $('#horas_tarea').val() <= 0) {
        Swal.fire('Error', 'Debe ingresar el número de horas de la tarea', 'error');
        return;
    }

    const formData = new FormData(document.getElementById('tareaForm'));
    
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
        url: '{{ route("admin.tareas.store") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            Swal.close();
            if (response.success) {
                Swal.fire('¡Éxito!', response.message, 'success');
                $('#tareaModal').modal('hide');
                window.tareasTable.ajax.reload();
                actualizarEstadisticasDespuesDeAccion();
                document.getElementById('tareaForm').reset();
                
                if (typeof $.fn.select2 !== 'undefined') {
                    $('#empleados_asignados').val(null).trigger('change.select2');
                }
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

function verTarea(id) {
    console.log('🔍 Solicitando datos de tarea ID:', id);
    
    // ✅ GUARDAR EL ID ACTUAL PARA USAR EN EDICIÓN
    window.tareaActualId = id;
    
    $.ajax({
        url: `/admin/tareas/${id}`,
        type: 'GET',
        success: function(response) {
            console.log('✅ Datos de tarea recibidos:', response);
            
            if (response.success) {
                const tarea = response.data.tarea;
                const empleados = response.data.empleados_asignados;
                
                // Llenar modal de vista con TODOS los datos
                $('#view_titulo').text(tarea.titulo || 'Sin título');
                $('#view_descripcion').text(tarea.descripcion || 'Sin descripción');
                $('#view_prioridad').html(getBadgePrioridad(tarea.prioridad));
                $('#view_estado').html(getBadgeEstado(tarea.estado));
                
                // Mostrar fecha de la tarea
                $('#view_fecha_tarea').text(tarea.fecha_tarea ? formatFecha(tarea.fecha_tarea) : 'No especificada');
                
                // Mostrar horas formateadas
                const horas = parseFloat(tarea.horas_tarea);
                const horasEntero = Math.floor(horas);
                const minutos = Math.round((horas - horasEntero) * 60);
                
                let horasTexto = '';
                if (horasEntero > 0) horasTexto += `${horasEntero}h`;
                if (minutos > 0) horasTexto += ` ${minutos}m`;
                $('#view_horas_tarea').text(horasTexto || '0h');
                
                // Mostrar tipo de tarea
                $('#view_tipo_tarea').text(tarea.tipo_tarea ? (tarea.tipo_tarea.descripcion || tarea.tipo_tarea.nombre) : 'No especificado');
                
                // Mostrar área/proyecto
                $('#view_area').text(tarea.area || 'No especificado');
                
                // Mostrar creador
                 let creadorHtml = '';
                if (tarea.creador_tipo === 'admin') {
                    creadorHtml = '<span class="badge badge-info">Administrador</span>';
                } else if (tarea.creador_tipo === 'empleado' && tarea.empleado_creador) {
                    creadorHtml = `
                        <span class="badge badge-warning">Empleado</span><br>
                        <small>${tarea.empleado_creador.nombre} ${tarea.empleado_creador.apellidos}</small>
                    `;
                } else {
                    creadorHtml = '<span class="text-muted">Desconocido</span>';
                }
                $('#view_creador').html(creadorHtml);
                // Mostrar fecha de creación
                $('#view_created_at').text(tarea.created_at ? formatFecha(tarea.created_at) : 'No disponible');
                
                // Mostrar empleados asignados
                let empleadosHtml = '';
                if (empleados && empleados.length > 0) {
                    empleados.forEach(emp => {
                        empleadosHtml += `
                            <span class="badge badge-primary mr-1 mb-1 p-2">
                                <i class="fas fa-user mr-1"></i>
                                ${emp.nombre_completo}
                            </span>
                        `;
                    });
                } else {
                    empleadosHtml = '<span class="text-muted">No hay empleados asignados</span>';
                }
                $('#view_empleados_asignados').html(empleadosHtml);
                
                // Mostrar el modal
                $('#viewTareaModal').modal('show');
                
            } else {
                console.error('❌ Error en respuesta:', response);
                Swal.fire('Error', response.message || 'No se pudieron cargar los datos de la tarea', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error cargando tarea:', error);
            Swal.fire('Error', 'Error al cargar los datos de la tarea', 'error');
        }
    });
}

function editarTarea(id) {
    console.log('✏️ Editando tarea ID:', id);
    
    $.ajax({
        url: `/admin/tareas/${id}`,
        type: 'GET',
        success: function(response) {
            console.log('✅ Datos para edición recibidos:', response);
            
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
                
                // ✅ CORREGIDO: Mostrar fecha de la tarea en el campo de edición
                if (tarea.fecha_tarea) {
                    // Formatear la fecha para el input type="date" (YYYY-MM-DD)
                    const fecha = new Date(tarea.fecha_tarea);
                    const fechaFormateada = fecha.toISOString().split('T')[0];
                    $('#edit_fecha_tarea').val(fechaFormateada);
                } else {
                    $('#edit_fecha_tarea').val('');
                }
                
                $('#edit_horas_tarea').val(tarea.horas_tarea);
                $('#edit_area').val(tarea.area || '');
                
                // Seleccionar empleados asignados
                const empleadosIds = empleados.map(emp => emp.id);
                if (typeof $.fn.select2 !== 'undefined') {
                    $('#edit_empleados_asignados').val(empleadosIds).trigger('change.select2');
                    $('#edit_tipo_tarea_id').trigger('change.select2');
                } else {
                    $('#edit_empleados_asignados').val(empleadosIds);
                }
                
                console.log('📅 Fecha cargada en edición:', $('#edit_fecha_tarea').val());
                
                $('#editTareaModal').modal('show');
                
            } else {
                console.error('❌ Error en respuesta:', response);
                Swal.fire('Error', response.message || 'No se pudieron cargar los datos para editar', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error cargando tarea para editar:', error);
            Swal.fire('Error', 'Error al cargar los datos para editar', 'error');
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
                // ✅ ACTUALIZAR ESTADÍSTICAS DESPUÉS DE EDITAR
                actualizarEstadisticasDespuesDeAccion();
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
                        // ✅ ACTUALIZAR ESTADÍSTICAS DESPUÉS DE ELIMINAR
                        actualizarEstadisticasDespuesDeAccion();
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

/**
 * Editar tarea desde el modal de vista
 */
function editarTareaDesdeVista() {
    // Cerrar el modal de vista
    $('#viewTareaModal').modal('hide');
    
    // Obtener el ID de la tarea actual (guardado previamente)
    if (window.tareaActualId) {
        console.log('🔄 Abriendo edición desde vista, ID:', window.tareaActualId);
        
        // Pequeño delay para asegurar que el modal de vista se cierre
        setTimeout(() => {
            editarTarea(window.tareaActualId);
        }, 300);
    } else {
        console.error('❌ No se encontró el ID de la tarea actual');
        Swal.fire('Error', 'No se pudo cargar la tarea para editar', 'error');
    }
}

// =============================================
// FUNCIONES DE GESTIÓN DE TIPOS DE TAREA
// =============================================

function mostrarFormTipoTarea() {
    $('#formTipoTarea').show();
    $('#formTipoTareaTitle').html('<i class="fas fa-plus-circle mr-2"></i> Nuevo Tipo de Tarea');
    document.getElementById('tipoTareaForm').reset();
    $('#tipo_tarea_id').val('');
    $('#color_tipo').val('#3498db');
    $('#color_preview').css('background-color', '#3498db');
    
    // Enfocar el primer campo
    setTimeout(() => {
        $('#nombre_tipo').focus();
    }, 300);
}

function ocultarFormTipoTarea() {
    $('#formTipoTarea').hide();
    document.getElementById('tipoTareaForm').reset();
}

// Actualizar preview del color
$('#color_tipo').on('change', function() {
    $('#color_preview').css('background-color', $(this).val());
});

function submitTipoTareaForm() {
    const nombre = $('#nombre_tipo').val().trim();
    const color = $('#color_tipo').val();
    const descripcion = $('#descripcion_tipo').val().trim();

    // Validaciones
    if (!nombre) {
        Swal.fire('Error', 'El nombre del tipo es obligatorio', 'error');
        $('#nombre_tipo').focus();
        return;
    }

    if (!color) {
        Swal.fire('Error', 'Debe seleccionar un color', 'error');
        return;
    }

    const formData = new FormData(document.getElementById('tipoTareaForm'));
    const tipoId = $('#tipo_tarea_id').val();
    const url = tipoId ? `/admin/tipos-tarea/${tipoId}` : '{{ route("admin.tipos-tarea.store") }}';
    const method = tipoId ? 'PUT' : 'POST';

    // Mostrar loading
    const botonGuardar = $('#tipoTareaForm').find('button[type="button"]').filter(':contains("Guardar")');
    const textoOriginal = botonGuardar.html();
    botonGuardar.html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...').prop('disabled', true);

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: method === 'PUT' ? { 'X-HTTP-Method-Override': 'PUT' } : {},
        success: function(response) {
            botonGuardar.html(textoOriginal).prop('disabled', false);
            
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                ocultarFormTipoTarea();
                cargarTiposTarea();
                cargarListaTiposTarea();
                
                // Cerrar modal después de guardar si es creación nueva
                if (!tipoId) {
                    setTimeout(() => {
                        $('#tipoTareaModal').modal('hide');
                    }, 1000);
                }
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            botonGuardar.html(textoOriginal).prop('disabled', false);
            Swal.fire('Error', 'Error al procesar la solicitud', 'error');
        }
    });
}

function cargarListaTiposTarea() {
    console.log('Cargando lista de tipos de tarea...');
    
    $.ajax({
        url: '{{ route("admin.tareas.tipos") }}',
        type: 'GET',
        success: function(response) {
            console.log('Lista de tipos cargada:', response);
            
            if (response.success && response.data && response.data.length > 0) {
                let html = '';
                response.data.forEach(tipo => {
                    const descripcionMostrar = tipo.descripcion || '<span class="text-muted">Sin descripción</span>';
                    const estadoBadge = tipo.activo ? 
                        '<span class="badge badge-success">Activo</span>' : 
                        '<span class="badge badge-danger">Inactivo</span>';
                    
                    html += `
                    <tr>
                        <td class="align-middle">
                            <strong>${tipo.nombre}</strong>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <span class="badge-color mr-2" style="background-color: ${tipo.color}; width: 20px; height: 20px; border-radius: 3px; display: inline-block;"></span>
                                <small class="text-muted">${tipo.color}</small>
                            </div>
                        </td>
                        <td class="align-middle">${descripcionMostrar}</td>
                        <td class="align-middle">${estadoBadge}</td>
                        <td class="align-middle text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-warning btn-sm" onclick="editarTipoTarea(${tipo.id})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarTipoTarea(${tipo.id})" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
                });
                $('#tiposTareaBody').html(html);
            } else {
                $('#tiposTareaBody').html(`
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-inbox mr-2"></i>
                            No hay tipos de tarea registrados
                        </td>
                    </tr>
                `);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error cargando lista de tipos:', error);
            $('#tiposTareaBody').html(`
                <tr>
                    <td colspan="5" class="text-center text-danger py-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Error al cargar los tipos de tarea
                    </td>
                </tr>
            `);
        }
    });
}


function editarTipoTarea(id) {
    console.log('Editando tipo de tarea:', id);
    
    $.ajax({
        url: `/admin/tipos-tarea/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const tipo = response.data;
                
                $('#tipo_tarea_id').val(tipo.id);
                $('#nombre_tipo').val(tipo.nombre);
                $('#color_tipo').val(tipo.color);
                $('#descripcion_tipo').val(tipo.descripcion || '');
                $('#color_preview').css('background-color', tipo.color);
                
                $('#formTipoTareaTitle').html('<i class="fas fa-edit mr-2"></i> Editar Tipo de Tarea');
                $('#formTipoTarea').show();
                
                // Scroll al formulario
                $('html, body').animate({
                    scrollTop: $('#formTipoTarea').offset().top - 20
                }, 500);
                
                // Enfocar el primer campo
                setTimeout(() => {
                    $('#nombre_tipo').focus();
                }, 300);
                
            } else {
                Swal.fire('Error', 'No se pudo cargar el tipo de tarea', 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Error', 'Error al cargar el tipo de tarea', 'error');
        }
    });
}


function eliminarTipoTarea(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción desactivará el tipo de tarea. Las tareas existentes mantendrán este tipo.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, desactivar',
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
                url: `/admin/tipos-tarea/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}' // Asegurar token CSRF
                },
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Desactivado!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        cargarListaTiposTarea();
                        cargarTiposTarea(); // Actualizar selects
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    console.error('Error en DELETE:', xhr.responseText);
                    
                    if (xhr.status === 419) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de sesión',
                            text: 'El token de seguridad ha expirado. Por favor, recarga la página e intenta nuevamente.',
                            confirmButtonText: 'Recargar página'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire('Error', 'Error al eliminar el tipo de tarea: ' + error, 'error');
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
    //console.log('🔍 getBadgeEstado recibió:', estado, 'tipo:', typeof estado);
    
    if (!estado) {
        console.warn('❌ Estado vacío o undefined');
        return '<span class="badge badge-secondary">N/A</span>';
    }
    
    const estadoStr = estado.toString().toLowerCase().trim();
    //console.log('🔍 Estado procesado:', estadoStr);
    
    const badges = {
        'pendiente': '<span class="badge badge-secondary">Pendiente</span>',
        'en_progreso': '<span class="badge badge-primary">En Progreso</span>',
        'en progreso': '<span class="badge badge-primary">En Progreso</span>',
        'completada': '<span class="badge badge-success">Completada</span>',
        'completado': '<span class="badge badge-success">Completada</span>',
        'cancelada': '<span class="badge badge-danger">Cancelada</span>',
        'cancelado': '<span class="badge badge-danger">Cancelada</span>'
    };
    
    const resultado = badges[estadoStr] || '<span class="badge badge-secondary">N/A</span>';
    //console.log('✅ Badge generado para', estadoStr, ':', resultado);
    
    return resultado;
}

function aplicarFiltrosTareas() {
    //console.log('🔄 Aplicando filtros...');
    
    // Obtener valores de los filtros
    const filtros = {
        estado: $('#filterEstado').val(),
        prioridad: $('#filterPrioridad').val(),
        tipo: $('#filterTipo').val(),
        empleados: $('#filterEmpleados').val() || []
    };
    
    //console.log('Filtros aplicados:', filtros);
    
    // Validar rango de fechas
    if (filtros.fecha_inicio && filtros.fecha_fin) {
        const fechaInicio = new Date(filtros.fecha_inicio);
        const fechaFin = new Date(filtros.fecha_fin);
        
        if (fechaInicio > fechaFin) {
            Swal.fire('Error', 'La fecha de inicio no puede ser mayor que la fecha fin', 'error');
            return;
        }
    }
    
    // Recargar DataTable con los filtros
    window.tareasTable.ajax.reload();
}

function limpiarFiltrosTareas() {
    //console.log('🧹 Limpiando filtros...');
    
    // Limpiar todos los filtros
    $('#filterEstado').val('');
    $('#filterPrioridad').val('');
    $('#filterTipo').val('');
    
    // Limpiar select2 múltiple
    if (typeof $.fn.select2 !== 'undefined') {
        $('#filterEmpleados').val(null).trigger('change.select2');
    } else {
        $('#filterEmpleados').val(null);
    }
    
    // Recargar DataTable sin filtros
    window.tareasTable.ajax.reload();
    
    Swal.fire({
        icon: 'success',
        title: 'Filtros limpiados',
        text: 'Todos los filtros han sido restablecidos',
        timer: 1500,
        showConfirmButton: false
    });
}

/**
 * Cargar estadísticas iniciales
 */
/*function cargarEstadisticasIniciales() {
    console.log('📊 Cargando estadísticas iniciales...');
    actualizarEstadisticas();
}


function actualizarEstadisticas() {
    console.log('🔄 Actualizando estadísticas...');
    
    // Mostrar estado de carga
    $('#totalTareas').html('<i class="fas fa-spinner fa-spin"></i>');
    $('#tareasPendientes').html('<i class="fas fa-spinner fa-spin"></i>');
    $('#tareasProgreso').html('<i class="fas fa-spinner fa-spin"></i>');
    $('#tareasCompletadas').html('<i class="fas fa-spinner fa-spin"></i>');

    $.ajax({
        url: '{{ route("admin.tareas.estadisticas") }}',
        type: 'GET',
        success: function(response) {
            console.log('✅ Estadísticas recibidas:', response);
            
            if (response.success && response.data) {
                const stats = response.data;
                
                // Actualizar los valores en las cards
                $('#totalTareas').text(stats.total || 0);
                $('#tareasPendientes').text(stats.pendientes || 0);
                $('#tareasProgreso').text(stats.en_progreso || 0);
                $('#tareasCompletadas').text(stats.completadas || 0);
                
                // Agregar animación de actualización
                $('.card-body .h5').addClass('text-success');
                setTimeout(() => {
                    $('.card-body .h5').removeClass('text-success');
                }, 1000);
                
            } else {
                console.error('❌ Error en respuesta de estadísticas:', response);
                mostrarEstadisticasPorDefecto();
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error cargando estadísticas:', error);
            mostrarEstadisticasPorDefecto();
            
            // Mostrar notificación de error
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar las estadísticas',
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
}*/





/**
 * Calcular estadísticas desde los datos visibles en la DataTable
 */
function calcularEstadisticasDesdeDataTable() {
    try {
        if (!window.tareasTable) {
            //console.log('❌ DataTable no disponible para estadísticas');
            mostrarEstadisticasPorDefecto();
            return;
        }

        // Obtener datos VISIBLES (con filtros aplicados)
        const datos = window.tareasTable.rows({ filter: 'applied' }).data();
        //console.log('🔍 Calculando estadísticas con', datos.length, 'tareas visibles');
        
        let total = 0;
        let pendientes = 0;
        let enProgreso = 0;
        let completadas = 0;
        let creadasPorAdmin = 0;
        let creadasPorEmpleados = 0;

        // Contar por estado y creador
        for (let i = 0; i < datos.length; i++) {
            const tarea = datos[i];
            total++;

            // ✅ CORREGIDO: Obtener el estado de forma más robusta
            let estado = '';
            
            // Intentar diferentes formas de obtener el estado
            if (tarea.estado) {
                estado = tarea.estado.toString().toLowerCase().trim();
            } else if (tarea.estado_raw) {
                estado = tarea.estado_raw.toString().toLowerCase().trim();
            } else {
                // Buscar en todos los campos
                for (let key in tarea) {
                    if (typeof tarea[key] === 'string' && 
                        (tarea[key].includes('Pendiente') || 
                         tarea[key].includes('Progreso') || 
                         tarea[key].includes('Completada'))) {
                        estado = tarea[key].toLowerCase().trim();
                        break;
                    }
                }
            }

            //console.log(`Tarea ${tarea.id}: Estado detectado = "${estado}"`);

            // ✅ CONTAR POR ESTADO - CORREGIDO
            if (estado.includes('pendiente')) {
                pendientes++;
            } else if (estado.includes('progreso')) {
                enProgreso++;
            } else if (estado.includes('completada')) {
                completadas++;
            } else {
                console.warn(`❓ Estado no reconocido: "${estado}" para tarea ${tarea.id}`);
                // Por defecto contar como pendiente
                pendientes++;
            }

            // ✅ CONTAR POR CREADOR (esto ya funciona bien)
            if (tarea.creador_info) {
                const html = tarea.creador_info.toLowerCase();
                
                if (html.includes('admin') || html.includes('administrador') || html.includes('badge-info')) {
                    creadasPorAdmin++;
                } else if (html.includes('empleado') || html.includes('creada por:') || html.includes('badge-warning')) {
                    creadasPorEmpleados++;
                } else {
                    creadasPorAdmin++;
                }
            } else {
                creadasPorAdmin++;
            }
        }

        console.log('📊 ESTADÍSTICAS FINALES:', {
            total,
            pendientes,
            enProgreso, 
            completadas,
            creadasPorAdmin,
            creadasPorEmpleados
        });

        // ✅ VERIFICAR INTEGRIDAD
        const sumaEstados = pendientes + enProgreso + completadas;
        if (total !== sumaEstados) {
            console.warn(`⚠️ Discrepancia: total=${total}, sumaEstados=${sumaEstados}`);
            // Ajustar para evitar inconsistencias
            const diferencia = total - sumaEstados;
            if (diferencia > 0) {
                pendientes += diferencia; // Asignar la diferencia a pendientes
                //console.log(`🔧 Ajustado: +${diferencia} a pendientes`);
            }
        }

        // Actualizar UI
        actualizarUIEstadisticasCompletas(
            total, 
            pendientes, 
            enProgreso, 
            completadas,
            creadasPorAdmin,
            creadasPorEmpleados
        );

    } catch (error) {
        console.error('❌ Error crítico en cálculo de estadísticas:', error);
        mostrarEstadisticasPorDefecto();
    }
}


function actualizarUIEstadisticasCompletas(total, pendientes, enProgreso, completadas, creadasPorAdmin, creadasPorEmpleados, porcentajeAdmin, porcentajeEmpleados) {
    // Estadísticas principales
    $('#totalTareas').text(total);
    $('#tareasPendientes').text(pendientes);
    $('#tareasProgreso').text(enProgreso);
    $('#tareasCompletadas').text(completadas);
    
    // ✅ NUEVO: Estadísticas de creadores
    $('#tareasAdmin').text(creadasPorAdmin);
    $('#tareasEmpleados').text(creadasPorEmpleados);

    // Animación de actualización
    $('.card-body .h5').addClass('text-success');
    setTimeout(() => {
        $('.card-body .h5').removeClass('text-success');
    }, 1000);
    
    // Log para debugging
    console.log('✅ UI actualizada con estadísticas completas');
}

function actualizarUIEstadisticas(total, pendientes, enProgreso, completadas) {
    $('#totalTareas').text(total);
    $('#tareasPendientes').text(pendientes);
    $('#tareasProgreso').text(enProgreso);
    $('#tareasCompletadas').text(completadas);

    // Animación de actualización
    $('.card-body .h5').addClass('text-success');
    setTimeout(() => {
        $('.card-body .h5').removeClass('text-success');
    }, 1000);
}

/**
 * Mostrar valores por defecto
 */
function mostrarEstadisticasPorDefecto() {
    $('#totalTareas').text('0');
    $('#tareasPendientes').text('0');
    $('#tareasProgreso').text('0');
    $('#tareasCompletadas').text('0');
    $('#tareasAdmin').text('0');
    $('#tareasEmpleados').text('0');
}
/**
 * Actualizar estadísticas después de acciones importantes
 */
function actualizarEstadisticasDespuesDeAccion() {
    console.log('🔄 Actualizando estadísticas después de acción...');
    
    // Pequeño delay para asegurar que la base de datos se actualizó
    setTimeout(() => {
        calcularEstadisticasDesdeDataTable();
    }, 500);
}

function formatFecha(fecha) {
    if (!fecha) return 'N/A';
    const date = new Date(fecha);
    return date.toLocaleDateString('es-ES');
}


/**
 * Cambiar estado de una tarea
 */
function cambiarEstadoTarea(id, nuevoEstado) {
    console.log(`🔄 Cambiando estado de tarea ${id} a: ${nuevoEstado}`);
    
    const estados = {
        'pendiente': { texto: 'Pendiente', color: 'secondary', icono: 'clock' },
        'en_progreso': { texto: 'En Progreso', color: 'primary', icono: 'spinner' },
        'completada': { texto: 'Completada', color: 'success', icono: 'check' },
        'cancelada': { texto: 'Cancelada', color: 'danger', icono: 'times' }
    };
    
    const estadoInfo = estados[nuevoEstado];
    
    if (!estadoInfo) {
        console.error('❌ Estado no válido:', nuevoEstado);
        Swal.fire('Error', 'Estado no válido', 'error');
        return;
    }

    Swal.fire({
        title: `¿Cambiar estado a "${estadoInfo.texto}"?`,
        text: "El estado de la tarea será actualizado inmediatamente",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: `#${nuevoEstado === 'pendiente' ? '6c757d' : nuevoEstado === 'en_progreso' ? '007bff' : nuevoEstado === 'completada' ? '28a745' : 'dc3545'}`,
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Sí, cambiar a ${estadoInfo.texto}`,
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `/admin/tareas/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT',
                        estado: nuevoEstado
                    },
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr) {
                        reject('Error al cambiar el estado');
                    }
                });
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.value && result.value.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Estado actualizado!',
                    text: `La tarea ahora está en estado: ${estadoInfo.texto}`,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Recargar la tabla y actualizar estadísticas
                window.tareasTable.ajax.reload(null, false); // false para mantener la página actual
                actualizarEstadisticasDespuesDeAccion();
                
            } else {
                Swal.fire('Error', result.value?.message || 'Error al cambiar el estado', 'error');
            }
        }
    });
}


/**
 * Duplicar una tarea
 */
function duplicarTarea(id) {
    console.log('📋 Duplicando tarea ID:', id);
    
    Swal.fire({
        title: '¿Duplicar tarea?',
        text: "Se creará una copia idéntica de esta tarea",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, duplicar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `/admin/tareas/${id}/duplicar`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr) {
                        reject('Error al duplicar la tarea');
                    }
                });
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.value && result.value.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Tarea duplicada!',
                    text: result.value.message || 'La tarea ha sido duplicada correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Recargar la tabla y actualizar estadísticas
                window.tareasTable.ajax.reload();
                actualizarEstadisticasDespuesDeAccion();
                
            } else {
                Swal.fire('Error', result.value?.message || 'Error al duplicar la tarea', 'error');
            }
        }
    });
}


function cargarEmpleadosConectados() {
    console.log('Cargando empleados conectados...');
    
    $.ajax({
        url: '/admin/empleados/conectados', // Ahora esta ruta debería funcionar
        type: 'GET',
        success: function(response) {
            console.log('Empleados conectados cargados:', response);
            
            if (response.success && response.data && response.data.length > 0) {
                // Limpiar selects
                $('#empleados_asignados').empty();
                $('#edit_empleados_asignados').empty();
                $('#empleados_asignacion').empty();
                
                // Llenar con datos de empleados conectados
                response.data.forEach(function(empleado) {
                    const optionText = `${empleado.nombre_completo} - ${empleado.dni} ${empleado.estado_badge} (${empleado.tiempo_conectado})`;
                    
                    $('#empleados_asignados').append(`<option value="${empleado.id}">${optionText}</option>`);
                    $('#edit_empleados_asignados').append(`<option value="${empleado.id}">${optionText}</option>`);
                    $('#empleados_asignacion').append(`<option value="${empleado.id}">${optionText}</option>`);
                });

                console.log('Empleados conectados cargados:', response.data.length);
                
                // Mostrar notificación de éxito
                mostrarNotificacionConexion(response.data.length, true);
                
            } else {
                console.warn('No hay empleados conectados disponibles');
                mostrarNotificacionConexion(0, false);
                //cargarTodosLosEmpleadosComoFallback();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error cargando empleados conectados:', error);
            mostrarNotificacionConexion(0, true, true);
            //cargarTodosLosEmpleadosComoFallback();
        }
    });
}

function mostrarNotificacionConexion(total, esConectado, esError = false) {
    $('#estado-conexion-notification').remove();
    
    let mensaje, tipo;
    
    if (esError) {
        mensaje = '⚠️ Usando lista completa de empleados (error en conexiones)';
        tipo = 'warning';
    } else if (esConectado) {
        mensaje = `🟢 ${total} empleados conectados disponibles`;
        tipo = 'success';
    } else {
        mensaje = '🔴 No hay empleados conectados. Usando lista completa.';
        tipo = 'warning';
    }
    
    const alerta = $(`
        <div id="estado-conexion-notification" class="alert alert-${tipo} alert-dismissible fade show mt-2">
            <i class="fas fa-${esConectado ? 'check-circle' : esError ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
            ${mensaje}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);
    
    $('#empleados_asignados').closest('.form-group').after(alerta);
}

function cargarTodosLosEmpleados() {
    console.log('Fallback: cargando todos los empleados...');
    
    $.ajax({
        url: '{{ route("admin.tareas.empleados") }}',
        type: 'GET',
        success: function(response) {
            if (response.success && response.data && response.data.length > 0) {
                // Limpiar selects
                $('#empleados_asignados').empty();
                $('#edit_empleados_asignados').empty();
                $('#empleados_asignacion').empty();
                
                // Llenar con todos los empleados
                response.data.forEach(function(empleado) {
                    const optionText = `${empleado.nombre_completo} - ${empleado.dni} ${empleado.estado_badge}`;
                    
                    $('#empleados_asignados').append(`<option value="${empleado.id}">${optionText}</option>`);
                    $('#edit_empleados_asignados').append(`<option value="${empleado.id}">${optionText}</option>`);
                    $('#empleados_asignacion').append(`<option value="${empleado.id}">${optionText}</option>`);
                });

                // Inicializar Select2
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
                }
                
                mostrarEstadisticasConexion(response.data.length, false);
            } else {
                mostrarEmpleadosConectadosVacios();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en fallback de empleados:', error);
            mostrarEmpleadosConectadosVacios();
        }
    });
}

function mostrarEstadisticasConexion(totalConectados, soloConectados = true) {
    const mensaje = soloConectados 
        ? `🟢 ${totalConectados} empleados conectados disponibles`
        : `📋 ${totalConectados} empleados totales disponibles`;
    
    // Mostrar notificación
    $('#estado-conexion-empleados').remove();
    
    const alerta = $(`
        <div id="estado-conexion-empleados" class="alert alert-info alert-dismissible fade show mt-2">
            <i class="fas fa-info-circle mr-2"></i>
            ${mensaje}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);
    
    $('#empleados_asignados').closest('.form-group').after(alerta);
}

function mostrarEmpleadosConectadosVacios() {
    const mensaje = '❌ No hay empleados conectados disponibles en este momento';
    $('#empleados_asignados').empty().append(`<option value="">${mensaje}</option>`);
    $('#edit_empleados_asignados').empty().append(`<option value="">${mensaje}</option>`);
    $('#empleados_asignacion').empty().append(`<option value="">${mensaje}</option>`);
    
    $('#estado-conexion-empleados').remove();
    const alerta = $(`
        <div id="estado-conexion-empleados" class="alert alert-warning alert-dismissible fade show mt-2">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            ${mensaje}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);
    
    $('#empleados_asignados').closest('.form-group').after(alerta);
}


function cargarEmpleadosConectadosParaFiltros() {
    console.log('Cargando empleados conectados para filtros...');
    
    $.ajax({
        url: '{{ route("admin.empleados.conectados") }}',
        type: 'GET',
        success: function(response) {
            //console.log('Empleados para filtros cargados:', response);
            
            if (response.success && response.data && response.data.length > 0) {
                // Limpiar select de filtros
                $('#filterEmpleados').empty();
                
                // Llenar con datos
                response.data.forEach(function(empleado) {
                    const optionText = `${empleado.nombre_completo} - ${empleado.dni}`;
                    $('#filterEmpleados').append(`<option value="${empleado.id}">${optionText}</option>`);
                });

                //console.log('Empleados cargados en filtro:', response.data.length);
                
                inicializarSelect2Filtros();
                
            } else {
                console.warn('No hay empleados disponibles para filtros');
                // Fallback para filtros
                $.ajax({
                    url: '{{ route("admin.tareas.empleados") }}',
                    type: 'GET',
                    success: function(fallbackResponse) {
                        if (fallbackResponse.success && fallbackResponse.data) {
                            $('#filterEmpleados').empty();
                            fallbackResponse.data.forEach(function(empleado) {
                                const optionText = `${empleado.nombre_completo} - ${empleado.dni}`;
                                $('#filterEmpleados').append(`<option value="${empleado.id}">${optionText}</option>`);
                            });
                            inicializarSelect2Filtros();
                        } else {
                            $('#filterEmpleados').empty().append('<option value="">No hay empleados disponibles</option>');
                        }
                    },
                    error: function() {
                        $('#filterEmpleados').empty().append('<option value="">Error al cargar empleados</option>');
                    }
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error cargando empleados para filtros:', error);
            // Fallback para filtros
            $.ajax({
                url: '{{ route("admin.tareas.empleados") }}',
                type: 'GET',
                success: function(fallbackResponse) {
                    if (fallbackResponse.success && fallbackResponse.data) {
                        $('#filterEmpleados').empty();
                        fallbackResponse.data.forEach(function(empleado) {
                            const optionText = `${empleado.nombre_completo} - ${empleado.dni}`;
                            $('#filterEmpleados').append(`<option value="${empleado.id}">${optionText}</option>`);
                        });
                        inicializarSelect2Filtros();
                    } else {
                        $('#filterEmpleados').empty().append('<option value="">No hay empleados disponibles</option>');
                    }
                },
                error: function() {
                    $('#filterEmpleados').empty().append('<option value="">Error al cargar empleados</option>');
                }
            });
        }
    });
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
    cargarEmpleadosConectados();

    // ✅ NUEVO: CARGAR EMPLEADOS PARA FILTROS
    cargarEmpleadosConectadosParaFiltros();

    // CONFIGURACIÓN DE DATATABLE
    window.tareasTable = $('#tareasTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.tareas.datatable") }}',
            type: 'GET',
            data: function(d) {
                 return {
                    draw: d.draw,
                    start: d.start,
                    length: d.length,
                    search: {
                        value: d.search.value,
                        regex: d.search.regex
                    },
                    order: d.order,
                    columns: d.columns,
                    estado: $('#filterEstado').val(),
                    prioridad: $('#filterPrioridad').val(),
                    tipo: $('#filterTipo').val(),
                    empleados: $('#filterEmpleados').val() || [], // ✅ NUEVO: Filtro múltiple de empleados
                };
            },
            error: function(xhr, error, thrown) {
                console.error('Error DataTable:', xhr.responseJSON);
                
                let errorMsg = 'Error al cargar los datos de la tabla';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }
                
                // Mostrar error en la tabla
                const tbody = $('#tareasTable tbody');
                tbody.html(`
                    <tr>
                        <td colspan="9" class="text-center text-danger py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                            <h6>Error al cargar los datos</h6>
                            <p class="small mb-2">${errorMsg}</p>
                            <button class="btn btn-sm btn-primary" onclick="window.tareasTable.ajax.reload()">
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
                searchable: true
            },
            { 
                data: 'titulo', 
                name: 'titulo',
                width: '18%',
                render: function(data, type, row) {
                    return '<span class="font-weight-bold text-dark">' + (data || 'Sin título') + '</span>';
                },
                searchable: true
            },
            { 
                data: 'tipo_tarea', 
                name: 'tipoTarea.descripcion',
                width: '10%',
                render: function(data, type, row) {
                    return data ?  data + '</span>' : '<span class="text-muted">N/A</span>';
                },
                searchable: true
            },
            { 
                data: 'prioridad', 
                name: 'prioridad',
                width: '8%',
                orderable: false,
                searchable: true
            },
            { 
                data: 'estado', 
                name: 'estado',
                width: '8%',
                orderable: false,
                searchable: true,
                render: function(data, type, row) {
                    // ✅ AHORA 'data' es el string crudo: 'pendiente', 'en_progreso', etc.
                    return getBadgeEstado(data);
                }
            },
            { 
                data: 'creador_info', 
                name: 'creador_tipo', 
                width: '10%', 
                orderable: false 
            },
            { 
                data: 'fecha_tarea', 
                name: 'fecha_tarea',
                width: '8%',
                orderable: true,
                searchable: true
            },
            { 
                data: 'horas_tarea', 
                name: 'horas_tarea',
                width: '8%',
                orderable: true,
                searchable: false,
                className: 'text-center'
            },
            { 
                data: 'empleados_asignados', 
                name: 'empleados.nombre_completo',
                width: '20%',
                orderable: false,
                render: function(data) {
                    return data || '<span class="text-muted">Sin asignar</span>';
                },
                searchable: false
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
                        <button class="btn btn-danger btn-sm" onclick="eliminarTarea(${row.id})" title="Eliminar Tarea">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn btn-success btn-sm" onclick="gestionarAsignaciones(${row.id})" title="Gestionar Asignaciones">
                            <i class="fas fa-users"></i>
                        </button>
                         <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" title="Acciones Rápidas">
                                <i class="fas fa-clone"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item text-info" href="javascript:void(0)" onclick="duplicarTarea(${row.id})">
                                    <i class="fas fa-copy mr-2"></i>Duplicar Tarea
                                </a>
                            </div>
                        </div> 
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" title="Cambiar Estado">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <h6 class="dropdown-header">Cambiar Estado</h6>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="cambiarEstadoTarea(${row.id}, 'pendiente')">
                                    <i class="fas fa-clock mr-2 text-secondary"></i>Pendiente
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="cambiarEstadoTarea(${row.id}, 'en_progreso')">
                                    <i class="fas fa-spinner mr-2 text-primary"></i>En Progreso
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="cambiarEstadoTarea(${row.id}, 'completada')">
                                    <i class="fas fa-check mr-2 text-success"></i>Completada
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="cambiarEstadoTarea(${row.id}, 'cancelada')">
                                    <i class="fas fa-times mr-2 text-danger"></i>Cancelada
                                </a>
                            </div>
                        </div>
                    </div>`;
                }
            }
        ],
        language: {
            "url": "{{ asset('js/datatables/Spanish.json') }}"
        },
        order: [[0, 'asc']],
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        searchDelay: 500, // Delay de 500ms para búsqueda
        // ✅ CONFIGURACIÓN SIMPLIFICADA PARA ESTADÍSTICAS
        drawCallback: function(settings) {
            console.log('🔄 DataTable redibujado');
            // Calcular estadísticas después de que la tabla se renderice
            setTimeout(() => {
                calcularEstadisticasDesdeDataTable();
            }, 300);
        },
        initComplete: function(settings, json) {
            console.log('✅ DataTable inicializado correctamente');
            // Calcular estadísticas iniciales
            setTimeout(() => {
                calcularEstadisticasDesdeDataTable();
            }, 500);
        }
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

    // Limpiar formulario al cerrar el modal
    $('#tipoTareaModal').on('hidden.bs.modal', function() {
        ocultarFormTipoTarea();
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

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    border-bottom: none !important;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%) !important;
    border-bottom: none !important;
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
    /*color: #5a5c69;*/
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
/* Estilos para el modal de tipos de tarea */
.badge-color {
    border: 1px solid #dee2e6;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.modal-content {
    border: none;
    border-radius: 0.5rem;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
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

#filterFechaFin{
    margin-left: 0.5rem;
}

select.form-control[multiple], 
select.form-control[size] {
    height: auto !important;
}

@media (min-width: 1200px) {
    .container, .container-lg, .container-md, .container-sm, .container-xl {
        max-width: 1800px !important;
    }
}

div.dataTables_wrapper div.dataTables_filter {
    text-align: right;
    margin-right: 0.5rem !important;
    margin-top: 0.5rem !important;
}

.pagination {
    display: -ms-flexbox;
    display: flex;
    padding-right: 0.5rem !important;
    list-style: none;
    border-radius: .25rem;
}

div.dataTables_wrapper div.dataTables_info {
    padding-top: .85em;
    margin-right: 15rem !important;
}
div.dataTables_length #tareasTable_length {
    text-align: left;
    margin-left: 2rem;
}

div.dataTables_wrapper div.dataTables_length label {
    float: left;
    margin-left: 2rem;
    margin-top: 0.5rem;
}

@media (max-width: 767px) {
    
    div.dataTables_wrapper div.dataTables_info {
        padding-top: .85em;
        margin-right: 0 !important;
    }

    div.dataTables_wrapper div.dataTables_length label {
         float: none; 
         margin-top: 0.5rem;
    }
    div.dataTables_wrapper div.dataTables_filter {
         text-align: center; 
    }
}

@media (min-width: 768px) and (max-width: 991px){
    
    div.dataTables_wrapper div.dataTables_info {
        padding-top: .85em;
        margin-right: 0 !important;
        margin-left:1.5rem;
    }

    div.dataTables_wrapper div.dataTables_length label {
         float: none; 
         margin-top: 0.5rem !important;
         margin-right: 2rem;
    }

    .col-auto {
        width: -webkit-fill-available !important;
    }
}

@media (min-width: 992px){
    
    div.dataTables_wrapper div.dataTables_info {
        padding-top: .85em;
        margin-right: 0 !important;
    }
}

</style>
@endsection