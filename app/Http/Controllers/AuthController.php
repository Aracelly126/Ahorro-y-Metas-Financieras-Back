<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Registro de usuario (no inicia sesión automáticamente)
     */
    public function register(Request $request)
    {
        $request->validate([
            'user_nombre' => 'required|string|max:255',
            'user_apellido' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'user_genero' => 'nullable|in:M,F,O',
            'user_fec_nac' => 'nullable|date',
            'user_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userData = [
            'user_nombre' => $request->user_nombre,
            'user_apellido' => $request->user_apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_genero' => $request->user_genero,
            'user_fec_nac' => $request->user_fec_nac,
        ];

        // Manejo de la foto
        if ($request->hasFile('user_foto')) {
            $path = $request->file('user_foto')->store('profile-photos', 'public');
            $userData['user_foto_path'] = $path;
        }

        $usuario = User::create($userData);

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
    public function profile(Request $request)
    {
        $user = $request->user();
        $profileData = $user->only([
            'user_id',
            'user_nombre',
            'user_apellido',
            'email',
            'user_cedula',
            'user_genero',
            'user_fec_nac',
            'user_foto_path'
        ]);

        // Añadir URL completa de la foto
        if ($user->user_foto_path) {
            $profileData['user_foto_url'] = asset("storage/{$user->user_foto_path}");
        } else {
            $profileData['user_foto_url'] = null;
        }

        return response()->json([
            'usuario' => $profileData
        ]);
    }

    /**
     * Actualizar foto de perfil
     */
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'user_foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        // Eliminar la foto anterior si existe
        if ($user->user_foto_path) {
            Storage::disk('public')->delete($user->user_foto_path);
        }

        // Guardar la nueva foto
        $path = $request->file('user_foto')->store('profile-photos', 'public');
        $user->user_foto_path = $path;
        $user->save();

        return response()->json([
            'message' => 'Foto de perfil actualizada con éxito',
            'foto_url' => asset("storage/$path")
        ]);
    }

    /**
     * Cerrar sesión (revoca todos los tokens)
     */
    public function all_logout(Request $request)
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
    public function logout(Request $request)
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
