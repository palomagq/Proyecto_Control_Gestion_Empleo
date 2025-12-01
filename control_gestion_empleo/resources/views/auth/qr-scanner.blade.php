<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escanear QR - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 0;
            margin: 0;
        }
        
        .scanner-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin: 1rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .scanner-icon {
            font-size: 3rem;
            color: #3b71ca;
            margin-bottom: 1rem;
        }
        
        .camera-container {
            width: 100%;
            max-width: 400px;
            height: 400px;
            margin: 0 auto;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            background: #000;
            position: relative;
        }
        
        #camera-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .scan-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 250px;
            border: 3px solid #28a745;
            border-radius: 15px;
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.4);
            pointer-events: none;
        }
        
        .scan-guide {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
            z-index: 10;
        }
        
        .scan-guide-text {
            background: rgba(0, 0, 0, 0.7);
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 14px;
            margin-top: 140px;
        }
        
        .scan-result {
            background: #e8f5e8;
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1rem 0;
            display: none;
        }
        
        .user-info {
            background: #e8f4fd;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
        }
        
        .scanner-placeholder {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 3rem;
            margin: 1rem 0;
            text-align: center;
            color: #6c757d;
        }
        
        .btn-scan {
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 5px;
        }

        .camera-controls {
            margin-top: 1rem;
        }
        
        .scanner-icon i {
            display: block;
        }

        .solution-steps {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-3">
        <div class="scanner-container">
            <div class="scanner-icon">
                <i class="fas fa-camera"></i>
            </div>
            
            <h1 class="mb-3">Confirmar Login en PC</h1>
            <p class="lead text-muted mb-4">Escanea el código QR del PC para confirmar el login</p>

            <!-- Información del usuario logueado en el móvil -->
            @if(auth()->check())
            <div class="user-info">
                <h6><i class="fas fa-user me-2"></i>Usuario en Móvil</h6>
                <p class="mb-1"><strong>{{ auth()->user()->name }}</strong></p>
                <small class="text-muted">
                    @if(auth()->user()->empleado)
                    Empleado ID: {{ auth()->user()->empleado->id }}
                    @endif
                </small>
            </div>
            @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                No estás autenticado. <a href="{{ route('login') }}">Inicia sesión</a> primero.
            </div>
            @endif

            <!-- Instrucciones -->
            <div class="alert alert-info">
                <h6><i class="fas fa-info-circle me-2"></i>Instrucciones:</h6>
                <ol class="text-start mb-0">
                    <li>En el PC, ve a la página de Login con QR</li>
                    <li>Escanea el código QR que aparece en el PC</li>
                    <li>Confirma el login aquí en el móvil</li>
                    <li>El PC iniciará sesión automáticamente</li>
                </ol>
            </div>

            <!-- Pasos de solución (oculto inicialmente) -->
            <div class="solution-steps" id="solution-steps" style="display: none;">
                <h6><i class="fas fa-wrench me-2"></i>Si la cámara no funciona:</h6>
                <ol class="mb-2">
                    <li><strong>Verifica que estés en HTTPS</strong> (la URL debe empezar con https://)</li>
                    <li><strong>Permite el acceso a la cámara</strong> cuando Chrome lo solicite</li>
                    <li><strong>Cierra otras apps</strong> que puedan estar usando la cámara</li>
                    <li><strong>Reinicia Chrome</strong> si el problema persiste</li>
                </ol>
            </div>

            <!-- Vista de la cámara -->
            <div class="camera-container" id="camera-container" style="display: none;">
                <video id="camera-video" autoplay playsinline muted></video>
                <div class="scan-overlay"></div>
                <div class="scan-guide">
                    <div class="scan-guide-text">
                        <i class="fas fa-arrows-alt-h me-1"></i>
                        Enfoca el código QR dentro del marco
                    </div>
                </div>
            </div>
            
            <!-- Placeholder inicial -->
            <div id="scanner-loading" class="scanner-placeholder">
                <i class="fas fa-qrcode fa-2x text-muted mb-3"></i>
                <p>Listo para escanear</p>
                <small class="text-muted">Presiona "Iniciar Cámara" para comenzar</small>
            </div>

            <!-- Resultado del escaneo -->
            <div id="scan-result" class="scan-result">
                <h5><i class="fas fa-check-circle me-2 text-success"></i>QR Detectado</h5>
                <div id="scan-info">
                    <p><strong>Token:</strong> <span id="token-info"></span></p>
                    <p><strong>Empleado ID:</strong> <span id="empleado-id-info"></span></p>
                    <p><strong>Usuario PC:</strong> <span id="user-name-info"></span></p>
                    <p><strong>Estado:</strong> <span id="status-info"></span></p>
                </div>
                <button id="confirm-login-btn" class="btn btn-success btn-lg btn-scan">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Confirmar Login en PC
                </button>
                <button class="btn btn-outline-secondary btn-scan" onclick="resetScanner()">
                    <i class="fas fa-redo me-2"></i>
                    Escanear Otro QR
                </button>
            </div>

            <!-- Loading durante procesamiento -->
            <div id="processing-loading" class="text-center" style="display: none;">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Procesando...</span>
                </div>
                <p>Procesando código QR...</p>
            </div>

            <!-- Controles de cámara -->
            <div class="camera-controls">
                <button id="start-camera-btn" class="btn btn-primary btn-scan">
                    <i class="fas fa-camera me-2"></i>
                    Iniciar Cámara
                </button>
                <button id="stop-camera-btn" class="btn btn-danger btn-scan" style="display: none;">
                    <i class="fas fa-stop me-2"></i>
                    Detener Cámara
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-scan">
                    <i class="fas fa-arrow-left me-2"></i>
                    Volver Atrás
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Librería para escanear QR -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    
    <script>
        let currentScanData = null;
        let cameraStream = null;
        let qrScannerInterval = null;

        // Función mejorada para verificar compatibilidad
        function checkCameraSupport() {
            // Chrome móvil SI soporta la cámara, así que siempre retornamos true
            // pero verificamos si estamos en un contexto seguro (HTTPS)
            const isSecure = window.location.protocol === 'https:';
            const isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            
            console.log('Compatibilidad:', {
                navegador: 'Chrome',
                movil: isMobile,
                seguro: isSecure,
                userAgent: navigator.userAgent
            });
            
            return {
                supported: true, // Chrome móvil siempre soporta cámara
                needsHTTPS: !isSecure,
                isMobile: isMobile
            };
        }

        // Función optimizada para iniciar cámara
        async function startCamera() {
            console.log("📷 Iniciando cámara en Chrome móvil...");
            
            const support = checkCameraSupport();
            
            if (support.needsHTTPS) {
                showHTTPSRequired();
                return;
            }
            
            try {
                // Ocultar placeholder y mostrar cámara
                document.getElementById('scanner-loading').style.display = 'none';
                document.getElementById('camera-container').style.display = 'block';
                document.getElementById('start-camera-btn').style.display = 'none';
                document.getElementById('stop-camera-btn').style.display = 'inline-block';
                document.getElementById('solution-steps').style.display = 'none';

                // Configuración optimizada para Chrome móvil
                const constraints = {
                    video: {
                        facingMode: 'environment', // Cámara trasera
                        width: { ideal: 1280 },
                        height: { ideal: 720 },
                        aspectRatio: { ideal: 16/9 }
                    }
                };

                console.log("🎥 Solicitando acceso a la cámara en Chrome...");
                
                // Chrome móvil SI tiene navigator.mediaDevices.getUserMedia
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    cameraStream = await navigator.mediaDevices.getUserMedia(constraints)
                        .catch(error => {
                            console.error('Error específico de Chrome:', error);
                            throw error;
                        });
                } else {
                    // Fallback para versiones muy antiguas (prácticamente no existe)
                    throw new Error('PERMISO_DENEGADO');
                }

                const video = document.getElementById('camera-video');
                video.srcObject = cameraStream;
                
                // Esperar a que el video esté listo
                await new Promise((resolve, reject) => {
                    video.onloadedmetadata = () => {
                        video.play()
                            .then(resolve)
                            .catch(reject);
                    };
                    
                    video.onerror = reject;
                    
                    setTimeout(() => reject(new Error('TIMEOUT')), 8000);
                });

                console.log("✅ Cámara iniciada correctamente en Chrome");
                
                // Iniciar escaneo de QR
                startQRScanning();
                
            } catch (error) {
                console.error("❌ Error en Chrome móvil:", error);
                handleCameraError(error);
            }
        }

        // Mostrar error de HTTPS requerido
        function showHTTPSRequired() {
            document.getElementById('scanner-loading').style.display = 'block';
            document.getElementById('scanner-loading').innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-shield-alt me-2"></i>
                    <strong>Se requiere HTTPS</strong>
                    <p class="mb-2 mt-2">La cámara solo funciona en conexiones seguras (HTTPS).</p>
                    <p class="mb-0"><strong>Solución:</strong> Contacta al administrador para habilitar HTTPS en el sitio.</p>
                </div>
                <div class="mt-3">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Volver Atrás
                    </a>
                </div>
            `;
        }

        // Manejo de errores específico para Chrome
        function handleCameraError(error) {
            let errorMessage = '';
            let showSolution = true;

            if (error.name === 'NotAllowedError') {
                errorMessage = 'Permiso de cámara denegado. Por favor permite el acceso a la cámara cuando Chrome lo solicite.';
            } else if (error.name === 'NotFoundError') {
                errorMessage = 'No se encontró cámara en el dispositivo.';
            } else if (error.name === 'NotSupportedError') {
                errorMessage = 'Configuración de cámara no soportada.';
            } else if (error.name === 'NotReadableError') {
                errorMessage = 'La cámara está siendo usada por otra aplicación.';
            } else if (error.message === 'TIMEOUT') {
                errorMessage = 'La cámara tardó demasiado en responder.';
            } else if (error.message === 'PERMISO_DENEGADO') {
                errorMessage = 'No se pudo acceder a la cámara. Verifica los permisos.';
            } else {
                errorMessage = 'Error al acceder a la cámara.';
            }

            // Mostrar mensaje de error
            document.getElementById('scanner-loading').style.display = 'block';
            
            document.getElementById('scanner-loading').innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>${errorMessage}</strong>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary me-2" onclick="startCamera()">
                        <i class="fas fa-redo me-2"></i>
                        Reintentar
                    </button>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Volver Atrás
                    </a>
                </div>
            `;
            
            // Mostrar pasos de solución
            if (showSolution) {
                document.getElementById('solution-steps').style.display = 'block';
            }
            
            // Restaurar controles
            document.getElementById('camera-container').style.display = 'none';
            document.getElementById('start-camera-btn').style.display = 'inline-block';
            document.getElementById('stop-camera-btn').style.display = 'none';
            
            // Limpiar recursos
            stopCamera();
        }

        // Iniciar escaneo de QR
        function startQRScanning() {
            const video = document.getElementById('camera-video');
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            
            qrScannerInterval = setInterval(() => {
                if (video.readyState === video.HAVE_ENOUGH_DATA && video.videoWidth > 0) {
                    try {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);
                        
                        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                        const code = jsQR(imageData.data, imageData.width, imageData.height);
                        
                        if (code) {
                            console.log("✅ QR detectado:", code.data);
                            processQRCode(code.data);
                        }
                    } catch (error) {
                        console.error('Error en escaneo QR:', error);
                    }
                }
            }, 500);
        }

        // Procesar código QR detectado
        function processQRCode(qrData) {
            try {
                clearInterval(qrScannerInterval);
                
                const parsedData = JSON.parse(qrData);
                
                if (parsedData.token) {
                    handleQRScanned(parsedData);
                } else {
                    throw new Error('El QR no contiene token válido');
                }
            } catch (error) {
                console.error('Error procesando QR:', error);
                // Fallback para QR simple
                const fallbackData = {
                    token: qrData,
                    empleado_id: {{ auth()->check() && auth()->user()->empleado ? auth()->user()->empleado->id : '1' }},
                    user_name: '{{ auth()->user()->name ?? "Usuario" }}'
                };
                handleQRScanned(fallbackData);
            }
        }

        // Resto de funciones se mantienen igual...
        async function handleQRScanned(qrData) {
            console.log("🔄 Procesando QR escaneado...", qrData);
            
            stopCamera();
            
            document.getElementById('processing-loading').style.display = 'block';
            document.getElementById('camera-container').style.display = 'none';
            
            try {
                if (!qrData.token) {
                    throw new Error('Token no encontrado en el QR');
                }
                
                if (!qrData.empleado_id && {{ auth()->check() && auth()->user()->empleado ? 'true' : 'false' }}) {
                    qrData.empleado_id = {{ auth()->check() && auth()->user()->empleado ? auth()->user()->empleado->id : 'null' }};
                }
                
                currentScanData = qrData;
                showScanResult(qrData);
                
            } catch (error) {
                console.error('Error procesando QR:', error);
                showError('Error procesando el código QR: ' + error.message);
            } finally {
                document.getElementById('processing-loading').style.display = 'none';
            }
        }

        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
            if (qrScannerInterval) {
                clearInterval(qrScannerInterval);
                qrScannerInterval = null;
            }
        }

        function resetScanner() {
            stopCamera();
            document.getElementById('scan-result').style.display = 'none';
            document.getElementById('scanner-loading').style.display = 'block';
            document.getElementById('camera-container').style.display = 'none';
            document.getElementById('start-camera-btn').style.display = 'inline-block';
            document.getElementById('stop-camera-btn').style.display = 'none';
            document.getElementById('solution-steps').style.display = 'none';
            
            document.getElementById('scanner-loading').innerHTML = `
                <i class="fas fa-qrcode fa-2x text-muted mb-3"></i>
                <p>Listo para escanear</p>
                <small class="text-muted">Presiona "Iniciar Cámara" para comenzar</small>
            `;
            
            currentScanData = null;
        }

        function showScanResult(data) {
            document.getElementById('token-info').textContent = data.token ? (data.token.substring(0, 20) + '...') : 'N/A';
            document.getElementById('empleado-id-info').textContent = data.empleado_id || 'No especificado';
            document.getElementById('user-name-info').textContent = data.user_name || 'Usuario por confirmar';
            document.getElementById('status-info').textContent = 'QR válido - Listo para confirmar';
            document.getElementById('scan-result').style.display = 'block';
        }

        function showError(message) {
            document.getElementById('token-info').textContent = 'N/A';
            document.getElementById('empleado-id-info').textContent = 'N/A';
            document.getElementById('user-name-info').textContent = 'N/A';
            document.getElementById('status-info').textContent = message;
            document.getElementById('confirm-login-btn').style.display = 'none';
            document.getElementById('scan-result').style.display = 'block';
        }

        document.getElementById('confirm-login-btn').addEventListener('click', async function() {
            if (!currentScanData || !currentScanData.token) {
                alert('No hay datos de QR válidos para confirmar');
                return;
            }

            const confirmBtn = this;
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Confirmando...';

            try {
                const response = await fetch(`/confirm-qr-login/${currentScanData.token}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        empleado_id: currentScanData.empleado_id
                    })
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById('status-info').innerHTML = 
                        '<span class="text-success">✅ Login confirmado! El PC iniciará sesión automáticamente.</span>';
                    confirmBtn.innerHTML = '<i class="fas fa-check me-2"></i>Confirmado';
                    
                    setTimeout(() => {
                        window.location.href = '{{ route("empleado.dashboard_empleado") }}';
                    }, 3000);
                } else {
                    document.getElementById('status-info').textContent = 'Error: ' + (result.message || 'Error desconocido');
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Confirmar Login en PC';
                }
            } catch (error) {
                console.error('Error confirmando login:', error);
                document.getElementById('status-info').textContent = 'Error de conexión: ' + error.message;
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Confirmar Login en PC';
            }
        });

        document.getElementById('start-camera-btn').addEventListener('click', startCamera);
        document.getElementById('stop-camera-btn').addEventListener('click', resetScanner);

        document.addEventListener('DOMContentLoaded', function() {
            console.log("Página cargada, Chrome móvil listo para escanear QR");
        });
    </script>
</body>
</html>