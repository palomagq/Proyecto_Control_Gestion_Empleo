@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-user-cog mr-2"></i>Mi Perfil - Administrador
                </h1>
                <p class="lead text-muted">Gestiona tu información personal y configuración de cuenta</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Columna izquierda - Información del administrador -->
        <div class="col-lg-4 col-md-5">
            <!-- Tarjeta de información del administrador -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user-shield mr-2"></i>Información del Administrador
                    </h6>
                </div>
                <div class="card-body text-center">
                    <!-- Icono de administrador en lugar de avatar -->
                    <div class="mb-4">
                        <div class="rounded-circle bg-gradient-primary d-inline-flex align-items-center justify-content-center shadow-sm"
                             style="width: 120px; height: 120px;">
                            <i class="fas fa-user-shield fa-3x text-white"></i>
                        </div>
                    </div>

                    <!-- Información básica -->
                    <h5 class="font-weight-bold text-gray-900">{{ $admin->name }}</h5>
                    
                    @if($admin->phone)
                        <p class="text-muted mb-2">
                            <i class="fas fa-phone mr-2"></i>{{ $admin->phone }}
                        </p>
                    @endif
                    @if($admin->department)
                        <p class="text-muted mb-2">
                            <i class="fas fa-building mr-2"></i>{{ $admin->department }}
                        </p>
                    @endif
                    <p class="text-muted small">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Miembro desde: {{ $admin->created_at->format('d/m/Y') }}
                    </p>
                    <p class="text-muted small">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Rol: Administrador del Sistema
                    </p>
                </div>
            </div>

            <!-- Tarjeta de estadísticas rápidas -->
            <div class="card shadow">
                <div class="card-header bg-info text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-bar mr-2"></i>Estadísticas del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="border rounded p-3">
                                <div class="h4 mb-0 font-weight-bold text-primary" id="totalEmpleados">0</div>
                                <small class="text-muted">Total Empleados</small>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="border rounded p-3">
                                <div class="h4 mb-0 font-weight-bold text-success" id="registrosHoy">0</div>
                                <small class="text-muted">Registros de Hoy</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <div class="h4 mb-0 font-weight-bold text-danger" id="promedioEdad">0</div>
                                <small class="text-muted">Edad Promedio</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha - Solo cambio de contraseña e información del sistema -->
        <div class="col-lg-8 col-md-7">
            <!-- Cambio de contraseña -->
            <div class="card shadow mb-4">
                <div class="card-header bg-warning text-dark py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-lock mr-2"></i>Cambiar Contraseña
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        <!-- Contraseña Actual -->
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="current_password" class="font-weight-bold">Contraseña Actual</label>
                                    <input type="password" 
                                        class="form-control @error('current_password') is-invalid @enderror" 
                                        id="current_password" 
                                        name="current_password"
                                        placeholder="Ingrese su contraseña actual">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Nueva Contraseña y Confirmación -->
                        <div class="row">
                            <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                                <div class="form-group">
                                    <label for="new_password" class="font-weight-bold">Nueva Contraseña</label>
                                    <input type="password" 
                                        class="form-control @error('new_password') is-invalid @enderror" 
                                        id="new_password" 
                                        name="new_password"
                                        placeholder="Mínimo 8 caracteres">
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="new_password_confirmation" class="font-weight-bold">Confirmar Contraseña</label>
                                    <input type="password" 
                                        class="form-control" 
                                        id="new_password_confirmation" 
                                        name="new_password_confirmation"
                                        placeholder="Repita la nueva contraseña">
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle mr-2"></i>
                                Solo complete estos campos si desea cambiar su contraseña.
                            </small>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key mr-1"></i>Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información del sistema -->
            <div class="card shadow">
                <div class="card-header bg-secondary text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle mr-2"></i>Información del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Versión del Sistema</label>
                                <input type="text" class="form-control bg-light" value="v2.1.0" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Última Actualización</label>
                                <input type="text" class="form-control bg-light" value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Base de Datos</label>
                                <input type="text" class="form-control bg-light" value="MySQL" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Framework</label>
                                <input type="text" class="form-control bg-light" value="Laravel 9.x" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-primary">
                        <small>
                            <i class="fas fa-cogs mr-2"></i>
                            <strong>Sistema de Gestión de Empleados</strong> - Panel de administración completo 
                            para la gestión de empleados, registros horarios y control de accesos.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Cargar estadísticas
    loadAdminStats();

    // Validación de contraseña en tiempo real
    $('#new_password').on('input', function() {
        const password = $(this).val();
        const confirmation = $('#new_password_confirmation').val();
        
        if (password.length > 0 && password.length < 8) {
            $(this).addClass('is-invalid');
            $(this).removeClass('is-valid');
        } else if (password.length >= 8) {
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
        }

        // Verificar coincidencia
        if (confirmation.length > 0 && password !== confirmation) {
            $('#new_password_confirmation').addClass('is-invalid');
        } else if (confirmation.length > 0) {
            $('#new_password_confirmation').removeClass('is-invalid');
            $('#new_password_confirmation').addClass('is-valid');
        }
    });

    $('#new_password_confirmation').on('input', function() {
        const password = $('#new_password').val();
        const confirmation = $(this).val();
        
        if (confirmation.length > 0 && password !== confirmation) {
            $(this).addClass('is-invalid');
            $(this).removeClass('is-valid');
        } else if (confirmation.length > 0) {
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
        }
    });
});

function loadAdminStats() {
    $.ajax({
        url: '{{ route("admin.stats") }}',
        type: 'GET',
        success: function(response) {
            console.log('Estadísticas recibidas:', response); // Para debug
            
            if (response.success) {
                $('#totalEmpleados').text(response.data.total_empleados || '0');
                $('#registrosHoy').text(response.data.registros_hoy || '0');
                $('#promedioEdad').text(response.data.promedio_edad || '0');
            } else {
                // Si hay error en el servidor
                console.error('Error del servidor:', response.error);
                setDefaultStats();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error cargando estadísticas:', error);
            setDefaultStats();
        }
    });
}

function setDefaultStats() {
    $('#totalEmpleados').text('0');
    $('#registrosHoy').text('0');
    $('#promedioEdad').text('0');
}

// Actualizar estadísticas cada 30 segundos
setInterval(loadAdminStats, 30000);

</script>

@endsection




@section('css')
<style>
.cursor-pointer {
    cursor: pointer;
}

.rounded-circle {
    border: 4px solid #f8f9fa;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-outline-primary:hover {
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

.border.rounded {
    transition: all 0.3s ease;
}

.border.rounded:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df, #2e59d9) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .card-body .row {
        margin-left: -5px;
        margin-right: -5px;
    }
    
    .card-body .col-md-6 {
        padding-left: 5px;
        padding-right: 5px;
    }
    
    .text-right {
        text-align: left !important;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 10px;
    }
}

/* Animaciones */
.fa-user-shield {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Mejoras visuales para los campos de formulario */
.form-control.bg-light {
    border: 1px solid #e3e6f0;
    color: #6c757d;
    font-style: italic;
}

.alert-primary {
    border-left: 4px solid #4e73df;
}

/* Para tablets y móviles (hasta 991px) */
@media (max-width: 991px) {
    .starter-template {
        padding: 1rem 0.5rem;
    }
    .container, .container-fluid, .container-lg, .container-md, .container-sm, .container-xl {
        padding-right: 0;
    }
}


/* Estilos para inputs de contraseña en dispositivos móviles */
@media (max-width: 991px) {
    .password-form .form-group {
        margin-bottom: 1rem;
    }
    
    .password-form input[type="password"] {
        width: 100%;
    }
}

/* Asegurar que los inputs ocupen el 100% en móviles */
@media (max-width: 768px) {
    .col-12 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    /* Espaciado adicional entre inputs en móviles */
    .mb-3 {
        margin-bottom: 1rem !important;
    }
}
</style>
@endsection