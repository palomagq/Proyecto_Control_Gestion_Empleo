@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-users mr-2"></i>Gestión de Empleados
                </h1>
                <p class="lead text-muted d-none d-md-block">Administra y gestiona los empleados del sistema</p>            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-3 mb-lg-4"> <!-- Margen responsivo -->
        <div class="col-12 col-md-4 mb-3 mb-md-0"> <!-- Columnas responsivas -->
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Empleados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalEmpleados">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-lg fa-2x text-gray-300"></i> <!-- Icono responsivo -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 mb-3 mb-md-0">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Registros este Mes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="registrosMes">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-lg fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Promedio de Edad
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="promedioEdad">0 años</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-lg fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Card -->
    <div class="row mb-3 mb-lg-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white py-2 py-lg-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center"> <!-- Flex responsivo -->
                    <h6 class="m-0 font-weight-bold text-primary mb-2 mb-md-0">
                        <i class="fas fa-plus-circle mr-2"></i>Acciones Rápidas
                    </h6>
                    <div class="btn-group w-100 w-md-auto"> <!-- Ancho responsivo -->
                        <button type="button" class="btn btn-success btn-sm btn-lg-md w-50 w-md-auto" data-toggle="modal" data-target="#employeeModal">
                            <i class="fas fa-user-plus mr-1"></i> <span class="d-none d-md-inline">Crear Nuevo Empleado</span><span class="d-md-none">Nuevo</span>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm btn-lg-md w-100 w-md-auto ml-0 ml-md-2" 
                                data-toggle="modal" data-target="#exportExcelModal">
                            <i class="fas fa-file-excel mr-1"></i> 
                            <span class="d-none d-md-inline">Exportar Excel</span>
                            <span class="d-md-none">Excel</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="row mb-3 mb-lg-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-light py-2 py-lg-3">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-filter mr-2"></i>Filtros de Búsqueda
                    </h6>
                </div>
                <div class="card-body p-2 p-lg-3"> <!-- Padding responsivo -->
                    <div class="row">
                        <div class="col-12 col-md-4 mb-2 mb-md-0"> <!-- Stack en móvil -->
                            <div class="form-group mb-1 mb-lg-2">
                                <label for="filterDni" class="font-weight-bold text-dark small small-lg"> <!-- Texto más pequeño en móvil -->
                                    <i class="fas fa-id-card mr-1"></i>Filtrar por DNI:
                                </label>
                                <input type="text" class="form-control form-control-sm form-control-lg-md" id="filterDni" placeholder="Ej: 12345678A"> <!-- Control responsivo -->
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                            <div class="form-group mb-1 mb-lg-2">
                                <label for="filterNombre" class="font-weight-bold text-dark small small-lg">
                                    <i class="fas fa-user mr-1"></i>Filtrar por Nombre:
                                </label>
                                <input type="text" class="form-control form-control-sm form-control-lg-md" id="filterNombre" placeholder="Buscar por nombre...">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group mb-1 mb-lg-2">
                                <label for="filterMes" class="font-weight-bold text-dark small small-lg">
                                    <i class="fas fa-calendar-alt mr-1"></i>Filtrar por Mes Completo:
                                </label>
                                <input type="text" class="form-control form-control-sm form-control-lg-md" id="filterMes" 
                                       placeholder="Seleccione un mes">
                                <small class="form-text text-muted d-none d-md-block">Se filtrará del día 1 al último día del mes</small> <!-- Ocultar en móvil -->
                            </div>
                        </div>
                    </div>

                    <!-- Información del filtro aplicado -->
                    <div class="row" id="filtroInfo" style="display: none;">
                        <div class="col-md-12">
                            <div class="alert alert-info py-1 py-lg-2 mb-0 small"> <!-- Padding y texto responsivo -->
                                <i class="fas fa-info-circle"></i> 
                                Filtrando por mes completo: <strong id="infoMes"></strong>
                                <button type="button" class="btn btn-sm btn-outline-info ml-2 d-none d-md-inline-block" onclick="limpiarFiltroMes()">
                                    <i class="fas fa-times"></i> Limpiar filtro de mes
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info ml-1 d-md-none" onclick="limpiarFiltroMes()" title="Limpiar filtro">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-2 py-lg-3">
                    <div class="row">
                        <div class="col-12 text-center text-md-right"> <!-- Centrar en móvil -->
                            <button type="button" class="btn btn-primary btn-sm btn-lg-md mb-1 mb-md-0 w-100 w-md-auto" onclick="aplicarFiltros()">
                                <i class="fas fa-filter mr-1"></i> Aplicar Filtros
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm btn-lg-md w-100 w-md-auto ml-0 ml-md-2" onclick="limpiarFiltros()">
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
                <div class="card-header bg-white py-2 py-lg-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-table mr-2"></i>Lista de Empleados
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="empleadosTable" class="table table-hover table-bordered mb-0 display responsive nowrap" style="width:100%"> <!-- Clase responsive -->
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%" class="all">ID</th> <!-- Clase 'all' para mostrar siempre -->
                                    <th width="8%" class="min-tablet"><i class="fas fa-id-card mr-1"></i> DNI</th> <!-- Ocultar en móvil -->
                                    <th width="15%" class="all">Nombre</th>
                                    <th width="15%" class="min-tablet">Apellidos</th>
                                    <th width="8%" class="min-desktop">Fecha Nac.</th> <!-- Ocultar en tablets pequeñas -->
                                    <th width="8%" class="all">Edad</th>
                                    <th width="18%" class="min-tablet">Domicilio</th>
                                    <th width="8%" class="min-tablet">Telefono</th>
                                    <th width="8%" class="min-desktop">Username</th>
                                    <th width="15%" class="all">Acciones</th>
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
<!-- Modal para Crear Empleado -->
<div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title" id="employeeModalLabel">
                    <i class="fas fa-user-plus mr-2"></i> Crear Nuevo Empleado
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="employeeForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre" class="font-weight-bold">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required 
                                       placeholder="Ingrese el nombre">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellidos" class="font-weight-bold">Apellidos *</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required 
                                       placeholder="Ingrese los apellidos">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dni" class="font-weight-bold">DNI *</label>
                                <input type="text" class="form-control" id="dni" name="dni" required 
                                       placeholder="Ej: 12345678A" maxlength="9" oninput="generarUsername()">
                                <small class="form-text text-muted">Formato: 8 números + 1 letra (ej: 12345678A)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_nacimiento" class="font-weight-bold">Fecha de Nacimiento *</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required 
                                       max="<?php echo date('Y-m-d', strtotime('-16 years')); ?>">
                                <small class="form-text text-muted">Debe ser mayor de 16 años</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono" class="font-weight-bold">Teléfono *</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" required 
                                       placeholder="Ej: +34 612 345 678" pattern="[+]?[0-9\s\-]+">
                                <small class="form-text text-muted">Formato internacional: +34 612 345 678</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="domicilio" class="font-weight-bold">Domicilio *</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="domicilio" name="domicilio" required 
                                placeholder="Calle, número, ciudad, código postal">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-primary" onclick="geocodificarDireccion()" id="btn-geocodificar">
                                    <i class="fas fa-search-location"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            Comience a escribir la dirección y seleccione una de las opciones sugeridas.
                        </small>
                    </div>

                    <!-- Campos ocultos para coordenadas CON VALORES POR DEFECTO -->
                    <input type="hidden" id="latitud" name="latitud" value="40.4168">
                    <input type="hidden" id="longitud" name="longitud" value="-3.7038">

                    <!-- Mapa -->
                    <div class="form-group">
                        <label class="font-weight-bold">Ubicación en el Mapa</label>
                        <div id="map" style="height: 300px; width: 100%; border-radius: 5px; border: 1px solid #ddd;"></div>
                        <small class="form-text text-muted" id="coordenadas-info">
                            <i class="fas fa-info-circle"></i> Coordenadas: No especificadas
                        </small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username" class="font-weight-bold">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" required 
                                    placeholder="Se generará automáticamente del DNI" readonly>
                                <small class="form-text text-muted">Se genera automáticamente a partir del DNI (sin letra)</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password-display" class="font-weight-bold">Contraseña Automática *</label>
                                <input type="text" class="form-control" id="password-display" 
                                    placeholder="Se generará automáticamente" readonly
                                    style="background-color: #f8f9fa; border: 1px solid #e3e6f0;">
                                <small class="form-text text-muted">Contraseña generada con los primeros 4 dígitos del DNI</small>
                            </div>
                        </div>
                    </div>
                 <!-- NUEVA SECCIÓN: Vista previa del QR -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-qrcode mr-2"></i> Código QR del Empleado
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <div id="qr-preview" class="mb-3">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            El código QR se generará automáticamente al crear el empleado
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        Este QR identificará al empleado en el sistema y podrá ser escaneado para acceder a su información
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle"></i> Todos los campos marcados con * son obligatorios.
                            El DNI y Username deben ser únicos en el sistema. Todos los usuarios creados tendrán el rol de Empleado.
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success btn-lg" onclick="submitEmployeeForm()">
                    <i class="fas fa-save mr-1"></i> Crear Empleado
                </button>
            </div>
        </div>
    </div>
</div>



<!-- Modal para Editar Empleado -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-warning text-white">
                <h5 class="modal-title" id="editEmployeeModalLabel">
                    <i class="fas fa-edit mr-2"></i> Editar Empleado
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editEmployeeForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información del empleado (solo lectura) -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_nombre" class="font-weight-bold">Nombre</label>
                                <input type="text" class="form-control bg-light" id="edit_nombre" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_apellidos" class="font-weight-bold">Apellidos</label>
                                <input type="text" class="form-control bg-light" id="edit_apellidos" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_dni" class="font-weight-bold">DNI</label>
                                <input type="text" class="form-control bg-light" id="edit_dni" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_fecha_nacimiento" class="font-weight-bold">Fecha de Nacimiento</label>
                                <input type="text" class="form-control bg-light" id="edit_fecha_nacimiento" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_username" class="font-weight-bold">Username</label>
                                <input type="text" class="form-control bg-light" id="edit_username" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_edad" class="font-weight-bold">Edad</label>
                                <input type="text" class="form-control bg-light" id="edit_edad" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Campo editable: Domicilio -->
                    <div class="form-group">
                        <label for="edit_domicilio" class="font-weight-bold">Domicilio *</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="edit_domicilio" name="domicilio" required 
                                placeholder="Calle, número, ciudad, código postal">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-primary" onclick="geocodificarDireccionEdit()" id="btn-geocodificar-edit">
                                    <i class="fas fa-search-location"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            Este es el único campo editable. Comience a escribir la dirección y seleccione una de las opciones sugeridas.
                        </small>
                    </div>

                    <!-- Campos ocultos para coordenadas -->
                    <input type="hidden" id="edit_latitud" name="latitud">
                    <input type="hidden" id="edit_longitud" name="longitud">
                    <input type="hidden" id="edit_empleado_id" name="empleado_id">

                    <!-- Mapa para edición -->
                    <div class="form-group">
                        <label class="font-weight-bold">Ubicación en el Mapa</label>
                        <div id="edit_map" style="height: 250px; width: 100%; border-radius: 5px; border: 1px solid #ddd;"></div>
                        <small class="form-text text-muted" id="edit_coordenadas-info">
                            <i class="fas fa-info-circle"></i> Coordenadas: No especificadas
                        </small>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle"></i> Solo el campo de domicilio puede ser editado. 
                            Los demás campos son informativos y no pueden modificarse.
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-warning btn-lg" onclick="updateEmployee()">
                    <i class="fas fa-save mr-1"></i> Actualizar Empleado
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Eliminar Empleado -->
<div class="modal fade" id="deleteEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger text-white">
                <h5 class="modal-title" id="deleteEmployeeModalLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Eliminar Empleado
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                        <h4 class="alert-heading">¿Está seguro que desea eliminar este empleado?</h4>
                    </div>
                    
                    <div class="employee-info-card card border-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title text-danger" id="delete_employee_name"></h5>
                            <div class="row text-left">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>DNI:</strong> <span id="delete_employee_dni"></span></p>
                                    <p class="mb-1"><strong>Username:</strong> <span id="delete_employee_username"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Edad:</strong> <span id="delete_employee_age"></span></p>
                                    <p class="mb-1"><strong>Domicilio:</strong> <span id="delete_employee_address"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            <strong>Esta acción no se puede deshacer.</strong> 
                            Se eliminarán todos los datos del empleado, incluyendo sus credenciales de acceso.
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger btn-lg" onclick="confirmDeleteEmployee()">
                    <i class="fas fa-trash mr-1"></i> Sí, Eliminar
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal para Ver Empleado -->
<div class="modal fade" id="viewEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="viewEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info text-white">
                <h5 class="modal-title" id="viewEmployeeModalLabel">
                    <i class="fas fa-eye mr-2"></i> Detalles del Empleado
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Información Personal -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-user-circle mr-2 text-primary"></i>Información Personal
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-4 font-weight-bold text-muted">ID:</div>
                                    <div class="col-8" id="view_id">-</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 font-weight-bold text-muted">Nombre:</div>
                                    <div class="col-8" id="view_nombre">-</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 font-weight-bold text-muted">Apellidos:</div>
                                    <div class="col-8" id="view_apellidos">-</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 font-weight-bold text-muted">DNI:</div>
                                    <div class="col-8">
                                        <span class="badge badge-primary" id="view_dni">-</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 font-weight-bold text-muted">Fecha Nac.:</div>
                                    <div class="col-8" id="view_fecha_nacimiento">-</div>
                                </div>
                                <div class="row">
                                    <div class="col-4 font-weight-bold text-muted">Edad:</div>
                                    <div class="col-8">
                                        <span class="badge badge-info" id="view_edad">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Cuenta -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-key mr-2 text-warning"></i>Información de Cuenta
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-4 font-weight-bold text-muted">Username:</div>
                                    <div class="col-8">
                                        <code class="bg-light p-1 rounded" id="view_username">-</code>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 font-weight-bold text-muted">Estado:</div>
                                    <div class="col-8">
                                        <span class="badge badge-success" id="view_estado">Activo</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 font-weight-bold text-muted">Rol:</div>
                                    <div class="col-8">
                                        <span class="badge badge-secondary" id="view_rol">Empleado</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 font-weight-bold text-muted">Registrado:</div>
                                    <div class="col-8" id="view_created_at">-</div>
                                </div>
                                <div class="row">
                                    <div class="col-4 font-weight-bold text-muted">Actualizado:</div>
                                    <div class="col-8" id="view_updated_at">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Domicilio y Ubicación -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-map-marker-alt mr-2 text-danger"></i>Domicilio y Ubicación
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-3 font-weight-bold text-muted">Domicilio:</div>
                                            <div class="col-9" id="view_domicilio">-</div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-3 font-weight-bold text-muted">Coordenadas:</div>
                                            <div class="col-9">
                                                <small class="text-muted" id="view_coordenadas">-</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="abrirEnGoogleMaps()">
                                            <i class="fas fa-external-link-alt mr-1"></i> Abrir en Maps
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Mapa de visualización -->
                                <div id="view_map" style="height: 250px; width: 100%; border-radius: 5px; border: 1px solid #ddd; background-color: #f8f9fa;">
                                    <div class="h-100 d-flex align-items-center justify-content-center text-muted">
                                        <div class="text-center">
                                            <i class="fas fa-map fa-2x mb-2"></i>
                                            <p>Cargando mapa...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-bar mr-2 text-success"></i>Información Adicional
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3 mb-3">
                                        <div class="border rounded p-3">
                                            <i class="fas fa-calendar-day fa-2x text-primary mb-2"></i>
                                            <h5 id="view_dias_registro">0</h5>
                                            <small class="text-muted">Días registrado</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="border rounded p-3">
                                            <i class="fas fa-birthday-cake fa-2x text-warning mb-2"></i>
                                            <h5 id="view_proximo_cumple">-</h5>
                                            <small class="text-muted">Próximo cumpleaños</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="border rounded p-3">
                                            <i class="fas fa-map-marked-alt fa-2x text-info mb-2"></i>
                                            <h5 id="view_region">-</h5>
                                            <small class="text-muted">Región</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="border rounded p-3">
                                            <i class="fas fa-clock fa-2x text-secondary mb-2"></i>
                                            <h5 id="view_ultima_actualizacion">-</h5>
                                            <small class="text-muted">Última actualización</small>
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
                    <i class="fas fa-times mr-1"></i> Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="imprimirDetalles()">
                    <i class="fas fa-print mr-1"></i> Imprimir
                </button>
                <button type="button" class="btn btn-warning" id="btnEditarDesdeVista">
                    <i class="fas fa-edit mr-1"></i> Editar
                </button>
            </div>
        </div>
    </div>
</div>



<!-- Modal para Exportar Excel -->
<div class="modal fade" id="exportExcelModal" tabindex="-1" role="dialog" aria-labelledby="exportExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title" id="exportExcelModalLabel">
                    <i class="fas fa-file-excel mr-2"></i> Exportar a Excel
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="exportExcelForm">
                    @csrf
                    <div class="form-group">
                        <label for="export_mes" class="font-weight-bold">
                            <i class="fas fa-calendar-alt mr-1"></i> Seleccionar Mes y Año *
                        </label>
                        <input type="text" class="form-control" id="export_mes" name="export_mes" 
                               placeholder="Seleccione el mes a exportar" required>
                        <small class="form-text text-muted">
                            Seleccione el mes y año para exportar los empleados registrados en ese período
                        </small>
                    </div>
                    
                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            Se exportará un archivo Excel con todos los empleados registrados en el mes seleccionado, 
                            incluyendo información completa de cada empleado.
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" onclick="confirmarExportacion()">
                    <i class="fas fa-file-excel mr-1"></i> Generar Excel
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- jQuery completo (NO slim) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<!-- Bootstrap (asegúrate de que esté después de jQuery) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places" async defer></script>



<!-- ******************************************** JS ****************************************************  -->


<script>

let table;

// Esperar a que jQuery esté completamente cargado
$(document).ready(function() {
    console.log('✅ jQuery cargado, versión:', $.fn.jquery);
    initializeDataTable();
    loadStats(); // Cargar estadísticas al inicio

});

function initializeDataTable() {
    console.log('🔄 Inicializando DataTable...');
    
    if (!$.fn.DataTable) {
        console.error('❌ DataTables no está cargado');
        return;
    }
    
    // Destruir instancia anterior si existe
    if ($.fn.DataTable.isDataTable('#empleadosTable')) {
        $('#empleadosTable').DataTable().destroy();
        $('#empleadosTable tbody').empty();
    }
    
    table = $('#empleadosTable').DataTable({
        //processing: true,
        serverSide: false, // ✅ IMPORTANTE: Cambiar a false
        responsive: true,
        language: {
            "url": "{{ asset('js/datatables/Spanish.json') }}"
        },
        ajax: {
            url: '{{ route("admin.empleados.datatable") }}',
            type: 'GET',
            dataSrc: 'data', // ✅ Especificar que los datos están en 'data'
            error: function(xhr, error, thrown) {
                console.error('❌ Error cargando DataTable:', error);
                console.error('Response:', xhr.responseText);
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'dni', name: 'dni' },
            { data: 'nombre', name: 'nombre' },
            { data: 'apellidos', name: 'apellidos' },
            { data: 'fecha_nacimiento', name: 'fecha_nacimiento' },
            { data: 'edad', name: 'edad', orderable: false, searchable: false },
            { data: 'domicilio', name: 'domicilio' },            
            { data: 'telefono', name: 'telefono' },
            { data: 'username', name: 'username' },
            { 
                data: 'acciones', 
                name: 'acciones', 
                orderable: false, 
                searchable: false,
                className: 'text-center'
            }
        ],
        order: [[0, 'asc']],
        pageLength: 10,
        drawCallback: function(settings) {
            console.log('📊 DataTable actualizado');
            // Actualizar estadísticas después de cargar datos
            setTimeout(updateStats, 500);
        },
        initComplete: function(settings, json) {
            console.log('✅ DataTable inicializado correctamente');
            console.log('Datos recibidos:', json);
        }
    });
}


document.addEventListener("DOMContentLoaded", function() {
    // Fecha de nacimiento con restricción de +16 años
    // ✅ CORREGIDO: Fecha de nacimiento con restricción exacta de +16 años
    flatpickr("#fecha_nacimiento", {
        dateFormat: "d-m-Y",
        maxDate: new Date(new Date().setFullYear(new Date().getFullYear() - 16)), // Exactamente 16 años atrás
        locale: "es",
        errorHandler: function(error) {
            console.log('Error de fecha:', error);
        }
    });

    // Selector de mes completo (mantener igual)
    flatpickr("#filterMes", {
        plugins: [
            new monthSelectPlugin({
                shorthand: true,
                dateFormat: "m-Y",
                altFormat: "F Y",
                theme: "material_blue"
            })
        ],
        locale: "es"
    });
    // Limpiar el modal cuando se cierra
    $('#employeeModal').on('hidden.bs.modal', function () {
        document.getElementById('employeeForm').reset();
        document.getElementById('username').value = '';
        document.getElementById('password-display').value = '';
        
        // Remover clases de validación
        const inputs = document.querySelectorAll('#employeeForm input');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
    });

    const passwordInput = document.getElementById('password-display');
    if (passwordInput) {
        passwordInput.addEventListener('input', validarPassword4Digitos);
    }
});

// Agregar event listeners para validación en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    // Validar DNI en tiempo real
    document.getElementById('dni').addEventListener('input', function() {
        validarDNI();
        generarUsername();
        generarQRPreview(); // ✅ NUEVO: Generar preview del QR
    });
    
    // Validar coordenadas cuando cambien
    document.getElementById('latitud').addEventListener('change', validarCoordenadas);
    document.getElementById('longitud').addEventListener('change', validarCoordenadas);
    
    // Validar dirección
    document.getElementById('domicilio').addEventListener('blur', function() {
        if (this.value.trim().length < 10) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
});


// Agregar event listener para validación en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('telefono').addEventListener('input', validarTelefono);
    document.getElementById('telefono').addEventListener('blur', validarTelefono);
});

function generarUsername() {
    const dniInput = document.getElementById('dni');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password-display');
    
    // Extraer solo los números del DNI
    const soloNumeros = dniInput.value.replace(/[^0-9]/g, '');
    
    // Generar username (primeros 8 números)
    if (soloNumeros.length >= 8) {
        usernameInput.value = soloNumeros.substring(0, 8);
    } else {
        usernameInput.value = soloNumeros;
    }
    
    // ✅ CONTRASEÑA DE 4 DÍGITOS EXACTOS
    if (soloNumeros.length >= 4) {
        passwordInput.value = soloNumeros.substring(0, 4); // Exactamente 4 dígitos
    } else if (soloNumeros.length > 0) {
        // Si no hay 4 dígitos, completar con ceros
        passwordInput.value = soloNumeros.padEnd(4, '0');
    } else {
        passwordInput.value = '';
    }
    
    validarDNI();
     // ✅ NUEVO: Generar preview del QR automáticamente
    generarQRPreview()
}

// ✅ FUNCIÓN MEJORADA: Validación de edad exacta de 16 años
function validarEdadMinima() {
    const fechaNacimientoInput = document.getElementById('fecha_nacimiento');
    const fechaNacimiento = new Date(fechaNacimientoInput.value);
    const hoy = new Date();
    
    if (!fechaNacimientoInput.value) {
        fechaNacimientoInput.classList.add('is-invalid');
        return { valido: false, mensaje: 'La fecha de nacimiento es requerida' };
    }
    
    // Calcular edad exacta
    let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
    const mes = hoy.getMonth() - fechaNacimiento.getMonth();
    const dia = hoy.getDate() - fechaNacimiento.getDate();
    
    // Ajustar si aún no ha cumplido años este año
    if (mes < 0 || (mes === 0 && dia < 0)) {
        edad--;
    }
    
    console.log('📅 Validación de edad:', {
        fechaNacimiento: fechaNacimientoInput.value,
        hoy: hoy.toISOString().split('T')[0],
        edadCalculada: edad,
        mesDiferencia: mes,
        diaDiferencia: dia
    });
    
    if (edad < 16) {
        fechaNacimientoInput.classList.add('is-invalid');
        return { 
            valido: false, 
            mensaje: `El empleado debe tener al menos 16 años. Edad calculada: ${edad} años. 
                     Faltan ${16 - edad} años para cumplir 16.` 
        };
    }
    
    fechaNacimientoInput.classList.remove('is-invalid');
    fechaNacimientoInput.classList.add('is-valid');
    return { valido: true, edad: edad };
}

// ✅ FUNCIÓN MEJORADA: Validación de DNI más robusta
function validarDNI() {
    const dniInput = document.getElementById('dni');
    const dni = dniInput.value.trim().toUpperCase();
    
    // Expresión regular para validar DNI español (8 números + 1 letra)
    const dniRegex = /^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i;
    
    if (dni.length === 9) {
        if (!dniRegex.test(dni)) {
            dniInput.classList.add('is-invalid');
            showDNIHelp('Formato incorrecto. Use: 8 números + 1 letra', 'error');
            return false;
        }
        
        // Validar letra del DNI
        const numero = dni.substring(0, 8);
        const letra = dni.substring(8, 9).toUpperCase();
        const letrasValidas = 'TRWAGMYFPDXBNJZSQVHLCKE';
        const letraCalculada = letrasValidas[numero % 23];
        
        if (letra !== letraCalculada) {
            dniInput.classList.add('is-invalid');
            showDNIHelp(`Letra incorrecta. La letra debería ser: ${letraCalculada}`, 'error');
            return false;
        }
        
        dniInput.classList.remove('is-invalid');
        dniInput.classList.add('is-valid');
        showDNIHelp('DNI válido', 'success');
        return true;
    } else {
        dniInput.classList.remove('is-valid');
        if (dni.length > 0) {
            dniInput.classList.add('is-invalid');
            showDNIHelp('El DNI debe tener 9 caracteres', 'error');
        } else {
            dniInput.classList.remove('is-invalid');
            showDNIHelp('Ingrese 8 números + 1 letra', 'info');
        }
        return false;
    }
}

// Función para mostrar ayuda del DNI
function showDNIHelp(message, type) {
    let helpElement = document.getElementById('dniHelp');
    if (!helpElement) {
        helpElement = document.createElement('small');
        helpElement.id = 'dniHelp';
        helpElement.className = 'form-text';
        document.getElementById('dni').parentNode.appendChild(helpElement);
    }
    
    helpElement.textContent = message;
    helpElement.className = `form-text text-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'muted'}`;
}

// Función auxiliar para obtener nombres legibles de campos
function getFieldName(field) {
    const fieldNames = {
        'nombre': 'Nombre',
        'apellidos': 'Apellidos',
        'dni': 'DNI',
        'fecha_nacimiento': 'Fecha de Nacimiento',
        'domicilio': 'Domicilio',
        'username': 'Username',
        'password': 'Contraseña',
        'latitud': 'Latitud',
        'longitud': 'Longitud'
    };
    
    return fieldNames[field] || field;
}

// ✅ FUNCIÓN PARA GENERAR USERNAME ALTERNATIVO
function generarUsernameAlternativo() {
    const dniBase = document.getElementById('dni').value.replace(/[^0-9]/g, '').substring(0, 8);
    let username = dniBase;
    let counter = 1;
    
    // Buscar un username disponible
    function verificarUsername(user) {
        return fetch(`/admin/empleados/verificar-username/${user}`)
            .then(response => response.json())
            .then(data => !data.exists);
    }
    
    function generarYVerificar() {
        const testUsername = counter === 1 ? username : `${username}${counter}`;
        
        verificarUsername(testUsername).then(disponible => {
            if (disponible) {
                document.getElementById('username').value = testUsername;
                Swal.fire({
                    icon: 'success',
                    title: 'Username Generado',
                    text: `Nuevo username: ${testUsername}`,
                    confirmButtonText: 'Aceptar'
                });
            } else {
                counter++;
                generarYVerificar();
            }
        });
    }
    
    generarYVerificar();
}

// ✅ FUNCIÓN MEJORADA: Validación de coordenadas
function validarCoordenadas() {
    const latitud = document.getElementById('latitud').value;
    const longitud = document.getElementById('longitud').value;
    const infoElement = document.getElementById('coordenadas-info');
    
    if (!latitud || !longitud || isNaN(latitud) || isNaN(longitud)) {
        if (infoElement) {
            infoElement.innerHTML = `<i class="fas fa-exclamation-triangle text-warning"></i> Coordenadas: No válidas`;
            infoElement.className = 'text-warning';
        }
        return false;
    }
    
    const lat = parseFloat(latitud);
    const lng = parseFloat(longitud);
    
    // Validar rangos aproximados de España
    if (lat < 35 || lat > 44 || lng < -10 || lng > 5) {
        if (infoElement) {
            infoElement.innerHTML = `<i class="fas fa-exclamation-triangle text-warning"></i> Coordenadas fuera de rango: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            infoElement.className = 'text-warning';
        }
        return false;
    }
    
    if (infoElement) {
        infoElement.innerHTML = `<i class="fas fa-check-circle text-success"></i> Coordenadas válidas: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        infoElement.className = 'text-success';
    }
    return true;
}



// ✅ FUNCIÓN MEJORADA: submitEmployeeForm con validaciones completas
function submitEmployeeForm() {
    
    console.log('=== INICIANDO VALIDACIÓN ===');
    
    // 1. Validar DNI COMPLETO (8 números + 1 letra)
    if (!validarDNI()) {
        Swal.fire({
            icon: 'error',
            title: 'DNI incompleto',
            text: 'El DNI debe tener 8 números + 1 letra (ej: 12345678A)'
        });
        return;
    }

    // 2. Validar que el DNI tenga formato correcto
    const dni = document.getElementById('dni').value.trim().toUpperCase();
    if (dni.length !== 9) {
        Swal.fire({
            icon: 'error',
            title: 'DNI incorrecto',
            text: 'El DNI debe tener exactamente 9 caracteres: 8 números + 1 letra'
        });
        return;
    }

    // 3. Validar edad
    const validacionEdad = validarEdadMinima();
    if (!validacionEdad.valido) {
        Swal.fire({
            icon: 'error',
            title: 'Error de edad',
            text: validacionEdad.mensaje
        });
        return;
    }

    // Validar teléfono
    if (!validarTelefono()) {
        Swal.fire({
            icon: 'error',
            title: 'Teléfono inválido',
            text: 'Por favor, ingrese un número de teléfono válido'
        });
        return;
    }

    // 4. Validar coordenadas
    const latitud = document.getElementById('latitud').value;
    const longitud = document.getElementById('longitud').value;
    
    if (!latitud || !longitud || isNaN(latitud) || isNaN(longitud)) {
        Swal.fire({
            icon: 'error',
            title: 'Ubicación requerida',
            text: 'Por favor, complete la dirección y asegúrese de que el mapa tenga coordenadas válidas'
        });
        return;
    }

    // 5. Validar dirección completa
    const domicilio = document.getElementById('domicilio').value.trim();
    if (!domicilio || domicilio.split(',').length < 3) {
        Swal.fire({
            icon: 'error',
            title: 'Dirección incompleta',
            text: 'Por favor, ingrese una dirección completa: calle, número, ciudad y código postal'
        });
        return;
    }

    // 6. Validar campos requeridos
    const camposRequeridos = ['nombre', 'apellidos', 'dni', 'fecha_nacimiento', 'domicilio','telefono'];
    const camposFaltantes = [];
    
    camposRequeridos.forEach(campo => {
        const elemento = document.getElementById(campo);
        if (!elemento || !elemento.value.trim()) {
            camposFaltantes.push(campo);
            elemento.classList.add('is-invalid');
        } else {
            elemento.classList.remove('is-invalid');
        }
    });
    
    if (camposFaltantes.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Campos incompletos',
            text: 'Por favor, complete todos los campos obligatorios marcados con *'
        });
        return;
    }

    // 7. Obtener credenciales generadas
    const usernameGenerado = document.getElementById('username').value;
    const passwordGenerado = document.getElementById('password-display').value;
    
    if (!usernameGenerado || usernameGenerado.length !== 8) {
        Swal.fire({
            icon: 'error',
            title: 'Username inválido',
            text: 'El username debe tener 8 dígitos. Verifique el DNI.'
        });
        return;
    }

    // 8. Obtener datos para el QR
    const nombreCompleto = `${document.getElementById('nombre').value.trim()} ${document.getElementById('apellidos').value.trim()}`;
    const qrData = {
        empleado_dni: dni,
        empleado_nombre: nombreCompleto,
        tipo: 'empleado',
        fecha_generacion: new Date().toISOString()
    };

    // 8. Preparar datos para enviar
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const empleadoData = {
        _token: csrfToken,
        nombre: document.getElementById('nombre').value.trim(),
        apellidos: document.getElementById('apellidos').value.trim(),
        dni: dni,
        fecha_nacimiento: document.getElementById('fecha_nacimiento').value,
        telefono: document.getElementById('telefono').value.trim(),
        domicilio: domicilio,
        latitud: parseFloat(latitud).toFixed(6),
        longitud: parseFloat(longitud).toFixed(6),
        username: usernameGenerado,
        password: passwordGenerado,
        password_confirmation: passwordGenerado,
        qr_data: qrData // ✅ NUEVO: Enviar datos para el QR

    };
    
    console.log('📤 Datos validados para enviar:', empleadoData);
    
    // 9. Enviar datos al servidor
    enviarDatosAlServidor(empleadoData, validacionEdad.edad);
}

// ✅ FUNCIÓN MEJORADA: Envío de datos con debug completo
function enviarDatosAlServidor(empleadoData, edadEmpleado) {
    console.log('🚀 Enviando datos al servidor...', empleadoData);
    
    const submitBtn = document.querySelector('#employeeModal .btn-success');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Creando...';
    submitBtn.disabled = true;
    
    fetch('{{ route("admin.empleados.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': empleadoData._token,
            'Accept': 'application/json'
        },
        body: JSON.stringify(empleadoData)
    })
    .then(response => response.json())
    .then(data => {
        console.log('✅ Respuesta exitosa:', data);
        
        if (data.success) {
            $('#employeeModal').modal('hide');
            
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                html: `
                    <div class="text-left">
                        <p>${data.message}</p>
                        <div class="alert alert-success mt-3">
                            <h6><i class="fas fa-key"></i> Credenciales Generadas</h6>
                            <hr>
                            <strong>Username:</strong> ${empleadoData.username}<br>
                            <strong>Contraseña (4 dígitos):</strong> <code class="bg-light p-1 rounded">${empleadoData.password}</code><br>
                            <strong>Edad:</strong> ${edadEmpleado} años
                        </div>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'Aceptar',
                allowOutsideClick: false,
                width: '600px'
            }).then((result) => {
                // ✅ RECARGAR DATATABLE Y ACTUALIZAR ESTADÍSTICAS
                if (typeof table !== 'undefined' && $.fn.DataTable.isDataTable('#empleadosTable')) {
                    table.ajax.reload(function() {
                        console.log('🔄 DataTable recargado completamente');
                        // ✅ ACTUALIZAR ESTADÍSTICAS DESPUÉS DE RECARGAR EL DATATABLE
                        updateStats();
                    }, false);
                } else {
                    console.log('⚠️ DataTable no encontrado, recargando página');
                    location.reload();
                }
            });
            
        } else {
            throw new Error(data.message || 'Error desconocido del servidor');
        }
    })
    .catch(error => {
        console.error('❌ Error completo:', error);
        
        let errorMessage = 'Error desconocido';
        
        try {
            // Intentar parsear el mensaje de error como JSON
            const errorData = JSON.parse(error.message);
            if (errorData.errors) {
                errorMessage = 'Errores de validación:\n\n';
                for (const field in errorData.errors) {
                    const fieldName = getFieldName(field);
                    errorMessage += `• ${fieldName}: ${errorData.errors[field][0]}\n`;
                }
            } else if (errorData.message) {
                errorMessage = errorData.message;
            }
        } catch (e) {
            // Si no es JSON, usar el mensaje original
            errorMessage = error.message;
        }
        
        Swal.fire({
            icon: 'error',
            title: 'Error al crear empleado',
            html: `
                <div class="text-left">
                    <div class="alert alert-danger">
                        <h6>Detalles del error:</h6>
                        <pre style="white-space: pre-wrap; font-size: 12px; background: #f8f9fa; padding: 10px; border-radius: 5px;">${errorMessage}</pre>
                    </div>
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: 'Entendido',
            width: '700px'
        });
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// ✅ FUNCIÓN PARA BUSCAR EMPLEADO EXISTENTE
function buscarEmpleadoPorDNI(dni) {
    Swal.fire({
        title: 'Buscando empleado...',
        text: `Buscando DNI: ${dni}`,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(`/admin/empleados/buscar-por-dni/${dni}`)
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                Swal.fire({
                    icon: 'info',
                    title: 'Empleado Encontrado',
                    html: `
                        <div class="text-left">
                            <p>El empleado con DNI <strong>${dni}</strong> ya existe:</p>
                            <div class="alert alert-info">
                                <strong>Nombre:</strong> ${data.empleado.nombre} ${data.empleado.apellidos}<br>
                                <strong>Username:</strong> ${data.empleado.username}<br>
                                <strong>Fecha registro:</strong> ${data.empleado.created_at}
                            </div>
                            <p>¿Desea ver los detalles del empleado?</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Ver Detalles',
                    cancelButtonText: 'Cerrar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirigir a la vista de detalles del empleado
                        window.location.href = `/admin/empleados/${data.empleado.id}`;
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Empleado No Encontrado',
                    text: `No se encontró un empleado con DNI: ${dni}`,
                    confirmButtonText: 'Aceptar'
                });
            }
        })
        .catch(error => {
            console.error('Error buscando empleado:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo buscar el empleado. Contacte al administrador.'
            });
        });
}

// ✅ FUNCIÓN PARA CORREGIR DNI
function corregirDNI() {
    Swal.fire({
        title: 'Corregir DNI',
        html: `
            <div class="text-left">
                <p>Ingrese un nuevo DNI:</p>
                <input type="text" id="nuevoDni" class="swal2-input" placeholder="Nuevo DNI" value="${document.getElementById('dni').value}">
                <small class="form-text text-muted">Formato: 8 números + 1 letra</small>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const nuevoDni = document.getElementById('nuevoDni').value.trim().toUpperCase();
            if (!nuevoDni || nuevoDni.length !== 9) {
                Swal.showValidationMessage('El DNI debe tener 9 caracteres');
                return false;
            }
            return nuevoDni;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('dni').value = result.value;
            validarDNI();
            generarUsername();
            
            Swal.fire({
                icon: 'success',
                title: 'DNI Actualizado',
                text: 'El DNI ha sido actualizado correctamente',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

// ✅ FUNCIÓN ADICIONAL: Validar contraseña en tiempo real
function validarPassword4Digitos() {
    const passwordInput = document.getElementById('password-display');
    const password = passwordInput.value;
    
    if (password.length === 4 && /^\d+$/.test(password)) {
        passwordInput.classList.remove('is-invalid');
        passwordInput.classList.add('is-valid');
        return true;
    } else {
        passwordInput.classList.remove('is-valid');
        passwordInput.classList.add('is-invalid');
        return false;
    }
}

// Función para validar que solo se ingresen números en la contraseña
function validarPasswordNumerica(input) {
    const valor = input.value;
    const soloNumerosRegex = /^[0-9]*$/; // Permite solo números
    
    if (!soloNumerosRegex.test(valor)) {
        // Remover caracteres no numéricos
        input.value = valor.replace(/[^0-9]/g, '');
    }
    
    // Actualizar el mensaje de ayuda
    const longitud = input.value.length;
    const mensaje = document.getElementById('passwordHelp');
    if (mensaje) {
        if (longitud < 8) {
            mensaje.textContent = `Mínimo 8 dígitos (actual: ${longitud})`;
            mensaje.style.color = '#dc3545';
        } else {
            mensaje.textContent = 'Contraseña válida (8+ dígitos)';
            mensaje.style.color = '#28a745';
        }
    }
}

// Función para verificar que todos los campos requeridos estén presentes
function verificarCamposFormulario() {
    const camposRequeridos = [
        'nombre', 'apellidos', 'dni', 'fecha_nacimiento', 
        'domicilio', 'username', 'password', 'password_confirmation',
        'latitud', 'longitud'
    ];
    
    const camposFaltantes = [];
    
    camposRequeridos.forEach(campo => {
        const elemento = document.getElementById(campo);
        if (!elemento || !elemento.value) {
            camposFaltantes.push(campo);
        }
    });
    
    if (camposFaltantes.length > 0) {
        console.error('❌ Campos faltantes:', camposFaltantes);
        return false;
    }
    
    console.log('✅ Todos los campos están presentes');
    return true;
}

// Variables globales
let map = null;
let marker = null;
let geocoder = null;
let autocomplete = null;
let googleMapsLoaded = false;

// Función para verificar si Google Maps está cargado
function checkGoogleMaps() {
    if (typeof google !== 'undefined' && google.maps && google.maps.Geocoder) {
        googleMapsLoaded = true;
        initializeGoogleMaps();
        console.log('✅ Google Maps API cargada correctamente');
    } else {
        console.log('⏳ Esperando carga de Google Maps...');
        setTimeout(checkGoogleMaps, 500);
    }
}

// Inicializar cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    checkGoogleMaps();
    initializeFallbackMap();
});

// Inicialización de Google Maps
function initializeGoogleMaps() {
    try {
        geocoder = new google.maps.Geocoder();
        initMap();
        initAutocomplete();
        
    } catch (error) {
        console.error('Error inicializando Google Maps:', error);
        showMapError('Error técnico al cargar el mapa');
    }
}

// **FUNCIÓN INITMAP CORREGIDA**
function initMap() {
    try {
        const mapElement = document.getElementById('map');
        if (!mapElement) {
            console.error('Elemento #map no encontrado');
            return;
        }
        
        // Limpiar el contenido existente del mapa
        mapElement.innerHTML = '';
        
        const defaultLocation = { lat: 40.4168, lng: -3.7038 };
        
        // Crear un div interno para el mapa
        const mapInnerDiv = document.createElement('div');
        mapInnerDiv.style.width = '100%';
        mapInnerDiv.style.height = '100%';
        mapElement.appendChild(mapInnerDiv);
        
        // Inicializar el mapa en el div interno
        map = new google.maps.Map(mapInnerDiv, {
            zoom: 12,
            center: defaultLocation,
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true,
            zoomControl: true
        });
        
        // Crear marcador
        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            title: "Arrastre para ajustar la ubicación",
            position: defaultLocation
        });
        
        // Eventos del marcador
        marker.addListener('dragend', function() {
            if (geocoder) {
                reverseGeocode(marker.getPosition());
            }
        });
        
        // Evento para hacer clic en el mapa
        map.addListener('click', function(event) {
            marker.setPosition(event.latLng);
            if (geocoder) {
                reverseGeocode(event.latLng);
            }
        });
        
        console.log('✅ Mapa inicializado correctamente');
        
    } catch (error) {
        console.error('Error en initMap:', error);
        showMapError('Error al inicializar el mapa: ' + error.message);
    }
}

// Mapa de respaldo
function initializeFallbackMap() {
    const mapElement = document.getElementById('map');
    if (mapElement && !map) {
        mapElement.innerHTML = `
            <div class="alert alert-info text-center h-100 d-flex align-items-center justify-content-center">
                <div>
                    <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                    <h5>Cargando servicios de mapa...</h5>
                    <p class="mb-0">Inicializando geolocalización</p>
                </div>
            </div>
        `;
    }
}

// Función para inicializar autocompletado
function initAutocomplete() {
    try {
        const domicilioInput = document.getElementById('domicilio');
        if (!domicilioInput) return;
        
        autocomplete = new google.maps.places.Autocomplete(domicilioInput, {
            types: ['address'],
            componentRestrictions: { country: 'es' },
            fields: ['address_components', 'formatted_address', 'geometry']
        });
        
        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            
            if (!place.geometry) {
                console.log('No hay detalles disponibles para: ' + place.name);
                return;
            }
            
            const formattedAddress = formatAddress(place);
            domicilioInput.value = formattedAddress;
            updateMapFromPlace(place);
        });
        
        console.log('✅ Autocompletado configurado');
        
    } catch (error) {
        console.error('Error en autocompletado:', error);
    }
}

// **FUNCIÓN GEOCodificarDireccion CORREGIDA**
function geocodificarDireccion() {
    // Verificar si tenemos geocoder
    if (!geocoder) {
        showAlert('Servicios de mapa no disponibles. Usando modo simulación.', 'warning');
        simulateGeocoding();
        return;
    }
    
    const address = document.getElementById('domicilio').value.trim();
    
    if (!address) {
        showAlert('Por favor, ingrese una dirección para buscar.', 'warning');
        return;
    }
    
    showGeocodingLoading(true);
    
    geocoder.geocode({
        address: address,
        componentRestrictions: { country: 'ES' }
    }, function(results, status) {
        showGeocodingLoading(false);
        
        if (status === 'OK' && results[0]) {
            const place = results[0];
            const formattedAddress = formatAddress(place);
            
            document.getElementById('domicilio').value = formattedAddress;
            updateMapFromPlace(place);
            
            //showAlert('Dirección encontrada correctamente', 'success', 2000);
        } else {
            handleGeocodingError(status);
        }
    });
}

// Actualizar mapa desde un lugar
// Función mejorada para actualizar coordenadas
function updateMapFromPlace(place) {
    if (!map || !marker) {
        console.warn('Mapa o marcador no disponibles');
        return;
    }
    
    if (place.geometry && place.geometry.location) {
        const location = place.geometry.location;
        
        map.setCenter(location);
        map.setZoom(16);
        marker.setPosition(location);
        
        // **ASIGNAR VALORES DIRECTAMENTE A LOS INPUTS**
        document.getElementById('latitud').value = location.lat();
        document.getElementById('longitud').value = location.lng();
        updateCoordinatesInfo();
        
        console.log('📍 Coordenadas actualizadas:', location.lat(), location.lng());
    } else {
        // **SI NO HAY GEOMETRÍA, ASIGNAR VALORES POR DEFECTO**
        console.warn('No se encontró geometría para el lugar, asignando valores por defecto');
        asignarCoordenadasPorDefecto();
    }
}

// Función para asignar coordenadas por defecto
function asignarCoordenadasPorDefecto() {
    const coordenadasPorDefecto = {
        lat: '40.4168',  // Madrid
        lng: '-3.7038'
    };
    
    document.getElementById('latitud').value = coordenadasPorDefecto.lat;
    document.getElementById('longitud').value = coordenadasPorDefecto.lng;
    updateCoordinatesInfo();
    
    console.log('📍 Coordenadas por defecto asignadas:', coordenadasPorDefecto);
}

// Modificar la función de geocodificación
function geocodificarDireccion() {
    const address = document.getElementById('domicilio').value.trim();
    
    if (!address) {
        showAlert('Por favor, ingrese una dirección para buscar.', 'warning');
        return;
    }
    
    // Si Google Maps no está disponible, asignar coordenadas por defecto
    if (!googleMapsLoaded || !geocoder) {
        showAlert('Servicios de mapa no disponibles. Usando ubicación por defecto.', 'warning');
        asignarCoordenadasPorDefecto();
        return;
    }
    
    showGeocodingLoading(true);
    
    geocoder.geocode({
        address: address,
        componentRestrictions: { country: 'ES' }
    }, function(results, status) {
        showGeocodingLoading(false);
        
        if (status === 'OK' && results[0]) {
            updateMapFromPlace(results[0]);
            //showAlert('Dirección encontrada correctamente', 'success', 2000);
        } else {
            // Si falla la geocodificación, usar coordenadas por defecto
            console.warn(`Geocoding falló: ${status}, usando coordenadas por defecto`);
            asignarCoordenadasPorDefecto();
            showAlert('No se pudo encontrar la ubicación exacta. Usando ubicación por defecto.', 'info', 2000);
        }
    });
}

// Reverse geocoding
function reverseGeocode(location) {
    if (!geocoder) return;
    
    geocoder.geocode({ location: location }, function(results, status) {
        if (status === 'OK' && results[0]) {
            const formattedAddress = formatAddress(results[0]);
            document.getElementById('domicilio').value = formattedAddress;
            
            document.getElementById('latitud').value = location.lat();
            document.getElementById('longitud').value = location.lng();
            updateCoordinatesInfo();
        }
    });
}

// Formatear dirección
function formatAddress(place) {
    let street = '';
    let streetNumber = '';
    let city = '';
    let postalCode = '';
    
    if (place.address_components) {
        place.address_components.forEach(component => {
            const types = component.types;
            
            if (types.includes('street_number')) {
                streetNumber = component.long_name;
            } else if (types.includes('route')) {
                street = component.long_name;
            } else if (types.includes('locality')) {
                city = component.long_name;
            } else if (types.includes('postal_code')) {
                postalCode = component.long_name;
            }
        });
    }
    
    let formatted = '';
    if (street) {
        formatted += street;
        if (streetNumber) formatted += ', ' + streetNumber;
    }
    if (city) {
        if (formatted) formatted += ', ';
        formatted += city;
    }
    if (postalCode) {
        if (formatted) formatted += ', ';
        formatted += postalCode;
    }
    
    return formatted || place.formatted_address || '';
}

// Actualizar información de coordenadas
function updateCoordinatesInfo() {
    const lat = document.getElementById('latitud').value;
    const lng = document.getElementById('longitud').value;
    const infoElement = document.getElementById('coordenadas-info');
    
    if (!infoElement) return;
    
    if (lat && lng) {
        infoElement.innerHTML = `<i class="fas fa-check-circle text-success"></i> Coordenadas: ${lat}, ${lng}`;
        infoElement.className = 'text-success';
    } else {
        infoElement.innerHTML = `<i class="fas fa-info-circle"></i> Coordenadas: No especificadas`;
        infoElement.className = 'text-muted';
    }
}

// Mostrar error en el mapa
function showMapError(message) {
    const mapElement = document.getElementById('map');
    if (mapElement) {
        mapElement.innerHTML = `
            <div class="alert alert-warning text-center h-100 d-flex align-items-center justify-content-center">
                <div>
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <h5>${message}</h5>
                    <p class="mb-2">Usando modo simulación</p>
                    <button class="btn btn-primary btn-sm" onclick="simulateGeocoding()">
                        <i class="fas fa-map-marker-alt"></i> Asignar coordenadas manualmente
                    </button>
                </div>
            </div>
        `;
    }
}

// Simulación de geocoding
function simulateGeocoding() {
    const address = document.getElementById('domicilio').value.trim();
    
    showGeocodingLoading(true);
    
    setTimeout(() => {
        showGeocodingLoading(false);
        
        const latBase = 40.4168;
        const lngBase = -3.7038;
        const variacion = (Math.random() - 0.5) * 0.02;
        
        const coordenadas = {
            lat: (latBase + variacion).toFixed(6),
            lng: (lngBase + variacion).toFixed(6)
        };
        
        document.getElementById('latitud').value = coordenadas.lat;
        document.getElementById('longitud').value = coordenadas.lng;
        updateCoordinatesInfo();
        
        showAlert('Modo simulación: Coordenadas asignadas', 'info', 2000);
        
    }, 1000);
}

// Manejar errores de geocodificación
function handleGeocodingError(status) {
    const errors = {
        'ZERO_RESULTS': 'No se encontraron resultados para la dirección ingresada.',
        'OVER_QUERY_LIMIT': 'Límite de consultas excedido. Intente más tarde.',
        'REQUEST_DENIED': 'Error de API. Verifique la configuración de Google Maps.',
        'INVALID_REQUEST': 'La dirección ingresada es inválida.',
        'UNKNOWN_ERROR': 'Error desconocido. Intente nuevamente.'
    };
    
    showAlert(errors[status] || `Error: ${status}`, 'error');
}

// Mostrar/ocultar loading
function showGeocodingLoading(show) {
    const button = document.getElementById('btn-geocodificar');
    if (button) {
        if (show) {
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';
            button.disabled = true;
        } else {
            button.innerHTML = '<i class="fas fa-search-location"></i> Buscar';
            button.disabled = false;
        }
    }
}

// Función para mostrar alertas
function showAlert(message, type, timer = null) {
    Swal.fire({
        icon: type,
        title: message,
        timer: timer,
        showConfirmButton: !timer
    });
}

// Geocodificación automática mientras se escribe
let geocodingTimeout;
document.getElementById('domicilio').addEventListener('input', function() {
    clearTimeout(geocodingTimeout);
    
    const address = this.value.trim();
    if (address.length > 5) {
        geocodingTimeout = setTimeout(() => {
            geocodificarDireccion();
        }, 1500);
    }
});

// Permitir Enter para buscar
document.getElementById('domicilio').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        geocodificarDireccion();
    }
});

// Redimensionar mapa cuando el modal se muestre
$('#employeeModal').on('shown.bs.modal', function() {
    if (googleMapsLoaded && map) {
        setTimeout(() => {
            google.maps.event.trigger(map, 'resize');
            if (marker && marker.getPosition()) {
                map.setCenter(marker.getPosition());
                map.setZoom(16);
            }
        }, 300);
    }
});

// Verificar estado al abrir el modal
$('#employeeModal').on('show.bs.modal', function() {
    if (!googleMapsLoaded) {
        checkGoogleMaps();
    }
});

// Limpiar cuando el modal se cierre
$('#employeeModal').on('hidden.bs.modal', function() {
    document.getElementById('domicilio').value = '';
    document.getElementById('latitud').value = '';
    document.getElementById('longitud').value = '';
    updateCoordinatesInfo();
});


// Función para cargar estadísticas
function loadStats() {
    console.log('📊 Cargando estadísticas...');
    
    $.ajax({
        url: '{{ route("admin.empleados.stats") }}',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#totalEmpleados').text(response.data.total);
                $('#registrosMes').text(response.data.registros_mes);
                $('#promedioEdad').text(response.data.promedio_edad + ' años');
                console.log('✅ Estadísticas actualizadas:', response.data);
            } else {
                console.error('❌ Error en respuesta de estadísticas:', response);
                setDefaultStats();
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error cargando estadísticas:', error);
            setDefaultStats();
        }
    });
}

// Función para establecer valores por defecto
function setDefaultStats() {
    $('#totalEmpleados').text('0');
    $('#registrosMes').text('0');
    $('#promedioEdad').text('0 años');
}

// Función para cargar estadísticas al inicializar la página
function loadStats() {
    console.log('📊 Cargando estadísticas iniciales...');
    updateStats();
}

// Funciones de filtros
function aplicarFiltros() {
    console.log('🔍 Aplicando filtros...');
    
    // Obtener y normalizar datos
    const filtros = prepararDatosFiltros();
    
    // Validar que los 3 campos estén completos
    if (!filtros.filterDni || !filtros.filterNombre || !filtros.filterMes) {
        Swal.fire({
            icon: 'warning',
            title: 'Filtros incompletos',
            html: `
                <div class="text-left">
                    <p>Debe completar los 3 filtros para realizar la búsqueda:</p>
                    <ul>
                        <li><strong>DNI:</strong> ${filtros.filterDni ? '✅ Completado' : '❌ Faltante'}</li>
                        <li><strong>Nombre:</strong> ${filtros.filterNombre ? '✅ Completado' : '❌ Faltante'}</li>
                        <li><strong>Mes completo:</strong> ${filtros.filterMes ? '✅ Completado' : '❌ Faltante'}</li>
                    </ul>
                </div>
            `,
            confirmButtonText: 'Entendido'
        });
        return;
    }
    
    // Validar formato del DNI
    if (filtros.filterDni.length === 9) {
        const numero = filtros.filterDni.substring(0, 8);
        const letra = filtros.filterDni.substring(8, 9);
        const letrasValidas = 'TRWAGMYFPDXBNJZSQVHLCKE';
        const letraCalculada = letrasValidas[numero % 23];
        
        if (letra !== letraCalculada) {
            Swal.fire({
                icon: 'error',
                title: 'DNI incorrecto',
                html: `
                    <div class="text-left">
                        <p>La letra del DNI <strong>${filtros.filterDni}</strong> es incorrecta.</p>
                        <p>La letra debería ser: <strong>${letraCalculada}</strong></p>
                        <p class="text-muted">DNI correcto: <code>${numero}${letraCalculada}</code></p>
                    </div>
                `,
                confirmButtonText: 'Corregir'
            });
            return;
        }
    }
    
    // Mostrar información del filtro aplicado
    mostrarInfoFiltro($('#filterMes').val().trim());
    
    // Aplicar filtros - Los datos se enviarán a través del DataTable
    table.ajax.reload();
}

function prepararDatosFiltros() {
    const filterDni = $('#filterDni').val().trim();
    const filterNombre = $('#filterNombre').val().trim();
    const filterMes = $('#filterMes').val().trim();
    
    // Normalizar DNI (quitar espacios, poner mayúsculas)
    const dniNormalizado = filterDni.toUpperCase().replace(/\s/g, '');
    
    // Normalizar mes (convertir MM-YYYY a YYYY-MM)
    let mesNormalizado = filterMes;
    if (filterMes.match(/^\d{2}-\d{4}$/)) {
        const partes = filterMes.split('-');
        mesNormalizado = `${partes[1]}-${partes[0]}`; // Convertir a YYYY-MM
    }
    
    console.log('📤 Datos normalizados:', {
        dni_original: filterDni,
        dni_normalizado: dniNormalizado,
        mes_original: filterMes,
        mes_normalizado: mesNormalizado,
        nombre: filterNombre
    });
    
    return {
        filterDni: dniNormalizado,
        filterNombre: filterNombre,
        filterMes: mesNormalizado
    };
}

function mostrarInfoFiltro(mes) {
    const filtroInfo = $('#filtroInfo');
    const infoMes = $('#infoMes');
    
    console.log('🔍 Valor recibido en mostrarInfoFiltro:', mes);
    
    if (!mes || mes.trim() === '') {
        filtroInfo.hide();
        return;
    }
    
    const mesLimpio = mes.trim();
    
    // ✅ ACEPTAR AMBOS FORMATOS
    let año, mesNumero;
    
    // Formato YYYY-MM (2025-09)
    if (mesLimpio.match(/^(\d{4})-(\d{2})$/)) {
        const partes = mesLimpio.split('-');
        año = partes[0];
        mesNumero = partes[1];
        console.log('✅ Formato YYYY-MM detectado');
    }
    // Formato MM-YYYY (09-2025) 
    else if (mesLimpio.match(/^(\d{2})-(\d{4})$/)) {
        const partes = mesLimpio.split('-');
        año = partes[1];
        mesNumero = partes[0];
        console.log('✅ Formato MM-YYYY detectado');
    }
    else {
        // Formato no reconocido
        console.warn('⚠️ Formato no reconocido:', mesLimpio);
        infoMes.text(`Filtrando por: ${mesLimpio}`);
        filtroInfo.show();
        return;
    }
    
    // Mapeo de meses en español
    const meses = {
        '01': 'enero', '02': 'febrero', '03': 'marzo', '04': 'abril',
        '05': 'mayo', '06': 'junio', '07': 'julio', '08': 'agosto',
        '09': 'septiembre', '10': 'octubre', '11': 'noviembre', '12': 'diciembre'
    };
    
    if (año && mesNumero && meses[mesNumero]) {
        const mesFormateado = `${meses[mesNumero]} de ${año}`;
        infoMes.text(mesFormateado);
        filtroInfo.show();
        console.log('✅ Mes formateado:', mesFormateado);
    } else {
        // Fallback
        infoMes.text(`Filtrando por: ${mesLimpio}`);
        filtroInfo.show();
    }
}

function normalizarFormatoMes(mes) {
    if (!mes) return '';
    
    const mesLimpio = mes.trim();
    
    // Si ya está en formato YYYY-MM, dejarlo así
    if (mesLimpio.match(/^\d{4}-\d{1,2}$/)) {
        const partes = mesLimpio.split('-');
        return `${partes[0]}-${partes[1].padStart(2, '0')}`;
    }
    
    // Convertir de MM-YYYY a YYYY-MM
    if (mesLimpio.match(/^(\d{1,2})-(\d{4})$/)) {
        const partes = mesLimpio.split('-');
        return `${partes[1]}-${partes[0].padStart(2, '0')}`;
    }
    
    // Convertir de MM/YYYY a YYYY-MM
    if (mesLimpio.match(/^(\d{1,2})\/(\d{4})$/)) {
        const partes = mesLimpio.split('/');
        return `${partes[1]}-${partes[0].padStart(2, '0')}`;
    }
    
    // Si no coincide con ningún formato conocido, devolver original
    return mesLimpio;
}

function limpiarFiltros() {
    console.log('🧹 Limpiando filtros...');
    
    Swal.fire({
        title: '¿Limpiar filtros?',
        text: 'Se eliminarán todos los filtros aplicados',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, limpiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#filterDni').val('');
            $('#filterNombre').val('');
            $('#filterMes').val('');
            $('#filtroInfo').hide();
            
            // Recargar tabla sin filtros
            table.ajax.reload();
            
            Swal.fire({
                icon: 'success',
                title: 'Filtros limpiados',
                text: 'Todos los filtros han sido eliminados',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

function limpiarFiltroMes() {
    $('#filterMes').val('');
    $('#filtroInfo').hide();
    // No recargar la tabla automáticamente, esperar a que se apliquen los filtros
<<<<<<< HEAD
}

// Función para validar teléfono
function validarTelefono() {
    const telefonoInput = document.getElementById('telefono');
    const telefono = telefonoInput.value.trim();
    
    // Expresión regular para teléfono internacional
    const telefonoRegex = /^[+]?[0-9\s\-]+$/;
    
    if (telefono && telefonoRegex.test(telefono) && telefono.length >= 9) {
        telefonoInput.classList.remove('is-invalid');
        telefonoInput.classList.add('is-valid');
        return true;
    } else if (telefono.length > 0) {
        telefonoInput.classList.remove('is-valid');
        telefonoInput.classList.add('is-invalid');
        return false;
    } else {
        telefonoInput.classList.remove('is-valid', 'is-invalid');
        return false;
    }
=======
>>>>>>> db47f97ca6491ce026d72a79284a0d57d54ea54c
}

function exportarExcel() {
    const filterMes = $('#filterMes').val().trim();
    
    if (!filterMes) {
        Swal.fire({
            icon: 'warning',
            title: 'Mes requerido',
            text: 'Por favor, seleccione un mes para exportar',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Convertir formato MM-YYYY o YYYY-MM a mes y año separados
    let mes, año;
    
    if (filterMes.match(/^(\d{2})-(\d{4})$/)) {
        // Formato MM-YYYY
        const partes = filterMes.split('-');
        mes = parseInt(partes[0]);
        año = parseInt(partes[1]);
    } else if (filterMes.match(/^(\d{4})-(\d{2})$/)) {
        // Formato YYYY-MM
        const partes = filterMes.split('-');
        mes = parseInt(partes[1]);
        año = parseInt(partes[0]);
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Formato inválido',
            text: 'El formato del mes debe ser MM-AAAA o AAAA-MM',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    console.log('📤 Exportando Excel para:', { mes, año, filterMes });

    // Mostrar confirmación
    Swal.fire({
        title: 'Exportar a Excel',
        html: `
            <div class="text-left">
                <p>¿Exportar empleados registrados en <strong>${getNombreMes(mes)} de ${año}</strong>?</p>
                <p class="text-muted small">Se generará un archivo Excel con todos los empleados del mes seleccionado.</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, Exportar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745'
    }).then((result) => {
        if (result.isConfirmed) {
            exportarExcelConfirmado(mes, año);
        }
    });
}

function exportarExcelConfirmado(mes, año) {
    // Mostrar loading
    Swal.fire({
        title: 'Generando Excel...',
        text: 'Por favor espere mientras se genera el archivo',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Hacer la petición para exportar
    fetch(`/admin/empleados/exportar-excel-mes?mes=${mes}&año=${año}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || 'Error en la respuesta del servidor');
            });
        }
        return response.blob();
    })
    .then(blob => {
        Swal.close();
        
        // Crear URL para descargar el archivo
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        
        // Nombre del archivo
        const meses = {
            1: 'enero', 2: 'febrero', 3: 'marzo', 4: 'abril',
            5: 'mayo', 6: 'junio', 7: 'julio', 8: 'agosto',
            9: 'septiembre', 10: 'octubre', 11: 'noviembre', 12: 'diciembre'
        };
        
        a.download = `empleados_${meses[mes]}_${año}.xlsx`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        
        // Mostrar mensaje de éxito
        Swal.fire({
            icon: 'success',
            title: '¡Excel Exportado!',
            html: `
                <div class="text-left">
                    <p>El archivo <strong>${a.download}</strong> se ha descargado correctamente.</p>
                    <p class="text-muted small">Empleados registrados en ${getNombreMes(mes)} de ${año}</p>
                </div>
            `,
            confirmButtonText: 'Aceptar'
        });
        
    })
    .catch(error => {
        console.error('❌ Error exportando Excel:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error al Exportar',
            html: `
                <div class="text-left">
                    <p><strong>No se pudo generar el archivo Excel</strong></p>
                    <p>${error.message}</p>
                    <p class="text-muted small">Verifique que haya empleados registrados en el mes seleccionado.</p>
                </div>
            `,
            confirmButtonText: 'Entendido'
        });
    });
}

// Función auxiliar para obtener nombre del mes
function getNombreMes(mes) {
    const meses = {
        1: 'enero', 2: 'febrero', 3: 'marzo', 4: 'abril',
        5: 'mayo', 6: 'junio', 7: 'julio', 8: 'agosto',
        9: 'septiembre', 10: 'octubre', 11: 'noviembre', 12: 'diciembre'
    };
    return meses[mes] || 'mes';
}

// Funciones para las acciones de la tabla
function verEmpleado(id) {
    Swal.fire({
        icon: 'info',
        title: 'Ver Empleado',
        text: 'Función de ver detalles en desarrollo para ID: ' + id,
        confirmButtonText: 'Aceptar'
    });
}

function editarEmpleado(id) {
    Swal.fire({
        icon: 'info',
        title: 'Editar Empleado',
        text: 'Función de edición en desarrollo para ID: ' + id,
        confirmButtonText: 'Aceptar'
    });
}

function eliminarEmpleado(id) {
    Swal.fire({
        icon: 'warning',
        title: '¿Eliminar Empleado?',
        text: 'Esta acción no se puede deshacer',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/empleados/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('¡Eliminado!', response.message, 'success');
                        table.ajax.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo eliminar el empleado', 'error');
                }
            });
        }
    });
}

// Función para actualizar estadísticas
function updateStats() {
    console.log('📊 Actualizando estadísticas...');
    
    // Hacer petición al servidor para obtener estadísticas actualizadas
    fetch('{{ route("admin.empleados.stats") }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#totalEmpleados').text(data.data.total);
            $('#registrosMes').text(data.data.registros_mes);
            $('#promedioEdad').text(data.data.promedio_edad + ' años');
            console.log('✅ Estadísticas actualizadas:', data.data);
        } else {
            console.error('❌ Error en respuesta de estadísticas:', data);
            setDefaultStats();
        }
    })
    .catch(error => {
        console.error('❌ Error cargando estadísticas:', error);
        setDefaultStats();
    });
}


/// ---------------------------- Editar Empleado --------------------------------------------

// Variables globales para el mapa de edición
let editMap = null;
let editMarker = null;
let editGeocoder = null;
let editAutocomplete = null;

// Función para abrir el modal de edición
function editarEmpleado(id) {
    console.log('📝 Editando empleado ID:', id);
    
    // Mostrar loading
    Swal.fire({
        title: 'Cargando...',
        text: 'Obteniendo datos del empleado',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Obtener datos del empleado
    fetch(`/admin/empleados/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.success) {
                console.log('✅ Datos del empleado:', data.data);
                populateEditForm(data.data);
                $('#editEmployeeModal').modal('show');
            } else {
                throw new Error(data.message || 'Error al cargar datos del empleado');
            }
        })
        .catch(error => {
            console.error('❌ Error cargando empleado:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los datos del empleado: ' + error.message
            });
        });
}

// Función para llenar el formulario de edición
function populateEditForm(empleado) {
    // Llenar campos de solo lectura
    document.getElementById('edit_nombre').value = empleado.nombre || '';
    document.getElementById('edit_apellidos').value = empleado.apellidos || '';
    document.getElementById('edit_dni').value = empleado.dni || '';
    document.getElementById('edit_fecha_nacimiento').value = empleado.fecha_nacimiento_formatted || '';
    document.getElementById('edit_username').value = empleado.username || '';
    document.getElementById('edit_edad').value = empleado.edad ? empleado.edad + ' años' : '';
    
    // Llenar campo editable
    document.getElementById('edit_domicilio').value = empleado.domicilio || '';
    document.getElementById('edit_latitud').value = empleado.latitud || '40.4168';
    document.getElementById('edit_longitud').value = empleado.longitud || '-3.7038';
    document.getElementById('edit_empleado_id').value = empleado.id || '';
    
    // Actualizar información de coordenadas
    updateEditCoordinatesInfo();
    
    // Inicializar mapa de edición
    initializeEditMap();
}

// Función para inicializar el mapa de edición
function initializeEditMap() {
    const lat = parseFloat(document.getElementById('edit_latitud').value) || 40.4168;
    const lng = parseFloat(document.getElementById('edit_longitud').value) || -3.7038;
    
    const mapElement = document.getElementById('edit_map');
    if (!mapElement) return;
    
    // Limpiar mapa existente
    mapElement.innerHTML = '';
    
    const mapInnerDiv = document.createElement('div');
    mapInnerDiv.style.width = '100%';
    mapInnerDiv.style.height = '100%';
    mapElement.appendChild(mapInnerDiv);
    
    // Crear nuevo mapa
    editMap = new google.maps.Map(mapInnerDiv, {
        zoom: 15,
        center: { lat: lat, lng: lng },
        mapTypeControl: true,
        streetViewControl: true,
        fullscreenControl: true,
        zoomControl: true
    });
    
    // Crear marcador
    editMarker = new google.maps.Marker({
        map: editMap,
        draggable: true,
        title: "Arrastre para ajustar la ubicación",
        position: { lat: lat, lng: lng }
    });
    
    // Eventos del marcador
    editMarker.addListener('dragend', function() {
        if (editGeocoder) {
            reverseGeocodeEdit(editMarker.getPosition());
        }
    });
    
    // Evento para hacer clic en el mapa
    editMap.addListener('click', function(event) {
        editMarker.setPosition(event.latLng);
        if (editGeocoder) {
            reverseGeocodeEdit(event.latLng);
        }
    });
    
    // Inicializar geocoder para edición
    if (typeof google !== 'undefined' && google.maps && google.maps.Geocoder) {
        editGeocoder = new google.maps.Geocoder();
        initEditAutocomplete();
    }
    
    console.log('✅ Mapa de edición inicializado');
}

// Función para inicializar autocompletado en edición
function initEditAutocomplete() {
    try {
        const domicilioInput = document.getElementById('edit_domicilio');
        if (!domicilioInput) return;
        
        editAutocomplete = new google.maps.places.Autocomplete(domicilioInput, {
            types: ['address'],
            componentRestrictions: { country: 'es' },
            fields: ['address_components', 'formatted_address', 'geometry']
        });
        
        editAutocomplete.addListener('place_changed', function() {
            const place = editAutocomplete.getPlace();
            
            if (!place.geometry) {
                console.log('No hay detalles disponibles para: ' + place.name);
                return;
            }
            
            const formattedAddress = formatAddress(place);
            domicilioInput.value = formattedAddress;
            updateEditMapFromPlace(place);
        });
        
    } catch (error) {
        console.error('Error en autocompletado de edición:', error);
    }
}

// Función para geocodificar dirección en edición
function geocodificarDireccionEdit() {
    const address = document.getElementById('edit_domicilio').value.trim();
    
    if (!address) {
        showAlert('Por favor, ingrese una dirección para buscar.', 'warning');
        return;
    }
    
    if (!editGeocoder) {
        showAlert('Servicios de mapa no disponibles.', 'warning');
        return;
    }
    
    showEditGeocodingLoading(true);
    
    editGeocoder.geocode({
        address: address,
        componentRestrictions: { country: 'ES' }
    }, function(results, status) {
        showEditGeocodingLoading(false);
        
        if (status === 'OK' && results[0]) {
            updateEditMapFromPlace(results[0]);
            showAlert('Dirección encontrada correctamente', 'success', 2000);
        } else {
            showAlert('No se pudo encontrar la ubicación exacta.', 'info', 2000);
        }
    });
}

// Función para actualizar mapa desde un lugar en edición
function updateEditMapFromPlace(place) {
    if (!editMap || !editMarker) return;
    
    if (place.geometry && place.geometry.location) {
        const location = place.geometry.location;
        
        editMap.setCenter(location);
        editMap.setZoom(16);
        editMarker.setPosition(location);
        
        document.getElementById('edit_latitud').value = location.lat();
        document.getElementById('edit_longitud').value = location.lng();
        updateEditCoordinatesInfo();
    }
}

// Reverse geocoding para edición
function reverseGeocodeEdit(location) {
    if (!editGeocoder) return;
    
    editGeocoder.geocode({ location: location }, function(results, status) {
        if (status === 'OK' && results[0]) {
            const formattedAddress = formatAddress(results[0]);
            document.getElementById('edit_domicilio').value = formattedAddress;
            
            document.getElementById('edit_latitud').value = location.lat();
            document.getElementById('edit_longitud').value = location.lng();
            updateEditCoordinatesInfo();
        }
    });
}

// Función para actualizar información de coordenadas en edición
function updateEditCoordinatesInfo() {
    const lat = document.getElementById('edit_latitud').value;
    const lng = document.getElementById('edit_longitud').value;
    const infoElement = document.getElementById('edit_coordenadas-info');
    
    if (!infoElement) return;
    
    if (lat && lng) {
        infoElement.innerHTML = `<i class="fas fa-check-circle text-success"></i> Coordenadas: ${lat}, ${lng}`;
        infoElement.className = 'text-success';
    } else {
        infoElement.innerHTML = `<i class="fas fa-info-circle"></i> Coordenadas: No especificadas`;
        infoElement.className = 'text-muted';
    }
}

// Mostrar/ocultar loading en geocodificación de edición
function showEditGeocodingLoading(show) {
    const button = document.getElementById('btn-geocodificar-edit');
    if (button) {
        if (show) {
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';
            button.disabled = true;
        } else {
            button.innerHTML = '<i class="fas fa-search-location"></i> Buscar';
            button.disabled = false;
        }
    }
}

// Función para actualizar el empleado
function updateEmployee() {
    const empleadoId = document.getElementById('edit_empleado_id').value;
    const domicilio = document.getElementById('edit_domicilio').value.trim();
    const latitud = document.getElementById('edit_latitud').value;
    const longitud = document.getElementById('edit_longitud').value;
    
    if (!domicilio) {
        showAlert('Por favor, ingrese el domicilio.', 'warning');
        return;
    }
    
    if (!empleadoId) {
        showAlert('Error: ID de empleado no válido.', 'error');
        return;
    }
    
    const submitBtn = document.querySelector('#editEmployeeModal .btn-warning');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Actualizando...';
    submitBtn.disabled = true;
    
    const data = {
        _token: '{{ csrf_token() }}',
        _method: 'PUT',
        domicilio: domicilio,
        latitud: latitud,
        longitud: longitud
    };
    
    fetch(`/admin/empleados/${empleadoId}`, {
        method: 'POST', // Usar POST para simular PUT (Laravel)
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#editEmployeeModal').modal('hide');
            
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Recargar DataTable y estadísticas
                if (table && $.fn.DataTable.isDataTable('#empleadosTable')) {
                    table.ajax.reload(null, false);
                    updateStats();
                }
            });
        } else {
            throw new Error(data.message || 'Error al actualizar empleado');
        }
    })
    .catch(error => {
        console.error('❌ Error actualizando empleado:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo actualizar el empleado: ' + error.message
        });
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Limpiar modal cuando se cierre
$('#editEmployeeModal').on('hidden.bs.modal', function () {
    document.getElementById('editEmployeeForm').reset();
    document.getElementById('edit_empleado_id').value = '';
    
    // Limpiar mapa
    if (editMap) {
        const mapElement = document.getElementById('edit_map');
        if (mapElement) {
            mapElement.innerHTML = '';
        }
        editMap = null;
        editMarker = null;
    }
});

///  ----------------------------- Eliminar Empleado ------------------------------------------------

// Variable global para almacenar el ID del empleado a eliminar
let employeeToDeleteId = null;

// Función para abrir el modal de eliminación
function eliminarEmpleado(id) {
    console.log('🗑️ Solicitando eliminar empleado ID:', id);
    employeeToDeleteId = id;
    
    // Mostrar loading
    Swal.fire({
        title: 'Cargando...',
        text: 'Obteniendo información del empleado',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Obtener datos del empleado para mostrar en el modal
    fetch(`/admin/empleados/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Empleado no encontrado');
            }
            return response.json();
        })
        .then(data => {
            Swal.close();
            
            if (data.success) {
                console.log('✅ Datos del empleado para eliminar:', data.data);
                populateDeleteModal(data.data);
                $('#deleteEmployeeModal').modal('show');
            } else {
                throw new Error(data.message || 'Error al cargar datos del empleado');
            }
        })
        .catch(error => {
            console.error('❌ Error cargando empleado para eliminar:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los datos del empleado: ' + error.message
            });
        });
}

// Función para llenar el modal de eliminación con datos del empleado
function populateDeleteModal(empleado) {
    document.getElementById('delete_employee_name').textContent = 
        `${empleado.nombre} ${empleado.apellidos}`;
    document.getElementById('delete_employee_dni').textContent = empleado.dni || 'N/A';
    document.getElementById('delete_employee_username').textContent = empleado.username || 'N/A';
    document.getElementById('delete_employee_age').textContent = empleado.edad ? empleado.edad + ' años' : 'N/A';
    document.getElementById('delete_employee_address').textContent = empleado.domicilio || 'N/A';
}

// Función para confirmar la eliminación
function confirmDeleteEmployee() {
    if (!employeeToDeleteId) {
        showAlert('Error: ID de empleado no válido.', 'error');
        return;
    }
    
    const submitBtn = document.querySelector('#deleteEmployeeModal .btn-danger');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Eliminando...';
    submitBtn.disabled = true;
    
    // Usar fetch para enviar la solicitud DELETE
    fetch(`/admin/empleados/${employeeToDeleteId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        $('#deleteEmployeeModal').modal('hide');
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Eliminado!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Recargar DataTable y estadísticas
                if (table && $.fn.DataTable.isDataTable('#empleadosTable')) {
                    table.ajax.reload(null, false);
                    updateStats();
                }
            });
        } else {
            throw new Error(data.message || 'Error al eliminar empleado');
        }
    })
    .catch(error => {
        console.error('❌ Error eliminando empleado:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo eliminar el empleado: ' + error.message
        });
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        employeeToDeleteId = null;
    });
}

// Limpiar variable cuando se cierre el modal
$('#deleteEmployeeModal').on('hidden.bs.modal', function () {
    employeeToDeleteId = null;
});


// Variables globales para el modal de vista
let viewMap = null;
let viewMarker = null;
let currentEmployeeId = null;

// Función para abrir el modal de vista
function verEmpleado(id) {
    console.log('👁️ Viendo empleado ID:', id);
    
    // Mostrar loading
    Swal.fire({
        title: 'Cargando...',
        text: 'Obteniendo información del empleado',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Obtener datos del empleado
    fetch(`/admin/empleados/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Empleado no encontrado');
            }
            return response.json();
        })
        .then(data => {
            Swal.close();
            
            if (data.success) {
                console.log('✅ Datos del empleado para vista:', data.data);
                populateViewModal(data.data);
                $('#viewEmployeeModal').modal('show');
            } else {
                throw new Error(data.message || 'Error al cargar datos del empleado');
            }
        })
        .catch(error => {
            console.error('❌ Error cargando empleado para vista:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los datos del empleado: ' + error.message
            });
        });
}

// Función para llenar el modal de vista
function populateViewModal(empleado) {
    // Información Personal
    document.getElementById('view_id').textContent = empleado.id || 'N/A';
    document.getElementById('view_nombre').textContent = empleado.nombre || 'N/A';
    document.getElementById('view_apellidos').textContent = empleado.apellidos || 'N/A';
    document.getElementById('view_dni').textContent = empleado.dni || 'N/A';
    document.getElementById('view_fecha_nacimiento').textContent = empleado.fecha_nacimiento_formatted || 'N/A';
    document.getElementById('view_edad').textContent = empleado.edad ? empleado.edad + ' años' : 'N/A';

    // Información de Cuenta
    document.getElementById('view_username').textContent = empleado.username || 'N/A';
    document.getElementById('view_created_at').textContent = formatDateTime(empleado.created_at);
    document.getElementById('view_updated_at').textContent = formatDateTime(empleado.updated_at);

    // Domicilio y Ubicación
    document.getElementById('view_domicilio').textContent = empleado.domicilio || 'N/A';
    
    const lat = empleado.latitud || '40.4168';
    const lng = empleado.longitud || '-3.7038';
    document.getElementById('view_coordenadas').textContent = `${lat}, ${lng}`;

    // Información Adicional
    calcularInformacionAdicional(empleado);

    // Configurar botón de editar
    document.getElementById('btnEditarDesdeVista').onclick = function() {
        $('#viewEmployeeModal').modal('hide');
        setTimeout(() => editarEmpleado(empleado.id), 500);
    };

    // Inicializar mapa de vista
    initializeViewMap(empleado);
}

// Función para inicializar el mapa de vista
function initializeViewMap(empleado) {
    const lat = parseFloat(empleado.latitud) || 40.4168;
    const lng = parseFloat(empleado.longitud) || -3.7038;
    
    const mapElement = document.getElementById('view_map');
    if (!mapElement) return;

    // Limpiar mapa existente
    mapElement.innerHTML = '';

    try {
        const mapInnerDiv = document.createElement('div');
        mapInnerDiv.style.width = '100%';
        mapInnerDiv.style.height = '100%';
        mapElement.appendChild(mapInnerDiv);

        // Crear mapa
        viewMap = new google.maps.Map(mapInnerDiv, {
            zoom: 15,
            center: { lat: lat, lng: lng },
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true,
            zoomControl: true,
            styles: [
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                }
            ]
        });

        // Crear marcador
        viewMarker = new google.maps.Marker({
            map: viewMap,
            draggable: false,
            title: `${empleado.nombre} ${empleado.apellidos}`,
            position: { lat: lat, lng: lng },
            animation: google.maps.Animation.DROP
        });

        // Crear ventana de información
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div class="p-2">
                    <h6 class="mb-1">${empleado.nombre} ${empleado.apellidos}</h6>
                    <p class="mb-1 small">${empleado.domicilio || 'Dirección no disponible'}</p>
                    <p class="mb-0 small text-muted">Coordenadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}</p>
                </div>
            `
        });

        // Mostrar infoWindow al hacer clic en el marcador
        viewMarker.addListener('click', () => {
            infoWindow.open(viewMap, viewMarker);
        });

        console.log('✅ Mapa de vista inicializado');

    } catch (error) {
        console.error('❌ Error inicializando mapa de vista:', error);
        mapElement.innerHTML = `
            <div class="alert alert-warning text-center h-100 d-flex align-items-center justify-content-center">
                <div>
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <h5>Error al cargar el mapa</h5>
                    <p class="mb-0">Coordenadas: ${lat}, ${lng}</p>
                </div>
            </div>
        `;
    }
}

// Función para calcular información adicional
function calcularInformacionAdicional(empleado) {
    // Días registrado
    if (empleado.created_at) {
        const created = new Date(empleado.created_at);
        const hoy = new Date();
        const diffTime = Math.abs(hoy - created);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        document.getElementById('view_dias_registro').textContent = diffDays;
    }

    // Próximo cumpleaños
    if (empleado.fecha_nacimiento) {
        const fechaNac = new Date(empleado.fecha_nacimiento);
        const hoy = new Date();
        const proximoCumple = new Date(hoy.getFullYear(), fechaNac.getMonth(), fechaNac.getDate());
        
        if (proximoCumple < hoy) {
            proximoCumple.setFullYear(hoy.getFullYear() + 1);
        }
        
        const diffTime = Math.abs(proximoCumple - hoy);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        document.getElementById('view_proximo_cumple').textContent = `En ${diffDays} días`;
    }

    // Región (inferida desde coordenadas)
    const lat = parseFloat(empleado.latitud);
    if (lat) {
        let region = 'España';
        if (lat >= 43.5) region = 'Norte';
        else if (lat >= 40.0) region = 'Centro';
        else region = 'Sur';
        document.getElementById('view_region').textContent = region;
    }

    // Última actualización
    if (empleado.updated_at) {
        const updated = new Date(empleado.updated_at);
        const hoy = new Date();
        const diffTime = Math.abs(hoy - updated);
        const diffHours = Math.ceil(diffTime / (1000 * 60 * 60));
        
        let texto = '';
        if (diffHours < 24) {
            texto = `Hace ${diffHours} horas`;
        } else {
            const diffDays = Math.floor(diffHours / 24);
            texto = `Hace ${diffDays} días`;
        }
        document.getElementById('view_ultima_actualizacion').textContent = texto;
    }
}

// Función para formatear fecha y hora
function formatDateTime(dateTimeString) {
    if (!dateTimeString) return 'N/A';
    
    const date = new Date(dateTimeString);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Función para abrir en Google Maps
function abrirEnGoogleMaps() {
    const lat = document.getElementById('view_coordenadas').textContent.split(',')[0];
    const lng = document.getElementById('view_coordenadas').textContent.split(',')[1];
    
    if (lat && lng) {
        const url = `https://www.google.com/maps?q=${lat},${lng}`;
        window.open(url, '_blank');
    } else {
        showAlert('No hay coordenadas disponibles', 'warning');
    }
}

// Función para imprimir detalles
function imprimirDetalles() {
    const ventanaImpresion = window.open('', '_blank');
    const contenido = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Detalles del Empleado - ${document.getElementById('view_nombre').textContent}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
                .section { margin-bottom: 20px; }
                .section h3 { background: #f0f0f0; padding: 10px; border-left: 4px solid #007bff; }
                .info-row { display: flex; margin-bottom: 5px; }
                .label { font-weight: bold; width: 150px; }
                @media print {
                    .no-print { display: none; }
                    body { margin: 0; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Detalles del Empleado</h1>
                <p>Generado el ${new Date().toLocaleDateString('es-ES')}</p>
            </div>
            
            <div class="section">
                <h3>Información Personal</h3>
                <div class="info-row"><div class="label">ID:</div><div>${document.getElementById('view_id').textContent}</div></div>
                <div class="info-row"><div class="label">Nombre:</div><div>${document.getElementById('view_nombre').textContent}</div></div>
                <div class="info-row"><div class="label">Apellidos:</div><div>${document.getElementById('view_apellidos').textContent}</div></div>
                <div class="info-row"><div class="label">DNI:</div><div>${document.getElementById('view_dni').textContent}</div></div>
                <div class="info-row"><div class="label">Fecha Nacimiento:</div><div>${document.getElementById('view_fecha_nacimiento').textContent}</div></div>
                <div class="info-row"><div class="label">Edad:</div><div>${document.getElementById('view_edad').textContent}</div></div>
            </div>
            
            <div class="section">
                <h3>Información de Cuenta</h3>
                <div class="info-row"><div class="label">Username:</div><div>${document.getElementById('view_username').textContent}</div></div>
                <div class="info-row"><div class="label">Registrado:</div><div>${document.getElementById('view_created_at').textContent}</div></div>
                <div class="info-row"><div class="label">Actualizado:</div><div>${document.getElementById('view_updated_at').textContent}</div></div>
            </div>
            
            <div class="section">
                <h3>Domicilio</h3>
                <div class="info-row"><div class="label">Dirección:</div><div>${document.getElementById('view_domicilio').textContent}</div></div>
                <div class="info-row"><div class="label">Coordenadas:</div><div>${document.getElementById('view_coordenadas').textContent}</div></div>
            </div>
            
            <div class="no-print" style="margin-top: 30px; text-align: center;">
                <button onclick="window.print()">Imprimir</button>
                <button onclick="window.close()">Cerrar</button>
            </div>
        </body>
        </html>
    `;
    
    ventanaImpresion.document.write(contenido);
    ventanaImpresion.document.close();
}

// Limpiar cuando se cierre el modal
$('#viewEmployeeModal').on('hidden.bs.modal', function () {
    currentEmployeeId = null;
    
    // Limpiar mapa
    if (viewMap) {
        const mapElement = document.getElementById('view_map');
        if (mapElement) {
            mapElement.innerHTML = '';
        }
        viewMap = null;
        viewMarker = null;
    }
});

// Redimensionar mapa cuando el modal se muestre
$('#viewEmployeeModal').on('shown.bs.modal', function() {
    if (viewMap) {
        setTimeout(() => {
            google.maps.event.trigger(viewMap, 'resize');
            if (viewMarker && viewMarker.getPosition()) {
                viewMap.setCenter(viewMarker.getPosition());
            }
        }, 300);
    }
});


// Inicializar Flatpickr para el modal de exportación
function initializeExportDatepicker() {
    flatpickr("#export_mes", {
        plugins: [
            new monthSelectPlugin({
                shorthand: true,
<<<<<<< HEAD
                dateFormat: "m-Y",  // Formato YYYY-MM
=======
                dateFormat: "Y-m",  // Formato YYYY-MM
>>>>>>> db47f97ca6491ce026d72a79284a0d57d54ea54c
                altFormat: "F Y",   // Formato visual: Mes Año
                theme: "material_blue"
            })
        ],
        locale: "es",
        onChange: function(selectedDates, dateStr, instance) {
            console.log('📅 Mes seleccionado para exportar:', dateStr);
        }
    });
}

// Función para abrir el modal de exportación
function abrirModalExportar() {
    // Limpiar el campo al abrir el modal
    $('#export_mes').val('');
    $('#exportExcelModal').modal('show');
}

// Función para confirmar la exportación
function confirmarExportacion() {
    const mesSeleccionado = $('#export_mes').val().trim();
    
    if (!mesSeleccionado) {
        Swal.fire({
            icon: 'warning',
            title: 'Mes requerido',
            text: 'Por favor, seleccione un mes y año para exportar',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Convertir formato YYYY-MM a mes y año separados
    let mes, año;
    
<<<<<<< HEAD
    if (mesSeleccionado.match(/^(\d{1,2})-(\d{4})$/)) {
        // Formato MM-YYYY (ej: "10-2025")
        const partes = mesSeleccionado.split('-');
        mes = parseInt(partes[0]);
        año = parseInt(partes[1]);
=======
    if (mesSeleccionado.match(/^(\d{4})-(\d{2})$/)) {
        const partes = mesSeleccionado.split('-');
        año = parseInt(partes[0]);
        mes = parseInt(partes[1]);
>>>>>>> db47f97ca6491ce026d72a79284a0d57d54ea54c
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Formato inválido',
<<<<<<< HEAD
            text: 'El formato del mes debe ser MM-AAAA (ej: 10-2025)',
=======
            text: 'El formato del mes debe ser AAAA-MM',
>>>>>>> db47f97ca6491ce026d72a79284a0d57d54ea54c
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Validar que no sea un mes futuro
    const fechaSeleccionada = new Date(año, mes - 1);
    const hoy = new Date();
    const mesActual = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
    
    if (fechaSeleccionada > mesActual) {
        Swal.fire({
            icon: 'warning',
            title: 'Mes inválido',
            text: 'No se puede exportar meses futuros',
            confirmButtonText: 'Entendido'
        });
        return;
    }

<<<<<<< HEAD
    console.log('📤 Confirmando exportación para:', { 
        mes, 
        año, 
        mesSeleccionado,
        formato: 'MM-YYYY'
    });
=======
    console.log('📤 Confirmando exportación para:', { mes, año, mesSeleccionado });

>>>>>>> db47f97ca6491ce026d72a79284a0d57d54ea54c
    // Mostrar confirmación final
    mostrarConfirmacionExportacion(mes, año, mesSeleccionado);
}

// Función para mostrar confirmación final
function mostrarConfirmacionExportacion(mes, año, mesSeleccionado) {
    const nombreMes = getNombreMesCompleto(mes);
    
    Swal.fire({
        title: 'Confirmar Exportación',
        html: `
            <div class="text-left">
                <p>¿Está seguro que desea exportar los empleados registrados en:</p>
                <div class="alert alert-info">
                    <h5 class="text-center mb-0"><strong>${nombreMes} de ${año}</strong></h5>
                </div>
                <p class="text-muted small mt-3">
                    <i class="fas fa-info-circle"></i>
                    Se generará un archivo Excel con todos los empleados registrados durante este mes.
                </p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, Exportar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745',
        width: '500px'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#exportExcelModal').modal('hide');
            ejecutarExportacion(mes, año, nombreMes);
        }
    });
}

// Función para ejecutar la exportación
function ejecutarExportacion(mes, año, nombreMes) {
    // Mostrar loading
    Swal.fire({
        title: 'Generando Excel...',
        html: `
            <div class="text-center">
                <div class="spinner-border text-success mb-3" role="status">
                    <span class="sr-only">Generando...</span>
                </div>
                <p>Exportando empleados de <strong>${nombreMes} de ${año}</strong></p>
<<<<<<< HEAD
                <p class="text-muted small">Formato: MM-AAAA (${mes}-${año})</p>
=======
                <p class="text-muted small">Buscando empleados registrados en este período...</p>
>>>>>>> db47f97ca6491ce026d72a79284a0d57d54ea54c
            </div>
        `,
        allowOutsideClick: false,
        showConfirmButton: false,
        width: '450px'
    });

    // ✅ **CORREGIDO: Usar parámetros en la URL correctamente**
    const url = `/admin/empleados/exportar-excel-mes?mes=${mes}&año=${año}`;
    
    console.log('🔍 URL de exportación:', url);

    // Hacer la petición para exportar
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json, application/vnd.ms-excel',
        }
    })
    .then(response => {
        console.log('📋 Respuesta del servidor:', {
            status: response.status,
            ok: response.ok,
            contentType: response.headers.get('content-type')
        });

        // Si la respuesta no es OK, intentar obtener el mensaje de error
        if (!response.ok) {
            // Si es error 404 (no hay datos), manejarlo específicamente
            if (response.status === 404) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'No hay empleados en el período seleccionado');
                });
            }
            
            return response.json().then(errorData => {
                throw new Error(errorData.message || `Error ${response.status}: ${response.statusText}`);
            }).catch(() => {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            });
        }

        // Verificar si es un JSON (error) o un blob (archivo)
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json().then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Error en la generación del archivo');
                }
                throw new Error('Respuesta inesperada del servidor');
            });
        }

        // Si es un archivo Excel, devolver el blob
        return response.blob();
    })
    .then(blob => {
        // Verificar si el blob es un JSON de error disfrazado
        if (blob.type && blob.type.includes('application/json')) {
            return new Response(blob).json().then(errorData => {
                throw new Error(errorData.message || 'Error en la generación del archivo');
            });
        }

        // Verificar que el blob no esté vacío
        if (blob.size === 0) {
            throw new Error('El archivo generado está vacío. No hay datos para exportar.');
        }

        Swal.close();
        
        // Crear URL para descargar el archivo
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        
        // Nombre del archivo
        const nombreArchivo = `empleados_${getNombreMesCorto(mes)}_${año}.xlsx`;
        a.download = nombreArchivo;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        
        // Mostrar mensaje de éxito
        Swal.fire({
            icon: 'success',
            title: '¡Excel Exportado!',
            html: `
                <div class="text-left">
                    <div class="alert alert-success">
                        <h6 class="mb-2"><i class="fas fa-check-circle"></i> Exportación completada</h6>
                        <p class="mb-1"><strong>Archivo:</strong> ${nombreArchivo}</p>
                        <p class="mb-1"><strong>Período:</strong> ${nombreMes} de ${año}</p>
                        <p class="mb-0"><strong>Tamaño del archivo:</strong> ${(blob.size / 1024).toFixed(2)} KB</p>
                    </div>
                    <p class="text-muted small">
                        <i class="fas fa-download"></i>
                        El archivo se ha descargado automáticamente. 
                        Verifique su carpeta de descargas.
                    </p>
                </div>
            `,
            confirmButtonText: 'Aceptar',
            width: '500px'
        });
        
    })
    .catch(error => {
        console.error('❌ Error exportando Excel:', error);
        
        Swal.close();
        
        // Mensajes específicos según el tipo de error
        let mensajeError = error.message;
        let tituloError = 'Error al Exportar';
        
        if (error.message.includes('No hay empleados') || error.message.includes('no hay empleados')) {
            tituloError = 'Sin datos para exportar';
            mensajeError = `No se encontraron empleados registrados en <strong>${nombreMes} de ${año}</strong>`;
        } else if (error.message.includes('404') || error.message.includes('No query results')) {
            tituloError = 'Sin datos encontrados';
            mensajeError = `No hay empleados registrados en el período seleccionado: <strong>${nombreMes} de ${año}</strong>`;
        } else if (error.message.includes('500') || error.message.includes('Error interno')) {
            tituloError = 'Error del servidor';
            mensajeError = 'Ocurrió un error interno al generar el archivo. Por favor, intente más tarde.';
        } else if (error.message.includes('vacío')) {
            tituloError = 'Archivo vacío';
            mensajeError = 'No hay datos para exportar en el período seleccionado.';
        }

        Swal.fire({
            icon: 'error',
            title: tituloError,
            html: `
                <div class="text-left">
                    <div class="alert alert-warning">
                        <p class="mb-2">${mensajeError}</p>
                    </div>
                    <div class="text-muted small">
                        <p><strong>Sugerencias:</strong></p>
                        <ul class="pl-3">
                            <li>Verifique que el mes y año sean correctos</li>
                            <li>Intente con otro período diferente</li>
                            <li>Confirme que hay empleados registrados en el sistema</li>
                            <li>Verifique las fechas de registro de los empleados</li>
                        </ul>
                    </div>
                </div>
            `,
            confirmButtonText: 'Entendido',
            width: '550px'
        });
    });
}

// Función auxiliar para nombre de mes corto
function getNombreMesCorto(mes) {
    const meses = {
        1: 'ene', 2: 'feb', 3: 'mar', 4: 'abr',
        5: 'may', 6: 'jun', 7: 'jul', 8: 'ago',
        9: 'sep', 10: 'oct', 11: 'nov', 12: 'dic'
    };
    return meses[mes] || 'mes';
}
// Funciones auxiliares para nombres de meses
function getNombreMesCompleto(mes) {
    const meses = {
        1: 'Enero', 2: 'Febrero', 3: 'Marzo', 4: 'Abril',
        5: 'Mayo', 6: 'Junio', 7: 'Julio', 8: 'Agosto',
        9: 'Septiembre', 10: 'Octubre', 11: 'Noviembre', 12: 'Diciembre'
    };
    return meses[mes] || 'Mes';
}

function getNombreMesCorto(mes) {
    const meses = {
        1: 'ene', 2: 'feb', 3: 'mar', 4: 'abr',
        5: 'may', 6: 'jun', 7: 'jul', 8: 'ago',
        9: 'sep', 10: 'oct', 11: 'nov', 12: 'dic'
    };
    return meses[mes] || 'mes';
}

// Inicializar cuando el documento esté listo
$(document).ready(function() {
    initializeExportDatepicker();
    
    // Limpiar el modal cuando se cierre
    $('#exportExcelModal').on('hidden.bs.modal', function () {
        $('#export_mes').val('');
    });
    
    // Permitir Enter en el campo de mes
    $('#export_mes').on('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            confirmarExportacion();
        }
    });
});

// Función para verificar si hay datos antes de exportar
function verificarDatosAntesDeExportar(mes, año) {
    return fetch(`/admin/empleados/verificar-datos-mes?mes=${mes}&año=${año}`)
        .then(response => response.json())
        .then(data => {
            return data.existenDatos;
        })
        .catch(error => {
            console.error('Error verificando datos:', error);
            return false;
        });
}

<<<<<<< HEAD
// ✅ NUEVA FUNCIÓN: Generar preview del QR automáticamente
function generarQRPreview() {
    const dni = document.getElementById('dni').value.trim().toUpperCase();
    const nombre = document.getElementById('nombre').value.trim();
    const apellidos = document.getElementById('apellidos').value.trim();
    
    // Solo generar QR si el DNI es válido y hay nombre/apellidos
    if (dni.length === 9 && validarDNI() && nombre && apellidos) {
        const nombreCompleto = `${nombre} ${apellidos}`;
        
        // Mostrar loading en el preview
        document.getElementById('qr-preview').innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary mb-2" role="status">
                    <span class="sr-only">Generando QR...</span>
                </div>
                <p class="small text-muted">Generando código QR...</p>
            </div>
        `;
        
        // Generar QR usando una API online o librería cliente
        generarQRCliente(dni, nombreCompleto);
    } else if (dni.length > 0) {
        // Mostrar mensaje de que se necesita DNI válido
        document.getElementById('qr-preview').innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-info-circle mr-2"></i>
                Complete el DNI correctamente para generar el QR
            </div>
        `;
    } else {
        // Estado inicial
        document.getElementById('qr-preview').innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                El código QR se generará automáticamente al completar el DNI
            </div>
        `;
    }
}

// ✅ NUEVA FUNCIÓN: Generar QR del lado del cliente
function generarQRCliente(dni, nombreCompleto) {
    try {
        // Datos para el QR
        const qrData = {
            empleado_dni: dni,
            empleado_nombre: nombreCompleto,
            tipo: 'empleado',
            fecha_generacion: new Date().toISOString()
        };
        
        const qrContent = JSON.stringify(qrData);
        
        // Opción 1: Usar API de Google Charts (gratuita y sin librerías)
        generarQRConGoogleCharts(qrContent, dni);
        
    } catch (error) {
        console.error('Error generando QR cliente:', error);
        mostrarQRError();
    }
}

// ✅ FUNCIÓN: Generar QR usando Google Charts API
function generarQRConGoogleCharts(qrContent, dni) {
    const qrSize = 200;
    const encodedContent = encodeURIComponent(qrContent);
    
    // URL de la API de Google Charts para QR
    const qrUrl = `https://chart.googleapis.com/chart?cht=qr&chs=${qrSize}x${qrSize}&chl=${encodedContent}&choe=UTF-8`;
    
    // Crear elemento de imagen
    const img = new Image();
    img.src = qrUrl;
    img.alt = `QR Code para DNI: ${dni}`;
    img.className = 'qr-image';
    img.style.maxWidth = '100%';
    img.style.height = 'auto';
    
    img.onload = function() {
        document.getElementById('qr-preview').innerHTML = `
            <div class="text-center">
                <img src="${qrUrl}" alt="QR Code para DNI: ${dni}" class="qr-image" style="max-width: 100%; height: auto;">
                <p class="small text-muted mt-2">Código QR generado automáticamente</p>
            </div>
        `;
    };
    
    img.onerror = function() {
        // Fallback: generar QR con texto
        generarQRFallback(dni);
    };
}

// ✅ FUNCIÓN: Fallback para generar QR simple con texto
function generarQRFallback(dni) {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const size = 200;
    
    canvas.width = size;
    canvas.height = size;
    
    // Fondo blanco
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, size, size);
    
    // Bordes
    ctx.strokeStyle = '#007bff';
    ctx.lineWidth = 2;
    ctx.strokeRect(5, 5, size - 10, size - 10);
    
    // Texto
    ctx.fillStyle = '#000000';
    ctx.font = 'bold 14px Arial';
    ctx.textAlign = 'center';
    
    const texto1 = 'EMPLEADO';
    const texto2 = `DNI: ${dni}`;
    
    ctx.fillText(texto1, size / 2, size / 2 - 10);
    ctx.fillText(texto2, size / 2, size / 2 + 15);
    
    // Convertir a data URL
    const dataUrl = canvas.toDataURL('image/png');
    
    document.getElementById('qr-preview').innerHTML = `
        <div class="text-center">
            <img src="${dataUrl}" alt="QR Code para DNI: ${dni}" class="qr-image" style="max-width: 100%; height: auto;">
            <p class="small text-muted mt-2">QR generado localmente</p>
            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="descargarQRPreview('${dni}')">
                <i class="fas fa-download mr-1"></i> Descargar QR
            </button>
        </div>
    `;
}

// ✅ FUNCIÓN: Descargar el QR generado
function descargarQRPreview(dni) {
    const qrImage = document.querySelector('#qr-preview img');
    if (qrImage) {
        const link = document.createElement('a');
        link.download = `qr_empleado_${dni}.png`;
        link.href = qrImage.src;
        link.click();
        
        Swal.fire({
            icon: 'success',
            title: 'QR Descargado',
            text: `El código QR para DNI ${dni} se ha descargado correctamente`,
            timer: 2000,
            showConfirmButton: false
        });
    }
}

// ✅ FUNCIÓN: Mostrar error en generación de QR
function mostrarQRError() {
    document.getElementById('qr-preview').innerHTML = `
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Error al generar el código QR. Se generará en el servidor.
        </div>
    `;
}

=======
>>>>>>> db47f97ca6491ce026d72a79284a0d57d54ea54c
</script>

<!-- ******************************************** CSS ****************************************************  -->

<style>
.page-header {
    border-bottom: 1px solid #e3e6f0;
    padding-bottom: 1rem;
    margin-bottom: 2rem;
}

.card {
    border: none;
    border-radius: 0.5rem;
}

.card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.table th {
    background-color: #4e73df;
    color: white;
    border: none;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fc;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.bg-gradient-success {
    background: linear-gradient(45deg, #1cc88a, #0dbd7a);
}

.btn-group .btn {
    margin-right: 0.1rem;
    border-radius: 0.25rem;
}

/* Asegurar que ocupe todo el ancho */
.container-fluid {
    padding-left: 2rem;
    padding-right: 2rem;
}

/* Mejorar la tabla para pantallas grandes */
.table-responsive {
    overflow-x: auto;
}

/* Botones más grandes */
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}

.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.form-text.text-muted {
    font-size: 0.85rem;
}

.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #20c997);
}

.gm-style .gm-style-iw-c {
    padding: 10px;
}

.btn-ver-mapa {
    font-size: 0.8rem;
    padding: 0.25rem 0.5rem;
}
.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
}

/* Estilos para el autocompletado */
.pac-container {
    z-index: 1051 !important;
    border-radius: 0 0 5px 5px;
    border: 1px solid #ddd;
}

.pac-item {
    padding: 10px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
}

.pac-item:hover {
    background-color: #f8f9fa;
}

/* Estilos para el mapa */
#map {
    min-height: 300px;
    background-color: #f8f9fa;
}

.input-group-append .btn {
    border-radius: 0 0.375rem 0.375rem 0;
}

.modal-title {
    color: white !important;
    font-weight: 600;
}

.modal-header {
    border-bottom: none;
}

/* Estilos específicos para cada modal */
.bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #e0a800) !important;
}

.bg-gradient-danger {
    background: linear-gradient(45deg, #dc3545, #c82333) !important;
}

.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #20c997) !important;
}

/* Asegurar que el texto sea visible en los headers */
.modal-header .close {
    color: white !important;
    opacity: 0.9;
}

.modal-header .close:hover {
    opacity: 1;
}
.page-header {
    border-bottom: 1px solid #e3e6f0;
    padding-bottom: 1rem;
    margin-bottom: 1rem;
}

/* Botones responsivos */
.btn-lg-md {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

@media (min-width: 768px) {
    .btn-lg-md {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
}

/* Texto responsivo */
.small-lg {
    font-size: 0.8rem;
}

@media (min-width: 768px) {
    .small-lg {
        font-size: 0.875rem;
    }
}

/* Controles de formulario responsivos */
.form-control-lg-md {
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 0.9rem;
}

@media (min-width: 768px) {
    .form-control-lg-md {
        height: calc(1.5em + 1rem + 2px);
        padding: 0.5rem 1rem;
        font-size: 1rem;
    }
}

/* Iconos responsivos */
.fa-lg {
    font-size: 1.25em;
}

@media (min-width: 768px) {
    .fa-lg {
        font-size: 2em;
    }
}

/* Tabla responsiva */
@media (max-width: 767.98px) {
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-size: 0.8rem;
    }
    
    /* Botones de acción más pequeños en móvil */
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

/* Ajustes para tablets */
@media (min-width: 768px) and (max-width: 991.98px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}

/* Modal responsivo */
@media (max-width: 575.98px) {
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .modal-content {
        border-radius: 0.3rem;
    }
    
    .modal-body {
        padding: 1rem;
    }
}

/* Mejoras específicas para DataTables en móvil */
.dtr-details {
    font-size: 0.8rem;
}

.dtr-details li {
    border-bottom: 1px solid #efefef;
    padding: 0.5rem 0;
}

/* Botones de acción en tabla móvil */
.btn-action-group {
    display: flex;
    gap: 0.25rem;
}

.btn-action-group .btn {
    flex: 1;
    min-width: auto;
}

/* Asegurar que los elementos ocultos en móvil se muestren correctamente */
.d-md-block {
    display: none !important;
}

@media (min-width: 768px) {
    .d-md-block {
        display: block !important;
    }
    
    .d-md-inline {
        display: inline !important;
    }
    
    .d-md-inline-block {
        display: inline-block !important;
    }
}



/* Anchos responsivos */
.w-md-auto {
    width: auto !important;
}

@media (min-width: 768px) {
    .w-md-auto {
        width: auto !important;
    }
}

@media (min-width: 1200px) {
    .container, .container-lg, .container-md, .container-sm, .container-xl {
        max-width: 95rem; /* Aproximadamente 1520px */
    }
}

@media (min-width: 1400px) {
    .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
        max-width: 98rem; /* Aproximadamente 1568px */
    }
}

@media (min-width: 1600px) {
    .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
        max-width: 100rem; /* Aproximadamente 1600px */
    }
}

@media (min-width: 1800px) {
    .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
        max-width: 105rem; /* Aproximadamente 1680px */
    }
}

@media (min-width: 2000px) {
    .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
        max-width: 110rem; /* Aproximadamente 1760px */
    }
}


/* Estilos para el modal de vista */
.bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #138496) !important;
}

.card-header.bg-light {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #e3e6f0;
}

.border-0 {
    border: none !important;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

/* Mejoras para la información */
.font-weight-bold.text-muted {
    color: #6c757d !important;
    font-weight: 600 !important;
}

.badge {
    font-size: 0.75em;
}

code {
    font-family: 'Courier New', monospace;
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
}

/* Estilos para las tarjetas de información adicional */
.border.rounded {
    transition: all 0.3s ease;
}

.border.rounded:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

/* Responsive */
@media (max-width: 768px) {
    #viewEmployeeModal .modal-dialog {
        margin: 0.5rem;
    }
    
    #viewEmployeeModal .modal-body {
        padding: 1rem;
    }
    
    .btn-action-group .btn {
        margin-bottom: 0.25rem;
    }
}

/* Estilos para filtros */
.filter-status {
    padding: 8px 12px;
    border-radius: 4px;
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.filter-status.complete {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.filter-status.incomplete {
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
}

.form-control:valid {
    border-color: #28a745;
}

.form-control:invalid:not(:focus) {
    border-color: #dc3545;
}


/* Estilos para el modal de exportación */
#exportExcelModal .modal-header {
    background: linear-gradient(45deg, #28a745, #20c997) !important;
}

#exportExcelModal .modal-content {
    border: none;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

#exportExcelModal .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

#exportExcelModal .alert-info {
    background-color: #f8f9fa;
    border-color: #e9ecef;
    color: #495057;
}

<<<<<<< HEAD
/* Estilos para el campo de teléfono */
#telefono.is-valid {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

#telefono.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

/* Estilos para la sección del QR */
#qr-preview {
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.qr-image {
    max-width: 200px;
    max-height: 200px;
    border: 2px solid #dee2e6;
    border-radius: 5px;
    padding: 10px;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.qr-image:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Animación para la generación del QR */
@keyframes qr-generate {
    0% { opacity: 0; transform: scale(0.8); }
    100% { opacity: 1; transform: scale(1); }
}

.qr-generated {
    animation: qr-generate 0.5s ease-out;
}

/* Botón de descarga QR */
.btn-download-qr {
    margin-top: 10px;
    font-size: 0.8rem;
    padding: 0.25rem 0.5rem;
}

/* Estados del preview del QR */
.qr-loading {
    color: #6c757d;
}

.qr-success {
    color: #28a745;
}

.qr-error {
    color: #dc3545;
}

=======
>>>>>>> db47f97ca6491ce026d72a79284a0d57d54ea54c
</style>
@endsection