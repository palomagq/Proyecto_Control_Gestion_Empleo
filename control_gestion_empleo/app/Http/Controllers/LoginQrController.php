<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Qr;
use App\Models\Empleado;
use App\Models\QrLoginToken;
use App\Models\Credencial;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LoginQrController extends Controller
{
    /**
     * Mostrar página de login por QR (PC)
     */
    public function showQrLogin(Request $request)
    {
        $token = $request->get('token');
        $empleadoId = $request->get('empleado_id');
        
        Log::info("🔄 showQrLogin llamado", [
            'token' => $token,
            'empleado_id' => $empleadoId,
            'auth_check' => Auth::check(),
            'session_id' => session()->getId()
        ]);

        // ✅ SI YA ESTÁ AUTENTICADO, REDIRIGIR DIRECTAMENTE AL PERFIL
        if (Auth::check()) {
            $authEmpleadoId = Auth::user()->empleado->id ?? null; // Auth::user() devuelve Credencial
            if ($authEmpleadoId) {
                Log::info("✅ Usuario ya autenticado, redirigiendo al perfil", [
                    'credencial_id' => Auth::id(),
                    'empleado_id' => $authEmpleadoId
                ]);
                return redirect()->route('empleado.perfil', ['id' => $authEmpleadoId]);
            }
        }
        
        // ✅ SI VIENE CON TOKEN Y EMPLEADO_ID (DESDE CONFIRMACIÓN MÓVIL)
        if ($token && $empleadoId) {
            Log::info("🔍 Procesando login QR con token", [
                'token' => $token,
                'empleado_id' => $empleadoId
            ]);

            // Buscar el token confirmado
            $qrToken = QrLoginToken::where('token', $token)
                ->where('is_confirmed', true)
                ->where('empleado_id', $empleadoId)
                ->with('empleado.credencial') // <-- Cambiado de 'user' a 'credencial'
                ->first();

            if ($qrToken && $qrToken->empleado && $qrToken->empleado->credencial) {
                Log::info("✅ Token confirmado encontrado, iniciando sesión", [
                    'credencial_id' => $qrToken->empleado->credencial->id,
                    'username' => $qrToken->empleado->credencial->username
                ]);

                // ✅ INICIAR SESIÓN CON LA CREDENCIAL
                Auth::login($qrToken->empleado->credencial);
                session()->regenerate();
                session()->save();

                Log::info("🔐 Sesión después de login", [
                    'auth_check' => Auth::check(),
                    'credencial_id' => Auth::id(),
                    'session_id' => session()->getId()
                ]);

                // ✅ MARCAR TOKEN COMO USADO
                $qrToken->update(['is_active' => false]);
                
                // ✅ REDIRIGIR DIRECTAMENTE AL PERFIL
                Log::info("🎯 Redirigiendo al perfil del empleado", [
                    'empleado_id' => $empleadoId
                ]);
                
                return redirect()->to("/empleado/perfil/{$empleadoId}")
                    ->with('success', 'Login por QR exitoso!');
            } else {
                Log::warning("❌ Token inválido o no confirmado");
                // Si no está confirmado, mostrar el QR normal
                // No redirigir a login, mostrar página QR
            }
        }

        // Generar nuevo QR para login
        Log::info("🔄 Generando nuevo QR para login");
        $qrData = $this->generateQrForPC();
        return view('auth.qr-login', compact('token', 'empleadoId', 'qrData'));
    }

    /**
     * Método común para generar datos del QR
     */
    private function generateQrData()
    {
        try {
            $token = Str::random(32);
            $expiresAt = Carbon::now()->addMinutes(10);
            
            $credencial = Auth::user(); // Esto es una Credencial
            $empleadoId = $credencial->empleado->id ?? null;
            
            if ($credencial && $empleadoId) {
                // Invalidar tokens anteriores del empleado
                QrLoginToken::where('empleado_id', $empleadoId)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);

                // Crear nuevo token de login
                QrLoginToken::create([
                    'token' => $token,
                    'empleado_id' => $empleadoId,
                    'is_active' => true,
                    'is_confirmed' => false,
                    'expires_at' => $expiresAt
                ]);

                $qrData = json_encode([
                    'token' => $token,
                    'empleado_id' => $empleadoId,
                    'action' => 'qr_login'
                ]);
                
                return [
                    'success' => true,
                    'token' => $token,
                    'empleado_id' => $empleadoId,
                    'expires_at' => $expiresAt,
                    'qr_data' => $qrData,
                    'user_authenticated' => true,
                    'username' => $credencial->username
                ];
                
            } else {
                $qrData = json_encode([
                    'token' => $token,
                    'empleado_id' => null,
                    'action' => 'qr_login'
                ]);
                
                return [
                    'success' => true,
                    'token' => $token,
                    'empleado_id' => null,
                    'expires_at' => $expiresAt,
                    'qr_data' => $qrData,
                    'user_authenticated' => false,
                    'username' => 'Usuario por confirmar'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Error en generateQrData: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error generando datos QR: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generar QR para el PC
     */
    private function generateQrForPC()
    {
        DB::beginTransaction();
        
        try {
            $token = Str::random(32);
            $expiresAt = Carbon::now()->addMinutes(10);
            
            // ✅ EN EL PC NO HAY CREDENCIAL AUTENTICADA, PERO PODEMOS CREAR UN TOKEN VÁLIDO
            $empleadoId = null; // Será asignado cuando el móvil confirme
            
            // Crear token de login (sin empleado_id inicialmente)
            $qrLoginToken = QrLoginToken::create([
                'token' => $token,
                'empleado_id' => null, // ✅ Se asignará cuando el móvil confirme
                'is_active' => true,
                'is_confirmed' => false,
                'expires_at' => $expiresAt
            ]);

            $qrData = json_encode([
                'token' => $token,
                'empleado_id' => null, // ✅ Null inicialmente
                'action' => 'qr_login'
            ]);
            
            // Generar QR con los datos
            $qrCode = QrCode::format('svg')
                ->size(250)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($qrData);

            // Guardar en tabla_qr
            $qrModel = Qr::create([
                'imagen_qr' => $qrCode,
                'codigo_unico' => $token,
                'contenido_qr' => $qrData,
                'empleado_id' => null, // ✅ Null inicialmente
                'expiracion' => $expiresAt,
                'activo' => true
            ]);

            DB::commit();

            return [
                'success' => true,
                'token' => $token,
                'empleado_id' => null, // ✅ Null inicialmente
                'qr_data' => $qrData,
                'qr_image' => 'data:image/svg+xml;base64,' . base64_encode($qrCode),
                'expires_at' => $expiresAt->format('H:i:s'),
                'expires_timestamp' => $expiresAt->timestamp,
                'qr_id' => $qrModel->id,
                'user_authenticated' => false, // ✅ Correcto para PC
                'username' => 'Usuario por confirmar' // ✅ Correcto para PC
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generando QR para PC: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error generando código QR: ' . $e->getMessage()
            ];
        }
    }

    /**
     * API para generar QR token
     */
    public function generateQrTokenApi(Request $request)
    {
        DB::beginTransaction();
        
        try {
            Log::info('Iniciando generación de QR token');

            // Usar el mismo método para generar datos
            $qrData = $this->generateQrData();
            
            if (!$qrData['success']) {
                return response()->json($qrData, 500);
            }

            $token = $qrData['token'];
            $empleadoId = $qrData['empleado_id'];
            $expiresAt = $qrData['expires_at'];

            // Generar QR con los datos
            Log::info('Generando imagen QR');
            $qrCode = QrCode::format('svg')
                ->size(250)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($qrData['qr_data']);

            // Guardar en tabla_qr
            Log::info('Guardando en tabla_qr');
            $qrModel = Qr::create([
                'imagen_qr' => $qrCode,
                'codigo_unico' => $token,
                'contenido_qr' => $qrData['qr_data'],
                'empleado_id' => $empleadoId,
                'expiracion' => $expiresAt,
                'activo' => true
            ]);

            DB::commit();

            $response = [
                'success' => true,
                'token' => $token,
                'qr_data' => $qrData['qr_data'],
                'qr_image' => 'data:image/svg+xml;base64,' . base64_encode($qrCode),
                'expires_at' => $expiresAt->format('H:i:s'),
                'expires_timestamp' => $expiresAt->timestamp,
                'qr_id' => $qrModel->id,
                'user_authenticated' => $qrData['user_authenticated'],
                'username' => $qrData['username'],
                'empleado_id' => $empleadoId
            ];

            Log::info('QR generado exitosamente con token: ' . $token);
            
            return response()->json($response);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en generateQrTokenApi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error generando QR: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar estado del login por QR
     */
    public function checkQrLogin($token)
    {
        try {
            // Limpiar el token
            $token = trim($token);
            
            Log::info("🔍 Verificando token: " . $token);

            // Buscar token en la tabla principal
            $qrToken = QrLoginToken::where('token', $token)
                ->where('expires_at', '>', now())
                ->first();

            if ($qrToken) {
                $empleado = $qrToken->empleado;
                $username = $empleado && $empleado->credencial 
                    ? $empleado->credencial->username 
                    : 'Usuario por confirmar';
                
                Log::info("✅ Token válido encontrado", [
                    'token' => $token,
                    'is_confirmed' => $qrToken->is_confirmed,
                    'empleado_id' => $qrToken->empleado_id,
                    'username' => $username
                ]);

                $response = [
                    'success' => true,
                    'is_confirmed' => (bool) $qrToken->is_confirmed,
                    'empleado_id' => $qrToken->empleado_id,
                    'expires_in' => now()->diffInSeconds($qrToken->expires_at),
                    'username' => $username
                ];

                // ✅ Si está confirmado, agregar datos adicionales
                if ($qrToken->is_confirmed) {
                    $response['redirect_url'] = url("/empleado/perfil/{$qrToken->empleado_id}");
                    $response['login_success'] = true;
                    $response['message'] = 'Login confirmado, redirigiendo...';
                }

                return response()->json($response);
            }

            // Si no se encuentra, verificar si está expirado
            $expiredToken = QrLoginToken::where('token', $token)->first();
            if ($expiredToken) {
                Log::warning("❌ Token expirado: " . $token);
                return response()->json([
                    'success' => false,
                    'message' => 'Token expirado',
                    'expired' => true
                ]);
            }

            Log::warning("❌ Token no encontrado: " . $token);
            return response()->json([
                'success' => false,
                'message' => 'Token no encontrado'
            ]);

        } catch (\Exception $e) {
            Log::error('❌ ERROR en checkQrLogin: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error del servidor'
            ], 500);
        }
    }

    /**
     * Confirmar login por QR
     */
    public function confirmQrLogin(Request $request, $token)
    {
        DB::beginTransaction();
        
        try {
            Log::info("🔍 CONFIRMAR QR LOGIN - Token: " . $token);

            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes iniciar sesión en el móvil primero'
                ], 401);
            }

            $token = trim($token);
            $mobileEmpleadoId = Auth::user()->empleado->id ?? null; // Auth::user() es Credencial
            $username = Auth::user()->username;

            if (!$mobileEmpleadoId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credencial móvil no tiene empleado asociado'
                ], 400);
            }

            Log::info("📱 Credencial móvil confirmando:", [
                'empleado_id' => $mobileEmpleadoId,
                'username' => $username
            ]);

            // ✅ BUSCAR TOKEN SIN RESTRICCIONES ESTRICTAS
            $qrToken = QrLoginToken::where('token', $token)->first();

            if (!$qrToken) {
                Log::warning("❌ Token no encontrado en tabla_gr_login_tokens");
                
                // ✅ INTENTAR CON TABLA_GR
                $qrModel = Qr::where('codigo_unico', $token)->first();
                if (!$qrModel) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token no encontrado'
                    ], 404);
                }

                // ✅ CREAR TOKEN EN tabla_gr_login_tokens DESDE tabla_gr
                Log::info("🔄 Creando token en tabla_gr_login_tokens desde tabla_gr");
                $qrToken = QrLoginToken::create([
                    'token' => $token,
                    'empleado_id' => $mobileEmpleadoId,
                    'is_active' => true,
                    'is_confirmed' => true,
                    'expires_at' => $qrModel->expiracion ?? now()->addMinutes(10),
                    'confirmed_at' => now()
                ]);

                // ✅ DESACTIVAR EN TABLA_GR
                $qrModel->update(['activo' => false]);

                DB::commit();

                Log::info("✅ Token migrado y confirmado exitosamente");
                return response()->json([
                    'success' => true,
                    'message' => 'Login confirmado correctamente',
                    'username' => $username,
                    'empleado_id' => $mobileEmpleadoId,
                    'token' => $token
                ]);
            }

            // ✅ TOKEN EXISTE EN tabla_gr_login_tokens
            Log::info("📋 Token encontrado:", [
                'id' => $qrToken->id,
                'empleado_actual' => $qrToken->empleado_id,
                'is_confirmed' => $qrToken->is_confirmed,
                'is_active' => $qrToken->is_active
            ]);

            // ✅ PERMITIR CONFIRMACIÓN INCLUSO SI YA ESTABA CONFIRMADO
            $qrToken->update([
                'empleado_id' => $mobileEmpleadoId, // ✅ SIEMPRE ACTUALIZAR CON USUARIO ACTUAL
                'is_confirmed' => true,
                'is_active' => false, // ✅ DESACTIVAR DESPUÉS DE CONFIRMAR
                'confirmed_at' => now()
            ]);

            // ✅ ACTUALIZAR TABLA_GR TAMBIÉN
            Qr::where('codigo_unico', $token)->update([
                'empleado_id' => $mobileEmpleadoId,
                'activo' => false
            ]);

            DB::commit();

            Log::info("✅ Login QR confirmado exitosamente");
            return response()->json([
                'success' => true,
                'message' => 'Login confirmado correctamente',
                'username' => $username,
                'empleado_id' => $mobileEmpleadoId,
                'token' => $token
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ ERROR en confirmQrLogin: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error confirmando login: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesar escaneo QR
     */
    public function processQrScan(Request $request)
    {
        try {
            $scannedData = $request->input('qr_data');
            
            if (!$scannedData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se recibieron datos del QR'
                ], 400);
            }

            Log::info('QR escaneado desde móvil', ['data' => $scannedData]);

            $token = null;
            $empleadoId = null;

            // Procesar datos QR
            $jsonData = json_decode($scannedData, true);
            
            if (json_last_error() === JSON_ERROR_NONE && isset($jsonData['token'])) {
                $token = $jsonData['token'];
                $empleadoId = $jsonData['empleado_id'] ?? null;
            } else {
                // Intentar otros formatos...
                $cleanedData = trim($scannedData, '"');
                $jsonData = json_decode($cleanedData, true);
                
                if (json_last_error() === JSON_ERROR_NONE && isset($jsonData['token'])) {
                    $token = $jsonData['token'];
                    $empleadoId = $jsonData['empleado_id'] ?? null;
                }
            }

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR no válido - No contiene token'
                ], 400);
            }

            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes iniciar sesión en el móvil primero'
                ], 401);
            }

            // ✅ VERIFICAR TOKEN PERO NO VALIDAR EMPLEADO_ID
            $qrToken = QrLoginToken::where('token', $token)
                ->where('is_active', true)
                ->where('expires_at', '>', now())
                ->with('empleado.credencial') // <-- Cambiado de 'user' a 'credencial'
                ->first();

            $currentMobileEmpleadoId = Auth::user()->empleado->id ?? null;
            $currentUsername = Auth::user()->username;

            if ($qrToken) {
                Log::info("QR válido encontrado", [
                    'token' => $token,
                    'empleado_actual_qr' => $qrToken->empleado_id,
                    'empleado_movil' => $currentMobileEmpleadoId,
                    'username_movil' => $currentUsername
                ]);

                // ✅ MOSTRAR ADVERTENCIA SI EL QR PERTENECE A OTRO USUARIO
                $warning = '';
                if ($qrToken->empleado_id && $qrToken->empleado_id != $currentMobileEmpleadoId) {
                    $qrUsername = $qrToken->empleado->credencial->username ?? 'Usuario anterior';
                    $warning = "⚠️ Este QR fue generado para: <strong>{$qrUsername}</strong>. Se iniciará sesión como: <strong>{$currentUsername}</strong>";
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'token' => $token,
                        'empleado_id' => $currentMobileEmpleadoId,
                        'username' => $currentUsername,
                        'expires_at' => $qrToken->expires_at->format('H:i:s'),
                        'expires_in' => now()->diffInSeconds($qrToken->expires_at),
                        'user_authenticated' => true,
                        'confirm_url' => route('confirmar.qr.login', ['token' => $token]),
                        'warning' => $warning
                    ]
                ]);
            }

            // Verificar en tabla_gr (tokens anónimos)
            $qrModel = Qr::where('codigo_unico', $token)
                ->where('activo', true)
                ->where('expiracion', '>', now())
                ->first();

            if ($qrModel) {
                Log::info("QR anónimo válido encontrado", ['token' => $token]);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'token' => $token,
                        'empleado_id' => $currentMobileEmpleadoId,
                        'username' => $currentUsername,
                        'expires_at' => $qrModel->expiracion->format('H:i:s'),
                        'expires_in' => now()->diffInSeconds($qrModel->expiracion),
                        'user_authenticated' => true,
                        'confirm_url' => route('confirmar.qr.login', ['token' => $token]),
                        'warning' => ''
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Token inválido o expirado'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error en processQrScan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error procesando QR: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar escáner QR (móvil)
     */
    public function showQrScanner()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión primero');
        }

        return view('auth.qr-scanner');
    }

    /**
     * Procesar redirección después de confirmación QR
     */
    public function processQrLoginRedirect(Request $request)
    {
        $token = $request->get('token');
        $empleadoId = $request->get('empleado_id');
        
        Log::info("🔄 processQrLoginRedirect", [
            'token' => $token,
            'empleado_id' => $empleadoId
        ]);
        
        if (!$token || !$empleadoId) {
            return redirect()->route('login.qr')->with('error', 'Token o ID de empleado faltante');
        }
        
        // Buscar token confirmado
        $qrToken = QrLoginToken::where('token', $token)
            ->where('is_confirmed', true)
            ->where('empleado_id', $empleadoId)
            ->with('empleado.credencial') // <-- Cambiado de 'user' a 'credencial'
            ->first();
        
        if ($qrToken && $qrToken->empleado && $qrToken->empleado->credencial) {
            // ✅ INICIAR SESIÓN CON LA CREDENCIAL
            Auth::login($qrToken->empleado->credencial);
            session()->regenerate();
            
            // Marcar token como usado
            $qrToken->update(['is_active' => false]);
            
            Log::info("✅ Login exitoso vía QR, redirigiendo", [
                'credencial_id' => Auth::id(),
                'username' => Auth::user()->username,
                'empleado_id' => $empleadoId
            ]);
            
            return redirect()->route('empleado.perfil', ['id' => $empleadoId])
                ->with('success', 'Login por QR exitoso!');
        }
        
        Log::warning("❌ Token no confirmado o inválido", [
            'token' => $token,
            'empleado_id' => $empleadoId
        ]);
        
        return redirect()->route('login.qr')->with('error', 'Token inválido o expirado');
    }
}