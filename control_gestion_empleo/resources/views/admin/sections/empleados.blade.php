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
                <p class="lead text-muted">Administra y gestiona los empleados del sistema</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
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
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
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
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
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
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                        <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#employeeModal">
                            <i class="fas fa-user-plus mr-1"></i> Crear Nuevo Empleado
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-lg ml-2" onclick="exportarExcel()">
                            <i class="fas fa-file-excel mr-1"></i> Exportar Excel
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="filterDni" class="font-weight-bold text-dark">
                                    <i class="fas fa-id-card mr-1"></i>Filtrar por DNI:
                                </label>
                                <input type="text" class="form-control" id="filterDni" placeholder="Ej: 12345678A">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="filterNombre" class="font-weight-bold text-dark">
                                    <i class="fas fa-user mr-1"></i>Filtrar por Nombre:
                                </label>
                                <input type="text" class="form-control" id="filterNombre" placeholder="Buscar por nombre...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="filterMes" class="font-weight-bold text-dark">
                                    <i class="fas fa-calendar-alt mr-1"></i>Filtrar por Mes Completo:
                                </label>
                                <input type="text" class="form-control" id="filterMes" 
                                       placeholder="Seleccione un mes">
                                <small class="form-text text-muted">Se filtrará del día 1 al último día del mes</small>
                            </div>
                        </div>
                    </div>

                    <!-- Información del filtro aplicado -->
                    <div class="row" id="filtroInfo" style="display: none;">
                        <div class="col-md-12">
                            <div class="alert alert-info py-2 mb-0">
                                <i class="fas fa-info-circle"></i> 
                                Filtrando por mes completo: <strong id="infoMes"></strong>
                                <button type="button" class="btn btn-sm btn-outline-info ml-2" onclick="limpiarFiltroMes()">
                                    <i class="fas fa-times"></i> Limpiar filtro de mes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-primary btn-lg" onclick="aplicarFiltros()">
                                <i class="fas fa-filter mr-1"></i> Aplicar Filtros
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-lg ml-2" onclick="limpiarFiltros()">
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
                        <i class="fas fa-table mr-2"></i>Lista de Empleados
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="empleadosTable" class="table table-hover table-bordered mb-0" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="8%"><i class="fas fa-id-card mr-1"></i> DNI</th>
                                    <th width="15%"><i class="fas fa-user mr-1"></i> Nombre</th>
                                    <th width="15%"><i class="fas fa-users mr-1"></i> Apellidos</th>
                                    <th width="8%"><i class="fas fa-birthday-cake mr-1"></i> Fecha Nacimiento</th>
                                    <th width="8%"><i class="fas fa-calendar mr-1"></i> Edad</th>
                                    <th width="18%"><i class="fas fa-home mr-1"></i> Domicilio</th>
                                    <th width="8%"><i class="fas fa-user-tag mr-1"></i> Username</th>
                                    <th width="15%"><i class="fas fa-cogs mr-1"></i> Acciones</th>
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
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places" async defer></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Fecha de nacimiento con restricción de +16 años
    flatpickr("#fecha_nacimiento", {
        dateFormat: "Y-m-d",
        maxDate: new Date().fp_incr(-5840), // 16 años atrás (16 * 365 = 5840 días)
        locale: "es",
        errorHandler: function(error) {
            console.log('Error de fecha:', error);
        }
    });

    // Selector de mes completo
    flatpickr("#filterMes", {
        plugins: [
            new monthSelectPlugin({
                shorthand: true,
                dateFormat: "Y-m", // formato que usas en filtros
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
});

function generarUsername() {
    const dniInput = document.getElementById('dni');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password-display');
    
    // Extraer solo los números del DNI (eliminar letra si existe)
    const soloNumeros = dniInput.value.replace(/[^0-9]/g, '');
    
    // Generar username (primeros 8 números)
    if (soloNumeros.length >= 8) {
        usernameInput.value = soloNumeros.substring(0, 8);
    } else {
        usernameInput.value = soloNumeros; // Usar los números disponibles
    }
    
    // ✅ CORREGIDO: Generar contraseña de 4 dígitos 
    if (soloNumeros.length >= 4) {
        passwordInput.value = soloNumeros.substring(0, 4); // Primeros 8 dígitos
    } else if (soloNumeros.length > 0) {
        // Si no hay 4 dígitos, completar con ceros
        passwordInput.value = soloNumeros.padEnd(4, '0');
    } else {
        passwordInput.value = '';
    }
    
    validarDNI();
}

function validarDNI() {
    const dniInput = document.getElementById('dni');
    const dni = dniInput.value.trim();
    
    // Expresión regular para validar DNI español (8 números + 1 letra)
    const dniRegex = /^[0-9]{8}[A-Za-z]$/;
    
    if (dni.length === 9 && !dniRegex.test(dni)) {
        dniInput.classList.add('is-invalid');
        return false;
    } else {
        dniInput.classList.remove('is-invalid');
        return true;
    }
}

function submitEmployeeForm() {
    // Validaciones antes de enviar
    if (!validarDNI()) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor, ingrese un DNI válido (8 números + 1 letra)'
        });
        return;
    }

    // Validar que el DNI tenga al menos 4 dígitos para la contraseña
    const dni = document.getElementById('dni').value.replace(/[^0-9]/g, '');
    if (dni.length < 4) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El DNI debe contener al menos 4 dígitos para generar la contraseña automáticamente'
        });
        return;
    }

    // ✅ CORREGIDO: Validar edad mínima (16 años) - según requerimiento del servidor
    const fechaNacimiento = new Date(document.getElementById('fecha_nacimiento').value);
    const hoy = new Date();
    const edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
    const mes = hoy.getMonth() - fechaNacimiento.getMonth();
    const dia = hoy.getDate() - fechaNacimiento.getDate();
    
    // Calcular edad exacta
    let edadExacta = edad;
    if (mes < 0 || (mes === 0 && dia < 0)) {
        edadExacta--;
    }
    
    if (edadExacta < 16) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El empleado debe ser mayor de 16 años'
        });
        return;
    }

    // Validar campos requeridos
    const camposRequeridos = ['nombre', 'apellidos', 'dni', 'fecha_nacimiento', 'domicilio'];
    const camposFaltantes = [];
    
    camposRequeridos.forEach(campo => {
        const elemento = document.getElementById(campo);
        if (!elemento || !elemento.value.trim()) {
            camposFaltantes.push(campo);
        }
    });
    
    if (camposFaltantes.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Campos incompletos',
            text: 'Por favor, complete todos los campos obligatorios'
        });
        return;
    }
    
    // Verificar coordenadas
    let latitud = document.getElementById('latitud').value;
    let longitud = document.getElementById('longitud').value;
    
    if (!latitud || !longitud) {
        latitud = '40.4168';
        longitud = '-3.7038';
    }
    
    // ✅ CORREGIDO: Obtener username y password generados para enviar al servidor
    const usernameGenerado = document.getElementById('username').value;
    const passwordGenerado = document.getElementById('password-display').value;
    
    // Validar que las credenciales estén generadas
    if (!usernameGenerado || !passwordGenerado) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron generar las credenciales automáticamente. Verifique el DNI.'
        });
        return;
    }
    
    // Obtener el token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // ✅ CORREGIDO: Incluir username y password en los datos enviados
    const empleadoData = {
        _token: csrfToken,
        nombre: document.getElementById('nombre').value.trim(),
        apellidos: document.getElementById('apellidos').value.trim(),
        dni: document.getElementById('dni').value.trim(),
        fecha_nacimiento: document.getElementById('fecha_nacimiento').value,
        domicilio: document.getElementById('domicilio').value.trim(),
        latitud: latitud,
        longitud: longitud,
        username: usernameGenerado, // ✅ Ahora se envía
        password: passwordGenerado, // ✅ Ahora se envía
        password_confirmation: passwordGenerado // ✅ Confirmación requerida
    };
    
    console.log('📤 Datos a enviar:', empleadoData);
    
    // Mostrar loading
    const submitBtn = document.querySelector('#employeeModal .btn-success');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Creando...';
    submitBtn.disabled = true;
    
    // Enviar datos
    fetch('{{ route("admin.empleados.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(empleadoData)
    })
    .then(response => {
        if (response.status === 422) {
            return response.json().then(data => {
                let errorMessage = 'Errores de validación:\n';
                for (const field in data.errors) {
                    errorMessage += `• ${data.errors[field][0]}\n`;
                }
                throw new Error(errorMessage);
            });
        }
        
        if (!response.ok) {
            return response.text().then(text => {
                try {
                    const errorData = JSON.parse(text);
                    throw new Error(errorData.message || text);
                } catch (e) {
                    throw new Error(text);
                }
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('✅ Respuesta del servidor:', data);
        
        if (data.success) {
            $('#employeeModal').modal('hide');
            
            // Mostrar las credenciales generadas automáticamente
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                html: `
                    <div class="text-left">
                        <p>${data.message}</p>
                        <div class="alert alert-success mt-3">
                            <h6><i class="fas fa-key"></i> Credenciales Generadas Automáticamente</h6>
                            <hr>
                            <strong>Username:</strong> ${usernameGenerado}<br>
                            <strong>Contraseña:</strong> <code class="bg-light p-1 rounded">${passwordGenerado}</code><br>
                            <strong>Edad del empleado:</strong> ${edadExacta} años
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            La contraseña son los primeros 4 dígitos del DNI
                        </small>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'Aceptar',
                allowOutsideClick: false,
                width: '600px'
            }).then((result) => {
                location.reload();
            });
            
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        console.error('❌ Error completo:', error);
        
        // Manejar errores específicos
        if (error.message.includes('dni') && error.message.includes('duplicate')) {
            Swal.fire({
                icon: 'error',
                title: 'DNI duplicado',
                text: 'El DNI ingresado ya existe en el sistema. Por favor, verifique los datos.'
            });
        } else if (error.message.includes('username') && error.message.includes('duplicate')) {
            Swal.fire({
                icon: 'error',
                title: 'Username duplicado',
                text: 'El username generado ya existe. Esto puede ocurrir si el DNI es muy similar a uno existente.'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: `<div class="text-left">${error.message}</div>`
            });
        }
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
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
            
            showAlert('Dirección encontrada correctamente', 'success', 2000);
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
            showAlert('Dirección encontrada correctamente', 'success', 2000);
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
</script>

</script>

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

</style>
@endsection