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
                                    <th width="8%" class="all"><i class="fas fa-id-card mr-1"></i> DNI</th> <!-- Ocultar en móvil -->
                                    <th width="12%" class="all">Nombre</th>
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

                    <!-- NUEVA SECCIÓN: Vista previa del QR dinámico -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white py-2">
                                    <h6 class="mb-0">
                                        <i class="fas fa-qrcode mr-2"></i> Código QR del Empleado
                                    </h6>
                                </div>
                                <div class="card-body text-center p-3">
                                    <!-- Contenedor del QR que se actualizará dinámicamente -->
                                    <div id="qr-preview">
                                        <!-- Estado inicial - se generará automáticamente -->
                                        <div class="alert alert-info">
                                            <i class="fas fa-qrcode mr-2"></i>
                                            El código QR se generará automáticamente al completar el DNI
                                        </div>
                                    </div>
                                    
                                    <!-- Información de estado -->
                                    <div id="qr-status" class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-sync-alt mr-1"></i>
                                            El QR se actualiza en tiempo real según el DNI
                                        </small>
                                    </div>
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
                    
                    <!-- Información del empleado -->
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

                    <!-- Campos editables: Domicilio y Teléfono -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_telefono" class="font-weight-bold">Teléfono *</label>
                                <input type="tel" class="form-control" id="edit_telefono" name="telefono" required 
                                       placeholder="Ej: +34 612 345 678" pattern="[+]?[0-9\s\-]+">
                                <small class="form-text text-muted">Formato internacional: +34 612 345 678</small>
                            </div>
                        </div>
                    </div>

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
                            Comience a escribir la dirección y seleccione una de las opciones sugeridas.
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
                            <i class="fas fa-info-circle"></i> Solo los campos de teléfono y domicilio pueden ser editados. 
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
                                <div class="row mb-2">
                                    <div class="col-4 font-weight-bold text-muted">Edad:</div>
                                    <div class="col-8">
                                        <span class="badge badge-info" id="view_edad">-</span>
                                    </div>
                                </div>
                                <!-- ✅ NUEVO CAMPO: Teléfono -->
                                <div class="row mb-2">
                                    <div class="col-4 font-weight-bold text-muted">Teléfono:</div>
                                    <div class="col-8">
                                        <span id="view_telefono">-</span>
                                        <button type="button" class="btn btn-sm btn-outline-success ml-2" onclick="llamarTelefono()" id="btn-llamar" style="display: none;">
                                            <i class="fas fa-phone mr-1"></i> Llamar
                                        </button>
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
                                    <div class="col mb-3">
                                        <div class="border rounded p-3 h-100 d-flex flex-column justify-content-center">
                                            <i class="fas fa-calendar-day fa-2x text-primary mb-2"></i>
                                            <h5 class="mb-1" id="view_dias_registro">0</h5>
                                            <small class="text-muted">Días registrado</small>
                                        </div>
                                    </div>
                                    <div class="col mb-3">
                                        <div class="border rounded p-3 h-100 d-flex flex-column justify-content-center">
                                            <i class="fas fa-birthday-cake fa-2x text-warning mb-2"></i>
                                            <h5 class="mb-1" id="view_proximo_cumple">-</h5>
                                            <small class="text-muted">Próximo cumpleaños</small>
                                        </div>
                                    </div>
                                    <div class="col mb-3">
                                        <div class="border rounded p-3 h-100 d-flex flex-column justify-content-center">
                                            <i class="fas fa-phone fa-2x text-info mb-2"></i>
                                            <h5 class="mb-1" id="view_formato_telefono">-</h5>
                                            <small class="text-muted">Formato teléfono</small>
                                        </div>
                                    </div>
                                    <div class="col mb-3">
                                        <div class="border rounded p-3 h-100 d-flex flex-column justify-content-center">
                                            <i class="fas fa-map-marked-alt fa-2x text-success mb-2"></i>
                                            <h5 class="mb-1" id="view_region">-</h5>
                                            <small class="text-muted">Región</small>
                                        </div>
                                    </div>
                                    <div class="col mb-3">
                                        <div class="border rounded p-3 h-100 d-flex flex-column justify-content-center">
                                            <i class="fas fa-clock fa-2x text-secondary mb-2"></i>
                                            <h5 class="mb-1" id="view_ultima_actualizacion">-</h5>
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
        passwordInput.value = soloNumeros.substring(0, 4);
    } else if (soloNumeros.length > 0) {
        passwordInput.value = soloNumeros.padEnd(4, '0');
    } else {
        passwordInput.value = '';
    }
    
    validarDNI();
    
    // ✅ NUEVO: Generar QR automáticamente
    generarQRPreview();
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
    
    // Calcular edad exacta como ENTERO
    let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
    const mes = hoy.getMonth() - fechaNacimiento.getMonth();
    const dia = hoy.getDate() - fechaNacimiento.getDate();
    
    // Ajustar si aún no ha cumplido años este año
    if (mes < 0 || (mes === 0 && dia < 0)) {
        edad--;
    }
    
    // ✅ FORZAR A ENTERO
    edad = Math.floor(edad);
    
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
    

 
    // Obtener el token CSRF del meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    if (!csrfToken) {
        console.error('❌ No se encontró el token CSRF');
        Swal.fire({
            icon: 'error',
            title: 'Error de seguridad',
            text: 'No se pudo verificar la seguridad de la solicitud. Recargue la página.'
        });
        return;
    }

    console.log('✅ Token CSRF encontrado:', csrfToken.substring(0, 20) + '...');

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
    enviarDatosAlServidor(empleadoData, Math.floor(validacionEdad.edad)); // ✅ Añadir Math.floor()
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
                            <strong>Username:</strong> ${data.data.username}<br>
                            <strong>Contraseña (4 dígitos):</strong> <code class="bg-light p-1 rounded">${data.data.password}</code><br>
                            <strong>Edad:</strong> ${data.data.edad} años<br>
                            <strong>ID Empleado:</strong> ${data.data.empleado_id}
                        </div>
                        <p class="text-info small">
                            <i class="fas fa-qrcode"></i> El código QR se ha generado y guardado correctamente.
                        </p>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'Aceptar',
                allowOutsideClick: false,
                width: '600px'
            }).then((result) => {
                if (typeof table !== 'undefined' && $.fn.DataTable.isDataTable('#empleadosTable')) {
                    table.ajax.reload(function() {
                        console.log('🔄 DataTable recargado completamente');
                        updateStats();
                    }, false);
                }
            });
        }else {
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
        año = parseInt(partes[0]);
        mes = parseInt(partes[1]);
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
    document.getElementById('edit_telefono').value = empleado.telefono || '';
    document.getElementById('edit_domicilio').value = empleado.domicilio || '';
    document.getElementById('edit_latitud').value = empleado.latitud || '40.4168';
    document.getElementById('edit_longitud').value = empleado.longitud || '-3.7038';
    document.getElementById('edit_empleado_id').value = empleado.id || '';
    
    // Actualizar información de coordenadas
    updateEditCoordinatesInfo();
    
    // Inicializar mapa de edición
    initializeEditMap();
}

// Función para validar teléfono en edición
function validarTelefonoEdit() {
    const telefonoInput = document.getElementById('edit_telefono');
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

// Event listener para validación en tiempo real en edición
document.addEventListener('DOMContentLoaded', function() {
    const telefonoEditInput = document.getElementById('edit_telefono');
    if (telefonoEditInput) {
        telefonoEditInput.addEventListener('input', validarTelefonoEdit);
        telefonoEditInput.addEventListener('blur', validarTelefonoEdit);
    }
});

// Función para actualizar el empleado
function updateEmployee() {
    const empleadoId = document.getElementById('edit_empleado_id').value;
    const telefono = document.getElementById('edit_telefono').value.trim();
    const domicilio = document.getElementById('edit_domicilio').value.trim();
    const latitud = document.getElementById('edit_latitud').value;
    const longitud = document.getElementById('edit_longitud').value;
    
    if (!telefono) {
        showAlert('Por favor, ingrese el teléfono.', 'warning');
        document.getElementById('edit_telefono').focus();
        return;
    }

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
        telefono: telefono,
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

    // ✅ NUEVO CAMPO: Teléfono
    const telefono = empleado.telefono || 'N/A';
    document.getElementById('view_telefono').textContent = telefono;
    
    // Mostrar botón de llamar solo si hay teléfono válido
    const btnLlamar = document.getElementById('btn-llamar');
    if (telefono !== 'N/A' && telefono.trim() !== '') {
        btnLlamar.style.display = 'inline-block';
        btnLlamar.setAttribute('data-telefono', telefono);
    } else {
        btnLlamar.style.display = 'none';
    }

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
    const diasRegistroElement = document.getElementById('view_dias_registro');
    if (diasRegistroElement && empleado.created_at) {
        const created = new Date(empleado.created_at);
        const hoy = new Date();
        const diffTime = Math.abs(hoy - created);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        diasRegistroElement.textContent = diffDays;
    }

    // Próximo cumpleaños
    const proximoCumpleElement = document.getElementById('view_proximo_cumple');
    if (proximoCumpleElement && empleado.fecha_nacimiento) {
        const fechaNac = new Date(empleado.fecha_nacimiento);
        const hoy = new Date();
        const proximoCumple = new Date(hoy.getFullYear(), fechaNac.getMonth(), fechaNac.getDate());
        
        if (proximoCumple < hoy) {
            proximoCumple.setFullYear(hoy.getFullYear() + 1);
        }
        
        const diffTime = Math.abs(proximoCumple - hoy);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        proximoCumpleElement.textContent = `En ${diffDays} días`;
    }

    // Formato del teléfono
    const formatoTelefonoElement = document.getElementById('view_formato_telefono');
    if (formatoTelefonoElement) {
        const telefono = empleado.telefono || '';
        let formatoTelefono = 'No disponible';
        
        if (telefono) {
            // Detectar formato del teléfono
            if (telefono.includes('+')) {
                formatoTelefono = 'Internacional';
            } else if (telefono.startsWith('6') || telefono.startsWith('7')) {
                formatoTelefono = 'Móvil ES';
            } else if (telefono.startsWith('9') || telefono.startsWith('8')) {
                formatoTelefono = 'Fijo ES';
            } else {
                formatoTelefono = 'Otro formato';
            }
        }
        formatoTelefonoElement.textContent = formatoTelefono;
    }

    // Región (inferida desde coordenadas)
    const regionElement = document.getElementById('view_region');
    if (regionElement) {
        const lat = parseFloat(empleado.latitud);
        let region = 'No especificada';
        
        if (lat) {
            if (lat >= 43.5) region = 'Norte';
            else if (lat >= 40.0) region = 'Centro';
            else region = 'Sur';
        }
        regionElement.textContent = region;
    }

    // Última actualización
    const ultimaActualizacionElement = document.getElementById('view_ultima_actualizacion');
    if (ultimaActualizacionElement && empleado.updated_at) {
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
        ultimaActualizacionElement.textContent = texto;
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


// ✅ NUEVA FUNCIÓN: Llamar al teléfono
function llamarTelefono() {
    const btnLlamar = document.getElementById('btn-llamar');
    const telefono = btnLlamar.getAttribute('data-telefono');
    
    if (!telefono) {
        showAlert('No hay número de teléfono disponible', 'warning');
        return;
    }
    
    // Limpiar el número (quitar espacios, guiones, etc.)
    const telefonoLimpio = telefono.replace(/[\s\-\(\)]/g, '');
    
    Swal.fire({
        title: '¿Llamar al empleado?',
        html: `
            <div class="text-left">
                <p>¿Desea llamar al número:</p>
                <div class="alert alert-info text-center">
                    <h4 class="mb-0"><i class="fas fa-phone"></i> ${telefono}</h4>
                </div>
                <p class="text-muted small mt-2">
                    <i class="fas fa-info-circle"></i>
                    Esta acción abrirá su aplicación de teléfono predeterminada.
                </p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-phone mr-1"></i> Sí, Llamar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745',
        width: '450px'
    }).then((result) => {
        if (result.isConfirmed) {
            // Abrir enlace tel: para dispositivos móviles
            const linkLlamada = document.createElement('a');
            linkLlamada.href = `tel:${telefonoLimpio}`;
            linkLlamada.style.display = 'none';
            document.body.appendChild(linkLlamada);
            linkLlamada.click();
            document.body.removeChild(linkLlamada);
            
            // Para desktop, mostrar mensaje
            if (!/Mobi|Android/i.test(navigator.userAgent)) {
                Swal.fire({
                    icon: 'info',
                    title: 'Llamada simulada',
                    html: `
                        <div class="text-left">
                            <p>En un dispositivo móvil se abriría la aplicación de teléfono.</p>
                            <div class="alert alert-warning">
                                <strong>Número:</strong> ${telefono}<br>
                                <strong>Formateado:</strong> ${telefonoLimpio}
                            </div>
                        </div>
                    `,
                    confirmButtonText: 'Entendido'
                });
            }
        }
    });
}

// ✅ NUEVA FUNCIÓN: Copiar teléfono al portapapeles
function copiarTelefono() {
    const telefono = document.getElementById('view_telefono').textContent;
    
    if (telefono === 'N/A' || !telefono.trim()) {
        showAlert('No hay número de teléfono para copiar', 'warning');
        return;
    }
    
    navigator.clipboard.writeText(telefono).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Teléfono copiado',
            text: `Número ${telefono} copiado al portapapeles`,
            timer: 1500,
            showConfirmButton: false
        });
    }).catch(() => {
        // Fallback para navegadores antiguos
        const tempInput = document.createElement('input');
        tempInput.value = telefono;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        Swal.fire({
            icon: 'success',
            title: 'Teléfono copiado',
            text: `Número ${telefono} copiado al portapapeles`,
            timer: 1500,
            showConfirmButton: false
        });
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
                <div class="info-row"><div class="label">Teléfono:</div><div>${document.getElementById('view_telefono').textContent}</div></div>
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
            
            <div class="section">
                <h3>Información Adicional</h3>
                <div class="info-row"><div class="label">Días registrado:</div><div>${document.getElementById('view_dias_registro').textContent}</div></div>
                <div class="info-row"><div class="label">Próximo cumpleaños:</div><div>${document.getElementById('view_proximo_cumple').textContent}</div></div>
                <div class="info-row"><div class="label">Formato teléfono:</div><div>${document.getElementById('view_formato_telefono').textContent}</div></div>
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
                dateFormat: "m-Y",  // Formato YYYY-MM
                dateFormat: "Y-m",  // Formato YYYY-MM
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
    
    if (mesSeleccionado.match(/^(\d{1,2})-(\d{4})$/)) {
        // Formato MM-YYYY (ej: "10-2025")
        const partes = mesSeleccionado.split('-');
        mes = parseInt(partes[0]);
        año = parseInt(partes[1]);
    }

    if (mesSeleccionado.match(/^(\d{4})-(\d{2})$/)) {
        const partes = mesSeleccionado.split('-');
        año = parseInt(partes[0]);
        mes = parseInt(partes[1]);
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Formato inválido',
            text: 'El formato del mes debe ser MM-AAAA (ej: 10-2025)',
            text: 'El formato del mes debe ser AAAA-MM',
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

    console.log('📤 Confirmando exportación para:', { 
        mes, 
        año, 
        mesSeleccionado,
        formato: 'MM-YYYY'
    });
    console.log('📤 Confirmando exportación para:', { mes, año, mesSeleccionado });

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
            </div>
        `,
        allowOutsideClick: false,
        showConfirmButton: false
    });

    // ✅ URL corregida
    const url = `/admin/empleados/exportar-excel-mes?mes=${mes}&año=${año}`;
    
    console.log('🔍 URL de exportación:', url);

    // Hacer la petición
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => {
        console.log('📋 Respuesta del servidor:', response);
        
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || `Error ${response.status}`);
            });
        }
        
        return response.blob();
    })
    .then(blob => {
        Swal.close();
        
        // Crear URL para descargar
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        
        const nombreArchivo = `empleados_${getNombreMesCorto(mes)}_${año}.xlsx`;
        a.download = nombreArchivo;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        
        // Mensaje de éxito
        Swal.fire({
            icon: 'success',
            title: '¡Excel Exportado!',
            text: `Archivo ${nombreArchivo} descargado correctamente`,
            confirmButtonText: 'Aceptar'
        });
    })
    .catch(error => {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'Error al Exportar',
            text: error.message,
            confirmButtonText: 'Entendido'
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


// ✅ FUNCIÓN MEJORADA: Generar QR automáticamente por DNI en tiempo real
// ✅ FUNCIÓN MEJORADA: Generar QR automáticamente por DNI
function generarQRPreview() {
    const dni = document.getElementById('dni').value.trim().toUpperCase();
    const nombre = document.getElementById('nombre').value.trim();
    const apellidos = document.getElementById('apellidos').value.trim();
    const qrPreview = document.getElementById('qr-preview');
    const qrStatus = document.getElementById('qr-status');
    
    // Estado inicial
    if (!dni) {
        qrPreview.innerHTML = `
            <div class="alert alert-info text-center">
                <i class="fas fa-qrcode fa-2x mb-3 text-muted"></i>
                <h6 class="mb-1">Código QR del Empleado</h6>
                <p class="small mb-0">Ingrese el DNI para generar el código QR automáticamente</p>
            </div>
        `;
        return;
    }
    
    // Mostrar progreso según longitud del DNI
    if (dni.length < 9) {
        const porcentaje = Math.round((dni.length / 9) * 100);
        qrPreview.innerHTML = `
            <div class="text-center">
                <div class="mb-3">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" 
                             style="width: ${porcentaje}%"></div>
                    </div>
                    <small class="text-muted">DNI: ${dni.length}/9 caracteres (${porcentaje}%)</small>
                </div>
                <div class="text-muted">
                    <i class="fas fa-qrcode fa-3x mb-2 opacity-50"></i>
                    <p class="small mb-0">Complete el DNI para ver el QR</p>
                </div>
            </div>
        `;
        return;
    }
    
    // DNI completo - generar QR
    if (dni.length === 9) {
        // Validar formato DNI
        const dniRegex = /^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i;
        if (!dniRegex.test(dni)) {
            qrPreview.innerHTML = `
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <h6 class="mb-1">DNI Inválido</h6>
                    <p class="small mb-0">Formato incorrecto. Use: 8 números + 1 letra</p>
                </div>
            `;
            return;
        }

        // Validar letra del DNI
        const numero = dni.substring(0, 8);
        const letra = dni.substring(8, 9).toUpperCase();
        const letrasValidas = 'TRWAGMYFPDXBNJZSQVHLCKE';
        const letraCalculada = letrasValidas[numero % 23];
        
        if (letra !== letraCalculada) {
            qrPreview.innerHTML = `
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <h6 class="mb-1">Letra de DNI Incorrecta</h6>
                    <p class="small mb-0">La letra debería ser: <strong>${letraCalculada}</strong></p>
                </div>
            `;
            return;
        }

        // Mostrar loading
        qrPreview.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only">Generando QR...</span>
                </div>
                <p class="small text-muted">Generando QR para DNI: <strong>${dni}</strong></p>
            </div>
        `;

        // Llamar al servidor para generar QR
        generarQRDesdeServidor(dni, nombre, apellidos);
    }
}

// ✅ NUEVA FUNCIÓN: Generar QR desde el servidor
function generarQRDesdeServidor(dni, nombre, apellidos) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/admin/empleados/generar-qr-preview', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            dni: dni,
            nombre: nombre,
            apellidos: apellidos
        })
    })
    .then(response => response.json())
    .then(data => {
        const qrPreview = document.getElementById('qr-preview');
        const qrStatus = document.getElementById('qr-status');
        
        if (data.success && data.qr_image) {
            // Mostrar el QR generado
            qrPreview.innerHTML = `
                <div class="text-center">
                    <img src="data:image/png;base64,${data.qr_image}" 
                         alt="QR Code para DNI: ${dni}" 
                         class="img-fluid rounded border shadow-sm qr-generated" 
                         style="max-width: 200px; transition: all 0.3s ease;">
                    <p class="small text-success mt-2">
                        <i class="fas fa-check-circle mr-1"></i>
                        QR generado para DNI: <strong>${dni}</strong>
                    </p>
                    <div class="mt-2">
                        <small class="text-info">
                            <i class="fas fa-info-circle mr-1"></i>
                            Este QR se guardará al crear el empleado
                        </small>
                    </div>
                </div>
            `;
        } else {
            throw new Error(data.message || 'Error generando QR');
        }
    })
    .catch(error => {
        console.error('Error generando QR:', error);
        const qrPreview = document.getElementById('qr-preview');
        qrPreview.innerHTML = `
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <h6 class="mb-1">Error Generando QR</h6>
                <p class="small mb-0">${error.message}</p>
            </div>
        `;
    });
}

// ✅ FUNCIÓN FALLBACK: Usar Google Charts si el servidor falla
function generarQRConGoogleChartsFallback(dni, nombre, apellidos) {
    const qrPreview = document.getElementById('qr-preview');
    const qrStatus = document.getElementById('qr-status');
    const nombreCompleto = `${nombre} ${apellidos}`.trim();
    
    // Datos para el QR
    const qrData = {
        empleado_dni: dni,
        empleado_nombre: nombreCompleto,
        tipo: 'empleado',
        fecha_generacion: new Date().toISOString()
    };
    
    const qrContent = JSON.stringify(qrData);
    const qrUrl = `https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=${encodeURIComponent(qrContent)}&choe=UTF-8&chld=H|2`;
    
    qrPreview.innerHTML = `
        <div class="text-center">
            <img src="${qrUrl}" 
                 alt="QR Code para DNI: ${dni}" 
                 class="img-fluid rounded border shadow-sm qr-generated" 
                 style="max-width: 200px; transition: all 0.3s ease;">
            <p class="small text-success mt-2">
                <i class="fas fa-check-circle mr-1"></i>
                QR generado para DNI: <strong>${dni}</strong>
            </p>
            <div class="mt-2">
                <small class="text-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    Este QR se guardará al crear el empleado
                </small>
            </div>
        </div>
    `;
    
    // Actualizar estado
    if (qrStatus) {
        qrStatus.innerHTML = `
            <div class="alert alert-success py-1 mb-0">
                <i class="fas fa-check-circle mr-1"></i>
                <strong>QR listo</strong> - Generado automáticamente
            </div>
        `;
    }
}

// ✅ AGREGAR este event listener al DNI
document.addEventListener('DOMContentLoaded', function() {
    const dniInput = document.getElementById('dni');
    if (dniInput) {
         dniInput.addEventListener('input', function() {
            clearTimeout(window.qrTimeout);
            window.qrTimeout = setTimeout(generarQRPreview, 500);
        });
    }
});

// ✅ FUNCIÓN FALLBACK: Generar QR del lado del cliente si falla el servidor
function generarQRClienteFallback(dni, nombre, apellidos) {
    const qrPreview = document.getElementById('qr-preview');
    const nombreCompleto = `${nombre} ${apellidos}`.trim();
    
    // Crear canvas para QR básico
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const size = 200;
    
    canvas.width = size;
    canvas.height = size;
    
    // Fondo blanco
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, size, size);
    
    // Generar patrón QR básico
    generarPatronQRBasico(ctx, size, dni);
    
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
    
    qrPreview.innerHTML = `
        <div class="text-center">
            <img src="${dataUrl}" alt="QR Code para DNI: ${dni}" class="qr-image" style="max-width: 200px;">
            <p class="small text-warning mt-2">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                QR generado localmente
            </p>
            <div class="mt-2">
                <small class="text-muted">
                    <i class="fas fa-info-circle mr-1"></i>
                    El QR final se generará al crear el empleado
                </small>
            </div>
        </div>
    `;
}

// ✅ FUNCIÓN: Generar patrón QR básico
function generarPatronQRBasico(ctx, size, dni) {
    const cellSize = 8;
    const cells = Math.floor(size / cellSize);
    
    // Colores
    const colorNegro = '#000000';
    
    // Marcadores de posición (esquinas)
    ctx.fillStyle = colorNegro;
    
    // Esquina superior izquierda
    for (let i = 0; i < 7; i++) {
        for (let j = 0; j < 7; j++) {
            if ((i < 2 || i > 4) && (j < 2 || j > 4)) {
                ctx.fillRect(i * cellSize, j * cellSize, cellSize, cellSize);
            }
        }
    }
    
    // Esquina superior derecha
    for (let i = 0; i < 7; i++) {
        for (let j = 0; j < 7; j++) {
            if ((i < 2 || i > 4) && (j < 2 || j > 4)) {
                ctx.fillRect((cells - 1 - i) * cellSize, j * cellSize, cellSize, cellSize);
            }
        }
    }
    
    // Esquina inferior izquierda
    for (let i = 0; i < 7; i++) {
        for (let j = 0; j < 7; j++) {
            if ((i < 2 || i > 4) && (j < 2 || j > 4)) {
                ctx.fillRect(i * cellSize, (cells - 1 - j) * cellSize, cellSize, cellSize);
            }
        }
    }
    
    // Patrón aleatorio interno (usando DNI como semilla para consistencia)
    const seed = Array.from(dni).reduce((acc, char) => acc + char.charCodeAt(0), 0);
    
    for (let i = 7; i < cells - 7; i++) {
        for (let j = 7; j < cells - 7; j++) {
            // Evitar área central para el texto
            if (!(i >= Math.floor(cells/2) - 2 && i <= Math.floor(cells/2) + 2 &&
                  j >= Math.floor(cells/2) - 4 && j <= Math.floor(cells/2) + 4)) {
                
                // Usar una función pseudo-aleatoria basada en la semilla
                const valor = Math.sin(i * 0.7 + seed) * Math.cos(j * 0.7 + seed) + Math.sin(i * j * 0.01);
                
                if (valor > 0.3) {
                    ctx.fillRect(i * cellSize, j * cellSize, cellSize, cellSize);
                }
            }
        }
    }
}

// ✅ FUNCIÓN: Generar QR del lado del cliente (SIN BOTÓN DESCARGAR)
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

// ✅ FUNCIÓN: Generar QR usando Google Charts API (SIN BOTÓN DESCARGAR)
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
                <!-- SE ELIMINÓ EL BOTÓN DESCARGAR QR -->
            </div>
        `;
    };
    
    img.onerror = function() {
        // Fallback: generar QR con texto
        generarQRFallback(dni);
    };
}

// ✅ FUNCIÓN: Fallback para generar QR simple con texto (SIN BOTÓN DESCARGAR)
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
            <!-- SE ELIMINÓ EL BOTÓN DESCARGAR QR -->
        </div>
    `;
}

// ✅ FUNCIÓN: Generar QR progresivo del lado del cliente
function generarQRProgresivoCliente(dni, nombre, apellidos) {
    const canvas = document.getElementById('qrCanvas');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const size = 150;
    const progreso = dni.length / 9; // 0 a 1
    
    // Limpiar canvas
    ctx.clearRect(0, 0, size, size);
    
    // Fondo blanco
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, size, size);
    
    // Generar patrón progresivo
    generarPatronQRProgresivo(ctx, size, progreso, dni);
}

// ✅ FUNCIÓN: Generar patrón QR progresivo
function generarPatronQRProgresivo(ctx, size, progreso, dni) {
    const cellSize = 6;
    const cells = Math.floor(size / cellSize);
    
    // Colores
    const colorCompleto = '#000000';
    const colorParcial = '#666666';
    const colorClaro = '#cccccc';
    
    // Patrón de posicionamiento (esquinas - siempre visibles)
    ctx.fillStyle = colorCompleto;
    
    // Esquina superior izquierda
    for (let i = 0; i < 7; i++) {
        for (let j = 0; j < 7; j++) {
            if ((i < 2 || i > 4) && (j < 2 || j > 4)) {
                ctx.fillRect(i * cellSize, j * cellSize, cellSize, cellSize);
            }
        }
    }
    
    // Esquina superior derecha
    for (let i = 0; i < 7; i++) {
        for (let j = 0; j < 7; j++) {
            if ((i < 2 || i > 4) && (j < 2 || j > 4)) {
                ctx.fillRect((cells - 1 - i) * cellSize, j * cellSize, cellSize, cellSize);
            }
        }
    }
    
    // Esquina inferior izquierda
    for (let i = 0; i < 7; i++) {
        for (let j = 0; j < 7; j++) {
            if ((i < 2 || i > 4) && (j < 2 || j > 4)) {
                ctx.fillRect(i * cellSize, (cells - 1 - j) * cellSize, cellSize, cellSize);
            }
        }
    }
    
    // Patrón progresivo interno
    for (let i = 7; i < cells - 7; i++) {
        for (let j = 7; j < cells - 7; j++) {
            const deberiaEstarLleno = calcularCeldaQR(i, j, cells, progreso, dni);
            
            if (deberiaEstarLleno === 'completo') {
                ctx.fillStyle = colorCompleto;
                ctx.fillRect(i * cellSize, j * cellSize, cellSize, cellSize);
            } else if (deberiaEstarLleno === 'parcial') {
                ctx.fillStyle = colorParcial;
                ctx.fillRect(i * cellSize, j * cellSize, cellSize, cellSize);
            } else if (deberiaEstarLleno === 'claro') {
                ctx.fillStyle = colorClaro;
                ctx.fillRect(i * cellSize, j * cellSize, cellSize, cellSize);
            }
        }
    }
}

// ✅ FUNCIÓN: Calcular estado de cada celda del QR
function calcularCeldaQR(x, y, totalCells, progreso, dni) {
    // Usar el DNI como semilla para consistencia
    const seed = Array.from(dni).reduce((acc, char) => acc + char.charCodeAt(0), 0);
    const valor = Math.sin(x * 0.7 + seed) * Math.cos(y * 0.7 + seed) + Math.sin(x * y * 0.01);
    
    // Ajustar umbrales basados en el progreso
    const umbralCompleto = progreso * 0.8;
    const umbralParcial = progreso * 0.5;
    const umbralClaro = progreso * 0.3;
    
    if (valor > umbralCompleto) {
        return 'completo';
    } else if (valor > umbralParcial) {
        return 'parcial';
    } else if (valor > umbralClaro) {
        return 'claro';
    }
    
    return null;
}

// ✅ FUNCIÓN: Generar QR completo desde el servidor
function generarQRCompleto(dni, nombre, apellidos) {
    const qrPreview = document.getElementById('qr-preview');
    
    // Mostrar loading
    qrPreview.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="sr-only">Generando QR final...</span>
            </div>
            <p class="small text-muted">Generando código QR final...</p>
        </div>
    `;
    
    // Llamar al servidor para generar QR completo
    fetch('/admin/empleados/generar-qr-preview', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            dni: dni,
            nombre: nombre,
            apellidos: apellidos
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            qrPreview.innerHTML = `
                <div class="text-center">
                    <img src="data:image/png;base64,${data.qr_image}" 
                         alt="QR Code para DNI: ${dni}" 
                         class="qr-image img-fluid" 
                         style="max-width: 200px;">
                    <p class="small text-success mt-2">
                        <i class="fas fa-check-circle mr-1"></i>
                        QR generado correctamente
                    </p>
                    <div class="mt-2">
                        <small class="text-info">
                            <i class="fas fa-info-circle mr-1"></i>
                            Este QR identificará al empleado en el sistema
                        </small>
                    </div>
                </div>
            `;
        } else {
            throw new Error(data.message || 'Error generando QR');
        }
    })
    .catch(error => {
        console.error('Error generando QR completo:', error);
        qrPreview.innerHTML = `
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Error generando QR
                <p class="small mb-0 mt-1">${error.message}</p>
            </div>
        `;
    });
}



// ✅ FUNCIÓN: Mostrar QR simulado (será reemplazado por el real del servidor)
function mostrarQRSimulado(dni, nombreCompleto) {
    // Crear un canvas para un QR simulado más realista
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const size = 200;
    
    canvas.width = size;
    canvas.height = size;
    
    // Fondo blanco
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, size, size);
    
    // Generar patrón de QR básico
    generarPatronQR(ctx, size);
    
    // Convertir a data URL
    const dataUrl = canvas.toDataURL('image/png');
    
    document.getElementById('qr-preview').innerHTML = `
        <div class="text-center">
            <img src="${dataUrl}" alt="QR Code para DNI: ${dni}" class="qr-image img-fluid" style="max-width: 200px;">
            <p class="small text-muted mt-2">
                <i class="fas fa-qrcode mr-1"></i>
                Vista previa del código QR
            </p>
            <div class="mt-2">
                <small class="text-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    El QR final se generará al crear el empleado
                </small>
            </div>
        </div>
    `;
}

// ✅ FUNCIÓN: Generar patrón básico de QR
function generarPatronQR(ctx, size) {
    const cellSize = 10;
    const cells = size / cellSize;
    
    ctx.fillStyle = '#000000';
    
    // Patrón de posicionamiento (esquinas)
    for (let i = 0; i < 7; i++) {
        for (let j = 0; j < 7; j++) {
            // Esquina superior izquierda
            if ((i < 2 || i > 4) && (j < 2 || j > 4)) {
                ctx.fillRect(i * cellSize, j * cellSize, cellSize, cellSize);
            }
            
            // Esquina superior derecha
            ctx.fillRect((cells - 1 - i) * cellSize, j * cellSize, cellSize, cellSize);
            
            // Esquina inferior izquierda
            ctx.fillRect(i * cellSize, (cells - 1 - j) * cellSize, cellSize, cellSize);
        }
    }
    
    // Patrón aleatorio interno (simulación)
    for (let i = 7; i < cells - 7; i++) {
        for (let j = 7; j < cells - 7; j++) {
            if (Math.random() > 0.5) {
                ctx.fillRect(i * cellSize, j * cellSize, cellSize, cellSize);
            }
        }
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

// ✅ FUNCIÓN: Descargar el QR generado
function descargarQRPreview(dni) {
    const qrImage = document.querySelector('#qr-preview img');
    if (qrImage && qrImage.src && !qrImage.src.includes('svg+xml')) {
        const link = document.createElement('a');
        link.download = `qr_empleado_${dni}_${new Date().getTime()}.png`;
        link.href = qrImage.src;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Mostrar confirmación
        Swal.fire({
            icon: 'success',
            title: 'QR Descargado',
            text: `El código QR para DNI ${dni} se ha descargado correctamente`,
            timer: 2000,
            showConfirmButton: false
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error al descargar',
            text: 'No hay código QR disponible para descargar'
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

// ✅ FUNCIÓN COMPLETA: Imprimir QR por la web
function imprimirQR(id) {
    console.log('🖨️ Solicitando impresión de QR para empleado ID:', id);
    
    Swal.fire({
        title: 'Generando QR para impresión...',
        text: 'Preparando código QR',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/admin/empleados/${id}/qr-info`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener información del QR');
            }
            return response.json();
        })
        .then(data => {
            Swal.close();
            
            if (data.success) {
                mostrarModalImpresionQR(data.data);
            } else {
                throw new Error(data.message || 'Error al obtener información del QR');
            }
        })
        .catch(error => {
            console.error('❌ Error obteniendo QR:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo obtener el código QR: ' + error.message
            });
        });
}


// ✅ FUNCIÓN: Imprimir QR directamente
function imprimirQRDirecto() {
    // Ocultar elementos no necesarios para impresión
    const elementosOcultar = document.querySelectorAll('#imprimirQRModal .modal-header, #imprimirQRModal .modal-footer, #imprimirQRModal .btn-group, #imprimirQRModal .card-header:not(.bg-primary)');
    elementosOcultar.forEach(el => el.classList.add('d-none'));
    
    // Mostrar área de impresión
    document.getElementById('area-impresion').classList.remove('d-none');
    
    // Esperar un momento para que se renderice y luego imprimir
    setTimeout(() => {
        window.print();
        
        // Restaurar vista después de imprimir
        setTimeout(() => {
            elementosOcultar.forEach(el => el.classList.remove('d-none'));
            document.getElementById('area-impresion').classList.add('d-none');
        }, 500);
    }, 500);
}

// ✅ FUNCIÓN: Mostrar modal de impresión de QR
function mostrarModalImpresionQR(qrData) {
    const modalHtml = `
        <div class="modal fade" id="imprimirQRModal" tabindex="-1" role="dialog" aria-labelledby="imprimirQRModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="imprimirQRModalLabel">
                            <i class="fas fa-qrcode mr-2"></i> Imprimir Código QR
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-user mr-2 text-primary"></i>Información del Empleado
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-4 font-weight-bold text-muted">Nombre:</div>
                                            <div class="col-8">${qrData.nombre_completo}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-4 font-weight-bold text-muted">DNI:</div>
                                            <div class="col-8"><span class="badge badge-primary">${qrData.dni}</span></div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-4 font-weight-bold text-muted">Usuario:</div>
                                            <div class="col-8"><code>${qrData.username}</code></div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-4 font-weight-bold text-muted">Código:</div>
                                            <div class="col-8"><small class="text-muted">${qrData.codigo_unico}</small></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4 font-weight-bold text-muted">Generado:</div>
                                            <div class="col-8"><small class="text-muted">${qrData.fecha_generacion}</small></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <div class="btn-group w-100">
                                        <button type="button" class="btn btn-success" onclick="descargarQR(${qrData.empleado_id})">
                                            <i class="fas fa-download mr-1"></i> Descargar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-qrcode mr-2 text-success"></i>Código QR
                                        </h6>
                                    </div>
                                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                        <img src="data:image/png;base64,${qrData.qr_image}" 
                                             alt="QR Code para ${qrData.nombre_completo}" 
                                             class="img-fluid rounded border shadow mb-3"
                                             style="max-width: 200px;">
                                        <p class="text-center text-muted small">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Este código QR identifica al empleado en el sistema
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Área de impresión (oculta inicialmente) -->
                        <div id="area-impresion" class="mt-4 d-none">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white text-center">
                                    <h4 class="mb-0">CÓDIGO QR DEL EMPLEADO</h4>
                                </div>
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col-md-8 mx-auto">
                                            <img src="data:image/png;base64,${qrData.qr_image}" 
                                                 alt="QR Code para ${qrData.nombre_completo}" 
                                                 class="img-fluid mb-3"
                                                 style="max-width: 250px;">
                                            
                                            <h5 class="mb-1">${qrData.nombre_completo}</h5>
                                            <p class="mb-1"><strong>DNI:</strong> ${qrData.dni}</p>
                                            <p class="mb-1"><strong>Usuario:</strong> ${qrData.username}</p>
                                            <p class="mb-1"><strong>Código único:</strong> <small>${qrData.codigo_unico}</small></p>
                                            <p class="mb-0 text-muted"><small>Generado: ${qrData.fecha_generacion} | Impreso: ${new Date().toLocaleDateString('es-ES')}</small></p>
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
                        <button type="button" class="btn btn-primary" onclick="imprimirQRDirecto()">
                            <i class="fas fa-print mr-1"></i> Imprimir QR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remover modal anterior si existe
    $('#imprimirQRModal').remove();
    
    // Añadir nuevo modal al body
    $('body').append(modalHtml);
    
    // Mostrar modal
    $('#imprimirQRModal').modal('show');
}

// ✅ FUNCIÓN: Imprimir QR directamente
function imprimirQRDirecto() {
    // Ocultar elementos no necesarios para impresión
    const elementosOcultar = document.querySelectorAll('.modal-header, .modal-footer, .btn-group, .card-header:not(.bg-primary)');
    elementosOcultar.forEach(el => el.classList.add('d-none'));
    
    // Mostrar área de impresión
    document.getElementById('area-impresion').classList.remove('d-none');
    
    // Esperar un momento para que se renderice y luego imprimir
    setTimeout(() => {
        window.print();
        
        // Restaurar vista después de imprimir
        setTimeout(() => {
            elementosOcultar.forEach(el => el.classList.remove('d-none'));
            document.getElementById('area-impresion').classList.add('d-none');
        }, 500);
    }, 500);
}

// ✅ FUNCIÓN: Enviar QR por WhatsApp
function enviarQRWhatsApp(empleadoId) {
    console.log('📱 Enviando QR por WhatsApp para empleado ID:', empleadoId);
    
    Swal.fire({
        title: 'Enviar QR por WhatsApp',
        html: `
            <div class="text-left">
                <p>Ingrese el número de teléfono para enviar el QR:</p>
                <input type="tel" id="whatsappTelefono" class="swal2-input" 
                       placeholder="Ej: +34 612 345 678" required>
                <small class="form-text text-muted">
                    Incluya el código de país (ej: +34 para España)
                </small>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="fab fa-whatsapp mr-1"></i> Enviar por WhatsApp',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#25D366',
        preConfirm: () => {
            const telefono = document.getElementById('whatsappTelefono').value.trim();
            if (!telefono) {
                Swal.showValidationMessage('Por favor, ingrese un número de teléfono');
                return false;
            }
            return telefono;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const telefono = result.value;
            
            Swal.fire({
                title: 'Generando enlace...',
                text: 'Preparando enlace de WhatsApp',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/empleados/${empleadoId}/enviar-whatsapp`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    telefono: telefono
                })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Enlace listo!',
                        html: `
                            <div class="text-left">
                                <p>Enlace de WhatsApp generado para:</p>
                                <div class="alert alert-success">
                                    <strong>Teléfono:</strong> ${data.data.telefono}<br>
                                    <strong>Empleado:</strong> ${data.data.empleado}
                                </div>
                                <p class="text-muted small">
                                    <i class="fas fa-info-circle"></i>
                                    Se abrirá WhatsApp con el mensaje predefinido
                                </p>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: '<i class="fab fa-whatsapp mr-1"></i> Abrir WhatsApp',
                        cancelButtonText: 'Cerrar',
                        confirmButtonColor: '#25D366'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Abrir WhatsApp en nueva pestaña
                            window.open(data.data.whatsapp_url, '_blank');
                        }
                    });
                } else {
                    throw new Error(data.message || 'Error al generar enlace de WhatsApp');
                }
            })
            .catch(error => {
                console.error('❌ Error enviando por WhatsApp:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo generar el enlace de WhatsApp: ' + error.message
                });
            });
        }
    });
}

// ✅ FUNCIÓN: Descargar QR
function descargarQR(empleadoId) {
    fetch(`/admin/empleados/${empleadoId}/qr-info`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const qrData = data.data;
                
                // Crear enlace de descarga
                const link = document.createElement('a');
                link.download = `qr_empleado_${qrData.dni}_${new Date().getTime()}.png`;
                link.href = `data:image/png;base64,${qrData.qr_image}`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                Swal.fire({
                    icon: 'success',
                    title: 'QR Descargado',
                    text: `El código QR para ${qrData.nombre_completo} se ha descargado correctamente`,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                throw new Error(data.message || 'Error al obtener QR para descarga');
            }
        })
        .catch(error => {
            console.error('❌ Error descargando QR:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo descargar el código QR: ' + error.message
            });
        });
}

</script>

@endsection


@section('css')
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


/* ===== CORRECCIONES PARA DATATABLE ===== */

/* Contenedor principal de DataTables */
.dataTables_wrapper {
    padding: 0 15px;
    position: relative;
}

/* Fila de controles (length + filter) */
.dataTables_wrapper .row:first-child {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding: 0 10px;
}

/* Mostrar registros - IZQUIERDA */
.dataTables_length {
    order: 1;
    margin-left: 1rem;
}

.dataTables_length label {
    display: flex;
    align-items: center;
    margin-bottom: 0;
    font-weight: 600 !important;
    color: #495057 !important;
    font-size: 0.9rem !important;
}

.dataTables_length select {
    margin: 0 8px !important;
    padding: 0.4rem 1rem !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.35rem !important;
    background-color: white !important;
    min-width: 100px !important;
    width: auto !important;
    font-size: 0.9rem !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.75rem center !important;
    background-size: 16px 12px !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
}

/* Buscador - DERECHA */
.dataTables_filter {
    order: 2;
    margin-right: 1rem;
}

.dataTables_filter label {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    margin-bottom: 0;
    font-weight: 600 !important;
    color: #495057 !important;
    font-size: 0.9rem !important;
}

.dataTables_filter input {
    margin-left: 12px !important;
    padding: 0.5rem 0.75rem !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.35rem !important;
    width: auto !important;
    min-width: 220px !important;
    font-size: 0.9rem !important;
    transition: all 0.3s ease !important;
}

.dataTables_filter input:focus {
    border-color: #4e73df !important;
    outline: 0 !important;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25) !important;
}


/* Separación mejorada para la tabla */
.table-responsive {
    margin-top: 1rem !important;
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem !important;
    overflow: hidden;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1) !important;
}

/* Información - IZQUIERDA */
.dataTables_info {
    order: 1;
    padding-top: 0.75rem;
    color: #6c757d;
}

/* Paginación - DERECHA */
.dataTables_paginate {
    order: 2;
    padding-top: 0.75rem;
    text-align: right;
}

/* Estilos para la paginación */
.pagination {
    margin-bottom: 0;
    justify-content: flex-end;
}

.page-item.active .page-link {
    background-color: #4e73df;
    border-color: #4e73df;
}

.page-link {
    color: #4e73df;
    border: 1px solid #dddfeb;
}

.page-link:hover {
    color: #2e59d9;
    background-color: #eaecf4;
    border-color: #dddfeb;
}

/* ===== RESPONSIVE ===== */

/* Tablets */
@media (max-width: 991.98px) {
    .dataTables_wrapper .row:first-child {
        flex-direction: column;
        align-items: stretch;
    }
    
    .dataTables_length,
    .dataTables_filter {
        flex: none;
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .dataTables_filter {
        text-align: left;
    }
    
    .dataTables_filter label {
        justify-content: flex-start;
    }
    
    .dataTables_filter input {
        flex: 1;
        min-width: auto;
    }
    
    .dataTables_wrapper .row:last-child {
        flex-direction: column;
        align-items: stretch;
    }
    
    .dataTables_info,
    .dataTables_paginate {
        width: 100%;
        text-align: center;
        padding-right: 0rem !important;
        
    }
    
    .pagination {
        justify-content: center;
    }
}

/* Móviles */
@media (max-width: 767.98px) {
    .dataTables_wrapper {
        padding: 0 5px;
        font-size: 0.8rem;
    }
    
    .dataTables_length label,
    .dataTables_filter label {
        flex-direction: row !important; /* Mantener en línea horizontal */
        align-items: center !important;
        margin-bottom: 0.5rem !important;
    }
    
    .dataTables_length select {
        margin: 0 8px !important; /* Espaciado normal */
        width: auto !important; /* Ancho automático */
        min-width: 80px !important; /* Ancho mínimo */
    }
    
    .dataTables_filter label {
        flex-direction: row !important; /* Mantener en línea horizontal */
        align-items: center !important;
        margin-bottom: 0.5rem !important;
    }
    
    .dataTables_filter input {
        margin: 0 0 0 8px !important; /* Espaciado normal */
        width: auto !important; /* Ancho automático */
        flex: 1 !important; /* Ocupar espacio disponible */
        min-width: 150px !important; /* Ancho mínimo */
    }
    
    .table-responsive {
        font-size: 0.8rem;
    }
    
    /* Mejoras para tabla responsiva de DataTables */
    table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child::before,
    table.dataTable.dtr-inline.collapsed > tbody > tr > th:first-child::before {
        top: 50%;
        transform: translateY(-50%);
        background-color: #4e73df;
    }
    
    /* Detalles expandidos en móvil */
    .dtr-details {
        font-size: 0.8rem;
    }
    
    .dtr-details li {
        border-bottom: 1px solid #efefef;
        padding: 0.5rem 0;
        display: flex;
        justify-content: space-between;
    }
    
    .dtr-details li:last-child {
        border-bottom: none;
    }
    
    .dtr-title {
        font-weight: 600;
        color: #4e73df;
        min-width: 100px;
    }
    
    .dtr-data {
        text-align: right;
        flex: 1;
    }
}

/* Pantallas muy pequeñas */
@media (max-width: 575.98px) {
    .dataTables_paginate .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .page-item {
        margin-bottom: 0.25rem;
    }
    
    .dataTables_info {
        margin-bottom: 0.5rem;
    }
}

/* ===== ESTILOS ADICIONALES PARA MEJORAR LA EXPERIENCIA ===== */

/* Loading state */
.dataTables_wrapper .dataTables_processing {
    background: linear-gradient(45deg, #4e73df, #2e59d9);
    color: white;
    border-radius: 0.5rem;
    padding: 1rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

/* Botones de exportación si los hay */
.dt-buttons {
    margin-bottom: 1rem;
    text-align: center;
}

.dt-buttons .btn {
    margin: 0 2px 5px 2px;
}

#empleadosTable thead th.sorting:after,
#empleadosTable thead th.sorting_asc:after,
#empleadosTable thead th.sorting_desc:after {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Estilos para el ordenamiento */
table.dataTable thead th.sorting,
table.dataTable thead th.sorting_asc, 
table.dataTable thead th.sorting_desc {
    padding-right: 35px !important;
    background-repeat: no-repeat !important;
    background-position: center right 12px !important;
}

/* Posicionamiento de las flechas */
table.dataTable thead .sorting:after,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc:after {
    position: absolute;
    right: 12px;
    top: 40%;
    transform: translateY(-50%);
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    opacity: 0.5;
    transition: all 0.3s ease;
}
/* Flechas de ordenamiento - SOLO TRIANGULARES */
table.dataTable thead .sorting:after {
    content: "\f0dc" !important;
    color: rgba(255, 255, 255, 0.7) !important;
    opacity: 0.7 !important;
}

table.dataTable thead .sorting_asc:after {
    content: "\f0de" !important;
    color: #fff !important;
    opacity: 1 !important;
    text-shadow: 0 0 5px rgba(255, 255, 255, 0.8) !important;
}

table.dataTable thead .sorting_desc:after {
    content: "\f0dd" !important;
    color: #fff !important;
    opacity: 1 !important;
    text-shadow: 0 0 5px rgba(255, 255, 255, 0.8) !important;
}

/* Colores para estados en la tabla */
.table-success {
    background-color: #d4edda !important;
}

.table-warning {
    background-color: #fff3cd !important;
}

.table-danger {
    background-color: #f8d7da !important;
}

/* Efectos hover mejorados */
.table-hover tbody tr:hover {
    background-color: #f8f9fc;
    transform: translateY(-1px);
    transition: all 0.2s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}


/* Asegurar que la tabla ocupe todo el ancho disponible */
#empleadosTable {
    width: 100% !important;
}

/* Header de la tabla */
#empleadosTable thead th {
    color: white;
    border: none;
    font-weight: 600;
    padding: 12px 15px;
    white-space: nowrap;
}

/* Celdas de la tabla */
#empleadosTable tbody td {
    padding: 10px 15px;
    vertical-align: middle;
    border-bottom: 1px solid #e3e6f0;
}

/* Botones de acción en la tabla */
.btn-action-group {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.btn-action-group .btn {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

/* Responsive para botones de acción */
@media (max-width: 767.98px) {
    .btn-action-group {
        flex-direction: column;
    }
    
    .btn-action-group .btn {
        margin-bottom: 2px;
        text-align: center;
    }
}

/* Badges en la tabla */
.badge {
    font-size: 0.7rem;
    padding: 0.3em 0.6em;
}

/* Estilos para el contador de registros */
.dataTables_length,
.dataTables_info {
    color: #6c757d;
}

.dataTables_info {
    padding: 0.75rem 0;
    padding-right: 12rem;
}

/* Mejoras visuales para el buscador */
.dataTables_filter input {
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #d1d3e2;
}

.dataTables_filter input:focus {
    border-color: #bac8f3;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

/* Separación visual entre controles */
.dataTables_wrapper .row:first-child {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem !important; /* Más espacio */
    padding: 15px 0 !important; /* Padding vertical */
    border-bottom: 2px solid #e9ecef !important; /* Línea más definida */
    background-color: #f8f9fa !important; /* Fondo diferenciado */
    border-radius: 8px !important;
    margin-top: 10px !important;
}
.dataTables_wrapper .row:last-child {
    border-top: 1px solid #e3e6f0;
    padding-top: 1rem;
    margin-top: 0;
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
        padding-right: 0rem !important;
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

     .dataTables_wrapper .row:last-child {
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
        align-items: center !important;
        flex-wrap: nowrap !important;
    }
    
    .dataTables_info {
        order: 1 !important;
        width: auto !important;
        text-align: left !important;
        margin-bottom: 0 !important;
        padding-right: 1rem !important;
        flex: 1 !important;
    }
    
    .dataTables_paginate {
        order: 2 !important;
        width: auto !important;
        text-align: right !important;
        margin-bottom: 0 !important;
        padding-top: 0 !important;
    }
    
    .pagination {
        justify-content: flex-end !important;
        margin-bottom: 0 !important;
    }
    
    /* Asegurar que la información y paginación estén en la misma línea */
    .dataTables_wrapper .row:last-child > * {
        flex: 0 0 auto !important;
    }
}

/* Tablets grandes (991px en adelante) */
@media(min-width: 991px) and (max-width: 1199.98px) {
    .dataTables_info {
        padding-right: 0 !important;
    }
}

/* Ajustes adicionales para tablets más pequeñas */
@media (min-width: 768px) and (max-width: 820px) {
    .dataTables_info {
        font-size: 0.85rem !important;
        padding-right: 0.5rem !important;
    }
    
    .pagination .page-link {
        padding: 0.3rem 0.6rem !important;
        font-size: 0.85rem !important;
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


/* Estilos para el campo de teléfono */
#telefono.is-valid {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

#telefono.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

/* Estilos mejorados para la sección del QR */
#qr-preview {
    min-height: 250px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.qr-image {
    max-width: 200px;
    max-height: 200px;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    padding: 10px;
    background: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.qr-image:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

/* Animación para la generación del QR */
@keyframes qr-generate {
    0% { opacity: 0; transform: scale(0.8); }
    100% { opacity: 1; transform: scale(1); }
}

.qr-generated {
    animation: qr-generate 0.5s ease-out;
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

/* Mejorar la tarjeta del QR */
.card-border-primary {
    border-color: #007bff !important;
}

.card-header.bg-primary {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
}

/* Estilos para el campo de teléfono en edición */
#edit_telefono.is-valid {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

#edit_telefono.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

/* Mejorar la disposición de los campos editables */
@media (min-width: 768px) {
    .edit-form-row {
        display: flex;
        gap: 15px;
    }
    
    .edit-form-row .form-group {
        flex: 1;
    }
}


/* Estilos para la impresión de QR */
@media print {
    body * {
        visibility: hidden;
    }
    #area-impresion,
    #area-impresion * {
        visibility: visible;
    }
    #area-impresion {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .modal-footer,
    .modal-header,
    .btn-group {
        display: none !important;
    }
}

#imprimirQRModal .btn-success {
    background-color: #25D366;
    border-color: #25D366;
}

#imprimirQRModal .btn-success:hover {
    background-color: #128C7E;
    border-color: #128C7E;
}

/* Estilos para el área de impresión */
#area-impresion .card {
    border: 2px solid #007bff !important;
}

#area-impresion .card-header {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
    color: white;
    font-weight: bold;
}

/* Mejoras responsivas */
@media (max-width: 768px) {
    #imprimirQRModal .modal-dialog {
        margin: 0.5rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-bottom: 0.5rem;
    }
}


/* Estilos para el campo de teléfono en vista */
#view_telefono {
    font-family: 'Courier New', monospace;
    font-weight: bold;
    color: #2c3e50;
}

#btn-llamar {
    font-size: 0.7rem;
    padding: 0.15rem 0.4rem;
    transition: all 0.3s ease;
}

#btn-llamar:hover {
    transform: scale(1.05);
    background-color: #28a745;
    color: white;
}

/* Mejoras para la tarjeta de información personal */
.card-body .row {
    border-bottom: 1px solid #f8f9fa;
    padding: 0.25rem 0;
}

.card-body .row:last-child {
    border-bottom: none;
}

/* Estilos para el botón de llamar en móviles */
@media (max-width: 768px) {
    #btn-llamar {
        display: block;
        width: 100%;
        margin-top: 0.5rem;
    }
}

/* Estilo para el formato de teléfono en información adicional */
#view_formato_telefono {
    font-size: 0.9rem;
    color: #17a2b8;
    font-weight: bold;
}

/* Estilos mejorados para la sección del QR */
#qr-preview {
    min-height: 250px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    border: 2px dashed #dee2e6;
}

.qr-generated {
    animation: qrPulse 0.5s ease-out;
}

@keyframes qrPulse {
    0% { 
        opacity: 0; 
        transform: scale(0.8) rotate(-5deg); 
    }
    100% { 
        opacity: 1; 
        transform: scale(1) rotate(0deg); 
    }
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

/* Mejorar la tarjeta del QR */
.card-border-primary {
    border-color: #007bff !important;
}

.card-header.bg-primary {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
}

/* Animación para la barra de progreso */
.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

@keyframes progress-bar-stripes {
    0% { background-position: 1rem 0; }
    100% { background-position: 0 0; }
}

/* Efectos hover para el QR */
.qr-image:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

</style>
@endsection