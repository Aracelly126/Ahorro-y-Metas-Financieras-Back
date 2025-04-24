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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $usuario = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cambia esta parte
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'token' => $token,
            'usuario' => $user
        ]);
    }

    /**
     * Obtener perfil del usuario autenticado
     */
    public function perfil(Request $request)
    {
        return response()->json([
            'usuario' => $request->user()->only(['id', 'name', 'email'])
        ]);
    }

    /**
     * Cerrar sesión (revoca todos los tokens)
     */
    public function cerrar_todas_sesion(Request $request)
    {
        // Obtenemos el ID del usuario antes de eliminar los tokens
        $userId = $request->user()->id;

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
        $userId = $request->user()->id;

        // Revocamos solo el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada en este dispositivo.',
            'user_id' => $userId
        ]);
    }
}
