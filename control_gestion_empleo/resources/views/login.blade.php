<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- MDB CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet">

    <!-- FontAwesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Tus estilos actuales se mantienen igual */
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .vh-100 {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .h-custom {
            flex: 1;
            display: flex;
            align-items: center;
        }
        
        .container-fluid {
            padding: 0;
        }
        
        .row.d-flex {
            margin: 0;
            width: 100%;
        }
        
        .col-md-9.col-lg-6.col-xl-5 {
            padding: 0;
        }
        
        .col-md-8.col-lg-6.col-xl-4.offset-xl-1 {
            padding: 40px;
        }
        
        .img-fluid {
            object-fit: cover;
        }
        
        .btn-primary {
            background-color: #3b71ca;
            border-color: #3b71ca;
        }
        
        .btn-primary:hover {
            background-color: #386bc0;
            border-color: #386bc0;
        }

        .btn-outline-primary {
            border-color: #3b71ca;
            color: #3b71ca;
        }
        
        .btn-outline-primary:hover {
            background-color: #3b71ca;
            color: white;
        }

        .btn-outline-info {
            border-color: #17a2b8;
            color: #17a2b8;
        }
        
        .btn-outline-info:hover {
            background-color: #17a2b8;
            color: white;
        }
        
        .btn-floating {
            width: 40px;
            height: 40px;
        }
        
        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }
        
        .form-control {
            border-radius: 0.25rem;
        }
        
        .form-control:focus {
            border-color: #3b71ca;
            box-shadow: 0 0 0 0.2rem rgba(59, 113, 202, 0.25);
        }
        
        .form-label {
            color: #6c757d;
        }
        
        .form-check-input:checked {
            background-color: #3b71ca;
            border-color: #3b71ca;
        }
        
        .text-body {
            color: #6c757d !important;
        }
        
        .text-body:hover {
            color: #5a6268 !important;
        }
        
        .link-danger {
            color: #dc3545 !important;
        }
        
        .link-danger:hover {
            color: #c82333 !important;
        }
        
        .bg-primary {
            background-color: #3b71ca !important;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .qr-section {
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
            margin-top: 20px;
        }

        .qr-icon {
            font-size: 2rem;
            color: #3b71ca;
            margin-bottom: 10px;
        }

        .qr-btn {
            width: 100%;
            padding: 12px;
            font-size: 1.1rem;
        }

        .login-options {
            text-align: center;
            margin: 20px 0;
        }

        .divider-text {
            background-color: #f8f9fa;
            padding: 0 15px;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .qr-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .qr-card:hover {
            border-color: #17a2b8;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .qr-instructions {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }

        .qr-step {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 8px;
            background: white;
            border-radius: 6px;
            border-left: 4px solid #17a2b8;
        }

        .qr-step-number {
            background: #17a2b8;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
            font-size: 0.8rem;
        }

        /* NUEVA CLASE PARA OCULTAR QR EN MÓVILES */
        .hide-on-mobile {
            display: none;
        }

        /* MEJORAS PARA DISPOSITIVOS MÓVILES Y TABLETS */
        @media (max-width: 991px) {
            .col-md-9.col-lg-6.col-xl-5 {
                display: none;
            }
            
            .col-md-8.col-lg-6.col-xl-4.offset-xl-1 {
                padding: 25px 20px;
                padding-top: 2rem !important;
                width: 100%;
                max-width: 500px;
                margin: 0 auto;
            }
            
            .h-custom {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
            
            .form-control-lg {
                font-size: 1rem;
                padding: 0.75rem 1rem;
            }
            
            .btn-lg {
                font-size: 1rem;
                padding: 0.75rem 1.5rem;
            }
            
            /* NUEVO: Ocultar la sección QR en móviles (menos de 768px) */
            @media (max-width: 767px) {
                .hide-on-mobile {
                    display: none !important;
                }
                
                .login-options {
                    display: none !important;
                }
                
                .instructions {
                    margin-top: 0 !important;
                }
            }
            
            /* MOSTRAR QR EN TABLETS (768px a 991px) */
            @media (min-width: 768px) and (max-width: 991px) {
                .hide-on-mobile {
                    display: block !important;
                }
                
                .login-options {
                    display: block !important;
                }
            }
        }

        @media (max-width: 768px) {
            .h-custom {
                height: auto !important;
            }
            
            .row.d-flex {
                flex-direction: column;
            }
            
            .col-md-8.col-lg-6.col-xl-4.offset-xl-1 {
                padding: 20px 15px;
                padding-top: 1.5rem !important;
            }
        }

        @media (max-width: 450px) {
            .h-custom {
                height: 100% !important;
            }
            
            .col-md-8.col-lg-6.col-xl-4.offset-xl-1 {
                padding: 15px 10px;
                padding-top: 1rem !important;
            }
        }
        
        /* MOSTRAR SIEMPRE EN ESCRITORIO (más de 991px) */
        @media (min-width: 992px) {
            .hide-on-mobile {
                display: block !important;
            }
        }
    </style>
</head>
<body>

    <!-- Sección de login -->
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                    class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1" style="padding-top: 4rem;">
                    <!-- Mostrar errores si existen -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first('username') }}
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf

                        <!-- Username input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" id="username" name="username" class="form-control form-control-lg" 
                                   placeholder="Introduce 'admin' o tu DNI (8 números)" 
                                   required 
                                   value="{{ old('username') }}" />
                            <label class="form-label" for="username">Username</label>
                            <div class="invalid-feedback">
                                Por favor, introduce un usuario válido
                            </div>
                        </div>

                        <!-- Password input -->
                        <div data-mdb-input-init class="form-outline mb-3">
                            <input type="password" id="password" name="password" class="form-control form-control-lg" 
                                   placeholder="Introduce tu contraseña" 
                                   required />
                            <label class="form-label" for="password">Contraseña</label>
                            <div class="invalid-feedback">
                                Por favor, introduce tu contraseña
                            </div>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Iniciar Sesión</button>
                        </div>
                    </form>

                    <!-- Separador - Solo se muestra en tablet y desktop -->
                    <div class="login-options hide-on-mobile">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1" style="height: 1px; background-color: #dee2e6;"></div>
                            <span class="divider-text mx-3">O</span>
                            <div class="flex-grow-1" style="height: 1px; background-color: #dee2e6;"></div>
                        </div>
                    </div>

                    <!-- Sección QR - VERSIÓN SIMPLIFICADA CON REDIRECCIÓN -->
                    <!-- Añadida la clase hide-on-mobile para ocultar en móviles -->
                    <div class="row hide-on-mobile">
                        <div class="col-12">
                            <div class="card border-info qr-card">
                                <div class="card-header bg-info text-white text-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-qrcode mr-2"></i>
                                        Acceso Rápido por QR
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <!-- Botón para ir directamente al login QR -->
                                    <a href="{{ route('login.qr') }}" class="btn btn-outline-info btn-lg mb-3 w-100">
                                        <i class="fas fa-qrcode mr-2"></i>
                                        Acceder con Código QR
                                    </a>

                                    <!-- Información sobre el proceso QR -->
                                    <div class="qr-instructions">
                                        <h6 class="fw-bold mb-3">¿Cómo funciona?</h6>
                                        
                                        <div class="qr-step">
                                            <div class="qr-step-number">1</div>
                                            <div class="qr-step-text">
                                                <small>Haz clic en "Acceder con Código QR"</small>
                                            </div>
                                        </div>
                                        
                                        <div class="qr-step">
                                            <div class="qr-step-number">2</div>
                                            <div class="qr-step-text">
                                                <small>Se generará tu código QR personal</small>
                                            </div>
                                        </div>
                                        
                                        <div class="qr-step">
                                            <div class="qr-step-number">3</div>
                                            <div class="qr-step-text">
                                                <small>Escanea el código con tu móvil</small>
                                            </div>
                                        </div>
                                        
                                        <div class="qr-step">
                                            <div class="qr-step-number">4</div>
                                            <div class="qr-step-text">
                                                <small>Confirma el login en tu dispositivo</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información adicional -->
                                    <div class="mt-3 p-2 bg-light rounded">
                                        <small class="text-muted">
                                            <i class="fas fa-shield-alt mr-1"></i>
                                            Acceso seguro • Válido por 10 minutos
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--<div class="instructions mt-4">
                        <h6 class="fw-bold">Instrucciones de acceso:</h6>
                        <ul class="mb-0">
                            <li><strong>Administradores:</strong> username: "admin", password: "admin123"</li>
                            <li><strong>Empleados:</strong> username: DNI sin letra (8 dígitos), password: numérico de 4 dígitos</li>
                            <li class="qr-section-mobile"><strong>Acceso QR:</strong> Haz clic en "Acceder con Código QR" para login rápido</li>
                        </ul>
                    </div>-->
                </div>
            </div>
        </div>
        <div
            class="d-flex flex-column flex-md-row text-center text-md-start justify-content-center py-4 px-4 px-xl-5 bg-primary">
            <!-- Copyright -->
            <div class="text-white mb-3 mb-md-0">
            Copyright © 2025. All rights reserved.
            </div>
            <!-- Copyright -->
        </div>
    </section>

    <!-- MDB JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔧 Inicializando sistema de login...');

            // Validación básica del formulario
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const username = document.getElementById('username');
                    const password = document.getElementById('password');
                    let isValid = true;

                    // Validar que los campos no estén vacíos
                    if (username.value.trim() === '') {
                        username.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        username.classList.remove('is-invalid');
                    }

                    if (password.value.trim() === '') {
                        password.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        password.classList.remove('is-invalid');
                    }

                    if (!isValid) {
                        e.preventDefault();
                    }
                });

                // Eliminar clases de invalid cuando el usuario empiece a escribir
                document.getElementById('username').addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.classList.remove('is-invalid');
                    }
                });

                document.getElementById('password').addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.classList.remove('is-invalid');
                    }
                });
            }

            // Efecto hover para la tarjeta QR (solo en escritorio)
            const qrCard = document.querySelector('.qr-card');
            if (qrCard && window.innerWidth > 991) {
                qrCard.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                qrCard.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            }

            // Verificar si la sección QR está visible
            const qrSection = document.querySelector('.hide-on-mobile');
            if (qrSection) {
                console.log('📱 Sección QR:', 
                    window.innerWidth < 768 ? 'Oculta (móvil)' : 
                    window.innerWidth < 992 ? 'Visible (tablet)' : 'Visible (desktop)'
                );
            }

            console.log('✅ Sistema de login inicializado correctamente');
        });

        // Función para redirigir al login QR (backup)
        function goToQRLogin() {
            window.location.href = "{{ route('login.qr') }}";
        }
    </script>
</body>
</html>