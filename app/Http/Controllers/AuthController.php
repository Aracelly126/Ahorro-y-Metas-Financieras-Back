<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Registro de usuario (no inicia sesión automáticamente)
     */
    public function registro(Request $request)
    {
        $request->validate([
            'user_nombre' => 'required|string|max:255',
            'user_apellido' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_cedula' => 'required|string|unique:users',
            'user_genero' => 'nullable|in:M,F,O',
            'user_fec_nac' => 'nullable|date',
        ]);

        $usuario = User::create([
            'user_nombre' => $request->user_nombre,
            'user_apellido' => $request->user_apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_cedula' => $request->user_cedula,
            'user_genero' => $request->user_genero,
            'user_fec_nac' => $request->user_fec_nac,
        ]);

        return response()->json([
            'message' => 'Usuario registrado con éxito',
            'usuario' => $usuario
        ], 201);
    }

    /**
     * Inicio de sesión con Sanctum (sin sesiones tradicionales)
     */
    public function loginsito(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Mapeamos los campos personalizados a los que espera Auth
        $authCredentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!Auth::attempt($authCredentials)) {
            return response()->json([
                'message' => 'Credenciales inválidas',
                'errors' => [
                    'email' => ['Correo o contraseña incorrectos']
                ]
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'token' => $token,
            'usuario' => $user
        ], 200);
    }

    /**
     * Obtener perfil del usuario autenticado
     */
    public function perfil(Request $request)
    {
        return response()->json([
            'usuario' => $request->user()->only([
                'user_id',
                'user_nombre',
                'user_apellido',
                'email',
                'user_cedula',
                'user_genero',
                'user_fec_nac',
                'user_foto_path'
            ])
        ]);
    }

    /**
     * Cerrar sesión (revoca todos los tokens)
     */
    public function cerrar_todas_sesion(Request $request)
    {
        // Obtenemos el ID del usuario antes de eliminar los tokens
        $userId = $request->user()->user_id;

        // Revocamos todos los tokens
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Sesión cerrada. Todos los tokens revocados.',
            'user_id' => $userId
        ]);
    }

    /**
     * Cerrar sesión en dispositivo actual (revoca solo el token actual)
     */
    public function cerrar_sesion(Request $request)
    {
        // Obtenemos el ID del usuario antes de eliminar el token
        $userId = $request->user()->user_id;

        // Revocamos solo el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada en este dispositivo.',
            'user_id' => $userId
        ]);
    }
}
