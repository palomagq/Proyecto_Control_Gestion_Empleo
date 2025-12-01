<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login con QR - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .qr-container {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            margin: 2rem auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 600px;
            text-align: center;
        }
        
        .qr-icon {
            font-size: 4rem;
            color: #3b71ca;
            margin-bottom: 1rem;
        }
        
        .qr-image {
            max-width: 300px;
            width: 100%;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            background: white;
            margin: 2rem auto;
        }
        
        .timer {
            font-size: 1.5rem;
            font-weight: bold;
            color: #3b71ca;
            margin: 1rem 0;
        }
        
        .instructions {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: left;
        }
        
        .qr-info {
            background: #e8f4fd;
            border-radius: 8px;
            padding: 10px;
            margin: 10px 0;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="qr-container">
            <div class="qr-icon">
                <i class="fas fa-qrcode"></i>
            </div>
            
            <h1 class="mb-3">Login con Código QR</h1>
            <p class="lead text-muted mb-4">Escanea este código con tu móvil para acceder</p>

            <!-- QR Display -->
            <div id="qr-display">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Generando QR...</span>
                </div>
                <p>Generando código QR seguro...</p>
            </div>

            <!-- Información del QR -->
            <div id="qr-info" class="qr-info" style="display: none;">
                <i class="fas fa-info-circle me-2"></i>
                <span id="qr-info-text"></span>
            </div>

            <div class="timer">
                <i class="fas fa-clock me-2"></i>
                <span id="countdown">10:00</span>
            </div>

            <!-- Instructions -->
            <div class="instructions">
                <h5 class="mb-3"><i class="fas fa-mobile-alt me-2"></i>¿Cómo escanear?</h5>
                <div class="d-flex mb-3">
                    <div class="me-3 text-primary">
                        <i class="fas fa-1 fa-lg"></i>
                    </div>
                    <div>
                        <strong>Abre la cámara en tu móvil</strong><br>
                        <small class="text-muted">O una app escáner QR</small>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="me-3 text-primary">
                        <i class="fas fa-2 fa-lg"></i>
                    </div>
                    <div>
                        <strong>Apunta al código de arriba</strong><br>
                        <small class="text-muted">Mantén estable el dispositivo</small>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="me-3 text-primary">
                        <i class="fas fa-3 fa-lg"></i>
                    </div>
                    <div>
                        <strong>Confirma en tu móvil</strong><br>
                        <small class="text-muted">Se iniciará sesión automáticamente</small>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div id="status-message" class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <span id="status-text">Esperando escaneo...</span>
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Volver al Login Normal
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let currentToken = null;
        let currentEmpleadoId = null;
        let timeLeft = 600;
        let checkInterval = null;
        let countdownInterval = null;

        async function generateQRCode() {
            try {
                showLoadingState();
                
                const response = await fetch('{{ route("qr.generate") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                // Verificar si la respuesta es JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('El servidor devolvió una respuesta no válida');
                }

                const data = await response.json();

                if (data.success) {
                    currentToken = data.token;
                    currentEmpleadoId = data.empleado_id;
                    timeLeft = Math.floor((new Date(data.expires_timestamp * 1000) - new Date()) / 1000);
                    
                    displayQRCode(data);
                    startCountdown();
                    startQRChecking();
                    
                } else {
                    throw new Error(data.message || 'Error generando QR');
                }
            } catch (error) {
                console.error('Error:', error);
                showErrorState(error.message);
            }
        }

        async function checkQRStatus() {
            if (!currentToken) return;
            
            try {
                const response = await fetch(`/check-qr-login/${currentToken}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                // Verificar si la respuesta es JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    console.error('Respuesta no JSON recibida');
                    return;
                }

                const data = await response.json();
                
                if (data.success) {
                    if (data.is_confirmed) {
                        handleSuccessfulLogin(data);
                    } else {
                        updateStatusMessage(data);
                    }
                } else {
                    if (data.expired) {
                        handleQRExpiration();
                    }
                }
            } catch (error) {
                console.error('Error verificando QR:', error);
            }
        }


        function displayQRCode(data) {
            document.getElementById('qr-display').innerHTML = `
                <img src="${data.qr_image}" alt="Código QR" class="qr-image">
                <div class="mt-2">
                    <small class="text-muted">
                        <i class="fas fa-fingerprint me-1"></i>
                        Token: ${data.token.substring(0, 20)}...
                    </small>
                </div>
            `;
            
            showQRInfo(data);
        }

        function showQRInfo(data) {
            const qrInfo = document.getElementById('qr-info');
            const qrInfoText = document.getElementById('qr-info-text');
            
            if (data.user_authenticated && data.empleado_id) {
                qrInfoText.innerHTML = `Empleado ID: <strong>${data.empleado_id}</strong> | Token: ${data.token.substring(0, 10)}...`;
                qrInfo.style.display = 'block';
                updateStatusMessage(data);
            } else {
                qrInfoText.innerHTML = `Token: ${data.token.substring(0, 10)}... (Login anónimo)`;
                qrInfo.style.display = 'block';
                document.getElementById('status-text').innerHTML = 
                    '<span class="text-warning">Escanea el QR y confirma en tu móvil</span>';
            }
        }

        function updateStatusMessage(data) {
            const statusText = data.user_authenticated ? 
                `Esperando confirmación para: ${data.user_name} (ID: ${data.empleado_id})` :
                'Esperando confirmación del usuario...';
            
            document.getElementById('status-text').innerHTML = 
                `<span class="text-info">${statusText}</span>`;
        }

        function hideQRInfo() {
            document.getElementById('qr-info').style.display = 'none';
        }

        function showLoadingState() {
            document.getElementById('qr-display').innerHTML = `
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Generando QR...</span>
                </div>
                <p>Generando código QR seguro...</p>
            `;
            hideQRInfo();
        }

        function showErrorState(message) {
            document.getElementById('qr-display').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${message}
                </div>
                <button class="btn btn-primary mt-2" onclick="generateQRCode()">
                    <i class="fas fa-redo me-2"></i>
                    Reintentar
                </button>
            `;
            hideQRInfo();
            document.getElementById('status-text').innerHTML = 
                '<span class="text-danger">Error generando código QR</span>';
        }

        function startCountdown() {
            clearInterval(countdownInterval);
            
            countdownInterval = setInterval(() => {
                timeLeft--;
                
                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    handleQRExpiration();
                    return;
                }
                
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                document.getElementById('countdown').textContent = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    
                if (timeLeft < 60) {
                    document.getElementById('countdown').className = 'text-danger';
                }
            }, 1000);
        }

        function handleQRExpiration() {
            document.getElementById('status-text').innerHTML = 
                '<strong class="text-warning">QR expirado, generando nuevo...</strong>';
            
            setTimeout(() => {
                generateQRCode();
            }, 2000);
        }

        function startQRChecking() {
            clearInterval(checkInterval);
            
            checkInterval = setInterval(async () => {
                if (!currentToken) return;
                
                try {
                    const response = await fetch(`/check-qr-login/${currentToken}`);
                    const data = await response.json();
                    
                    if (data.success) {
                        if (data.is_confirmed) {
                            handleSuccessfulLogin(data);
                        } else {
                            // Actualizar estado de espera
                            const statusText = data.user_authenticated ? 
                                `Esperando confirmación para: ${data.user_name} (ID: ${data.empleado_id})` :
                                'Esperando confirmación del usuario...';
                            
                            document.getElementById('status-text').innerHTML = 
                                `<span class="text-info">${statusText}</span>`;
                        }
                    } else {
                        if (data.expired) {
                            handleQRExpiration();
                        }
                    }
                } catch (error) {
                    console.error('Error verificando QR:', error);
                }
            }, 2000);
        }

        function handleSuccessfulLogin(data) {
            console.log('🎉 Login exitoso! Procesando...', data);
            
            clearInterval(checkInterval);
            clearInterval(countdownInterval);
            
            document.getElementById('status-text').innerHTML = 
                `<strong class="text-success">✅ Login exitoso! Redirigiendo...</strong>`;
            
            // ✅ Construir URL de redirección con token y empleado_id
            const redirectUrl = `/qr-login-process?token=${encodeURIComponent(currentToken)}&empleado_id=${encodeURIComponent(data.empleado_id)}`;
            
            console.log('🔀 Redirigiendo a:', redirectUrl);
            
            // Redirigir después de 1.5 segundos
            setTimeout(() => {
                window.location.href = redirectUrl;
            }, 1500);
        }

        // Limpiar intervalos cuando la página se cierre
        window.addEventListener('beforeunload', function() {
            clearInterval(checkInterval);
            clearInterval(countdownInterval);
        });

        document.addEventListener('DOMContentLoaded', function() {
            generateQRCode();
        });
    </script>



</body>
</html>