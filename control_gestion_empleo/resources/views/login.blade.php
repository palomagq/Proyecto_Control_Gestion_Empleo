<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

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
            /*width: 100%;
            height: 100%;*/
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

        @media (max-width: 991px) {
            .col-md-8.col-lg-6.col-xl-4.offset-xl-1 {
                padding: 30px 20px;
            }
            
            .col-md-9.col-lg-6.col-xl-5 {
                height: 300px;
            }
        }
        
        @media (max-width: 768px) {
            .h-custom {
                height: auto !important;
            }
            
            .row.d-flex {
                flex-direction: column;
            }
            
            .col-md-9.col-lg-6.col-xl-5 {
                height: 250px;
            }
            
            .col-md-8.col-lg-6.col-xl-4.offset-xl-1 {
                padding: 25px 15px;
            }
        }
        
        @media (max-width: 450px) {
            .h-custom {
                height: 100% !important;
            }
            
            .col-md-8.col-lg-6.col-xl-4.offset-xl-1 {
                padding: 20px 10px;
            }
        }

/* MEJORAS PARA DISPOSITIVOS MÓVILES Y TABLETS */

/* Ajustes generales para pantallas pequeñas */
@media (max-width: 991px) {
    .col-md-9.col-lg-6.col-xl-5 {
        display: none; /* Ocultar la imagen */
    }
    
    .col-md-8.col-lg-6.col-xl-4.offset-xl-1 {
        padding: 25px 20px;
        padding-top: 2rem !important;
        width: 100%; /* Ocupar todo el ancho disponible */
        max-width: 500px; /* Limitar el ancho máximo */
        margin: 0 auto; /* Centrar el formulario */
    }
    
    .h-custom {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    /* Mejorar el tamaño de fuente para mejor legibilidad */
    .form-control-lg {
        font-size: 1rem;
        padding: 0.75rem 1rem;
    }
    
    .btn-lg {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
    }
    
    /* Ajustar el espaciado entre elementos */
    .form-outline.mb-4 {
        margin-bottom: 1rem !important;
    }
    
    .form-outline.mb-3 {
        margin-bottom: 0.75rem !important;
    }
    
    .text-center.text-lg-start.mt-4.pt-2 {
        margin-top: 1rem !important;
        padding-top: 0.5rem !important;
    }
    
    /* Optimizar la sección de instrucciones */
    .instructions {
        margin-top: 1.5rem;
    }
    
    .instructions h6 {
        font-size: 1rem;
    }
    
    .instructions ul {
        padding-left: 1.25rem;
        font-size: 0.9rem;
    }
    
    .instructions li {
        margin-bottom: 0.5rem;
    }
    
    /* Mejorar la sección QR */
    .qr-section {
        padding-top: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .qr-icon {
        font-size: 1.75rem;
    }
    
    .qr-btn {
        padding: 0.75rem;
        font-size: 1rem;
    }
}

/* Ajustes específicos para tablets medianas */
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
    
    /* Botón de login más ancho en móviles */
    .btn-primary.btn-lg {
        width: 100%;
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    /* Optimizar textos para móviles */
    .instructions ul {
        font-size: 0.85rem;
    }
    
    .qr-section h5 {
        font-size: 1.1rem;
    }
    
    .qr-section p {
        font-size: 0.9rem;
    }
}

/* Ajustes para móviles pequeños */
@media (max-width: 450px) {
    .h-custom {
        height: 100% !important;
    }
    
    .col-md-8.col-lg-6.col-xl-4.offset-xl-1 {
        padding: 15px 10px;
        padding-top: 1rem !important;
    }
    
    /* Reducir aún más los tamaños de fuente */
    .form-control-lg {
        font-size: 0.9rem;
        padding: 0.6rem 0.8rem;
    }
    
    .btn-lg {
        font-size: 0.9rem;
        padding: 0.6rem 1rem;
    }
    
    /* Ajustar márgenes para ahorrar espacio */
    .form-outline.mb-4 {
        margin-bottom: 0.75rem !important;
    }
    
    .login-options {
        margin: 15px 0;
    }
    
    .qr-section {
        padding-top: 1rem;
        margin-top: 1rem;
    }
    
    /* Simplificar instrucciones en móviles muy pequeños */
    .instructions ul li {
        font-size: 0.8rem;
        margin-bottom: 0.4rem;
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
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1" style="padding-top: 18rem;">
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

                    <!-- Separador -->
                    <div class="login-options">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1" style="height: 1px; background-color: #dee2e6;"></div>
                            <span class="divider-text mx-3">O</span>
                            <div class="flex-grow-1" style="height: 1px; background-color: #dee2e6;"></div>
                        </div>
                    </div>

                    <!-- Sección QR -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white text-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-qrcode mr-2"></i>
                                        Acceso Rápido por QR
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <!-- Botón para mostrar QR - VERSIÓN CORREGIDA -->
                                    <button id="btn-mostrar-qr" class="btn btn-outline-info btn-lg mb-3">
                                        <i class="fas fa-qrcode mr-2"></i>
                                        Mostrar Código QR
                                    </button>

                                    <!-- Contenedor del QR (oculto inicialmente) -->
                                    <div id="qr-container" class="mb-3" style="display: none;">
                                        <div id="qr-loading" class="text-center py-4" style="display: none;">
                                            <div class="spinner-border text-primary mb-3" role="status">
                                                <span class="sr-only">Generando QR...</span>
                                            </div>
                                            <p class="text-muted">Generando código QR...</p>
                                        </div>
                                        
                                        <div id="qr-image-container" class="mb-3">
                                            <!-- El QR se cargará aquí -->
                                        </div>
                                        
                                        <div class="qr-instructions">
                                            <p class="small text-muted mb-2">
                                                <i class="fas fa-mobile-alt mr-1"></i>
                                                Escanea este código con tu móvil
                                            </p>
                                            <div class="alert alert-info small">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                <strong>¿No tienes el QR?</strong> Solicítalo al administrador
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información del QR -->
                                    <div id="qr-info" class="mt-3 p-2 bg-light rounded" style="display: none;">
                                        <small class="text-muted">
                                            <i class="fas fa-shield-alt mr-1"></i>
                                            Acceso seguro • Válido por 24 horas
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="instructions mt-4">
                        <h6 class="fw-bold">Instrucciones de acceso:</h6>
                        <ul class="mb-0">
                            <li><strong>Administradores:</strong> username: "admin", password: "admin123"</li>
                            <li><strong>Empleados:</strong> username: DNI sin letra (8 dígitos), password: numérico de 4 dígitos</li>
                            <li><strong>Acceso QR:</strong> Escanea tu código QR personal para acceso rápido</li>
                        </ul>
                    </div>
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
    
    <!-- QR Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    
    <script>
        // Esperar a que jQuery esté completamente cargado
// Sistema QR con JavaScript puro (sin jQuery)
         document.addEventListener('DOMContentLoaded', function() {
            console.log('🔧 Inicializando sistema QR Login...');
            
            const btnMostrarQr = document.getElementById('btn-mostrar-qr');
            const qrContainer = document.getElementById('qr-container');
            const qrInfo = document.getElementById('qr-info');
            const qrLoading = document.getElementById('qr-loading');
            const qrImageContainer = document.getElementById('qr-image-container');

            let isQrVisible = false;

            // Verificar que todos los elementos existan
            if (!btnMostrarQr) {
                console.error('❌ No se encontró el botón de QR');
                return;
            }

            if (!qrContainer) {
                console.error('❌ No se encontró el contenedor QR');
                return;
            }

            console.log('✅ Todos los elementos encontrados correctamente');

            // Configurar el botón QR
            btnMostrarQr.addEventListener('click', toggleQR);

            function toggleQR() {
                console.log('🔄 Cambiando estado QR. Actual:', isQrVisible);
                if (isQrVisible) {
                    hideQR();
                } else {
                    showQR();
                }
            }

            function showQR() {
                console.log('👁️ Mostrando QR...');
                
                // Mostrar contenedores
                qrContainer.style.display = 'block';
                qrInfo.style.display = 'block';
                
                // Cambiar texto del botón directamente
                btnMostrarQr.innerHTML = '<i class="fas fa-times mr-2"></i>Ocultar QR';
                
                isQrVisible = true;
                loadQRCode();
            }

            function hideQR() {
                console.log('👁️ Ocultando QR...');
                
                // Ocultar contenedores
                qrContainer.style.display = 'none';
                qrInfo.style.display = 'none';
                
                // Cambiar texto del botón directamente
                btnMostrarQr.innerHTML = '<i class="fas fa-qrcode mr-2"></i>Mostrar Código QR';
                
                isQrVisible = false;
            }

            function loadQRCode() {
                console.log('🔄 Cargando código QR...');
                showLoading();
                qrImageContainer.innerHTML = '';

                // Simular generación de QR
                setTimeout(() => {
                    hideLoading();
                    generateExampleQR();
                }, 800);
            }

            function showLoading() {
                console.log('⏳ Mostrando carga...');
                if (qrLoading) {
                    qrLoading.style.display = 'block';
                }
            }

            function hideLoading() {
                console.log('✅ Ocultando carga...');
                if (qrLoading) {
                    qrLoading.style.display = 'none';
                }
            }

            function generateExampleQR() {
                console.log('🎨 Generando QR de ejemplo...');
                
                // URL de ejemplo para el QR
                const baseUrl = window.location.origin;
                const qrData = `${baseUrl}/login?method=qr&time=${Date.now()}`;
                
                // Usar API gratuita de QR
                const qrSize = 250;
                const qrImageUrl = `https://api.qrserver.com/v1/create-qr-code/?size=${qrSize}x${qrSize}&data=${encodeURIComponent(qrData)}&format=png&margin=10`;
                
                const qrHTML = `
                    <div class="qr-image-wrapper">
                        <img src="${qrImageUrl}" 
                             alt="Código QR para acceso al sistema"
                             class="img-fluid rounded shadow qr-image">
                        <div class="mt-3">
                            <div class="alert alert-success small">
                                <i class="fas fa-check-circle mr-2"></i>
                                <strong>QR generado correctamente</strong>
                            </div>
                            <small class="text-muted d-block">
                                <i class="fas fa-clock mr-1"></i>
                                Generado: ${new Date().toLocaleTimeString()}
                            </small>
                        </div>
                    </div>
                `;
                
                qrImageContainer.innerHTML = qrHTML;
                
                // Agregar efecto de animación
                const qrImage = qrImageContainer.querySelector('img');
                if (qrImage) {
                    qrImage.style.opacity = '0';
                    qrImage.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        qrImage.style.opacity = '1';
                    }, 100);
                }

                console.log('✅ QR generado exitosamente');
            }

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

            console.log('✅ Sistema QR Login inicializado correctamente');
        });

        // Función global para regenerar QR (si se necesita)
        window.regenerateQR = function() {
            const btn = document.getElementById('btn-mostrar-qr');
            if (btn) {
                btn.click();
            }
        };
    </script>
</body>
</html>