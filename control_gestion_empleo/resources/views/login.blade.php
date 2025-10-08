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
            width: 100%;
            height: 100%;
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
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
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

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="#!" class="text-body">¿Olvidaste tu contraseña?</a>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                            <p class="small fw-bold mt-2 pt-1 mb-0">¿No tienes una cuenta? <a href="#!"
                                class="link-danger">Regístrate</a></p>
                        </div>
                    </form>

                    <div class="instructions mt-4">
                        <h6 class="fw-bold">Instrucciones de acceso:</h6>
                        <ul class="mb-0">
                            <li><strong>Administradores:</strong> username: "admin", password: "admin123"</li>
                            <li><strong>Empleados:</strong> username: DNI sin letra (8 dígitos), password: numérico de 6 dígitos</li>
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
    <script>
       // Validación básica del formulario
    document.getElementById('loginForm').addEventListener('submit', function(e) {
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
    </script>
</body>
</html>