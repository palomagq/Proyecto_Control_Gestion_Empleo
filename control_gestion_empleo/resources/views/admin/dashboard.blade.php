<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- CSRF Token para protección -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">


      <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">

    <style>
      body {
        padding-top: 5rem;
      }
      .starter-template {
        padding: 3rem 1.5rem;
        text-align: center;
      }
    </style>
  </head>

  <body>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="{{ route('admin.empleados') }}">Panel del Administrador</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <!--<ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
          </li>
        </ul>-->
        <!-- En tu archivo de layout (navbar) -->
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.empleados') }}">Empleados</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.tareas') }}">Tareas</a>
          </li>
        </ul>
                <!-- Menú de usuario a la derecha - VERSIÓN CORREGIDA -->
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-user-circle mr-1"></i>
                  {{ auth()->user()->name ?? 'Administrador' }}
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                  <h6 class="dropdown-header">
                      <i class="fas fa-user-shield mr-1"></i>
                      Administrador
                  </h6>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="{{ route('admin.profile') }}">
                      <i class="fas fa-user mr-2"></i>Mi Perfil
                  </a>
                  <a class="dropdown-item" href="{{ route('admin.empleados') }}">
                      <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                  </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#" onclick="confirmLogout()">
                      <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                  </a>
              </div>
          </li>
      </ul>
      </div>
    </nav>

    <main role="main" class="container">
      <div class="starter-template">
        <!--<h1>Panel de administración del sistema.</h1>-->
        
        <!-- Aquí se incluirá la sección con el botón -->
        @yield('content')
        
      </div>
    </main>

    <!-- Modal Container -->
    @yield('modals')

    <!-- Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/es.js"></script>


    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>

    <!-- Script para cerrar sesión -->
     <script>
      function confirmLogout() {
        Swal.fire({
          title: '¿Cerrar Sesión?',
          text: '¿Estás seguro de que deseas salir del sistema?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Sí, Cerrar Sesión',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            // Crear formulario para logout (protección CSRF)
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("logout") }}';
            
            // Agregar CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Agregar al documento y enviar
            document.body.appendChild(form);
            form.submit();
          }
        });
      }

      // Script de inicialización del dropdown
      $(document).ready(function() {
        console.log('=== INICIALIZANDO DROPDOWN ===');
        
        // El dropdown de Bootstrap 4 debería funcionar automáticamente
        // con los atributos data-toggle="dropdown"
        
        // Verificar que todo esté cargado
        console.log('jQuery:', typeof $ !== 'undefined');
        console.log('Bootstrap dropdown:', typeof $.fn.dropdown !== 'undefined');
        console.log('Elemento #userDropdown:', $('#userDropdown').length);
        
        // Forzar la inicialización si es necesario
        try {
          // Inicializar manualmente el dropdown
          $('.dropdown-toggle').dropdown();
          console.log('✅ Dropdown inicializado manualmente');
        } catch (error) {
          console.log('❌ Error inicializando dropdown:', error);
        }
      });

      // Función para probar el dropdown manualmente
      function testDropdown() {
        console.log('🔧 Probando dropdown...');
        $('#userDropdown').dropdown('toggle');
      }
    </script>

    <!-- Scripts adicionales -->
    @yield('scripts')
    @yield('css')

  </body>
</html>