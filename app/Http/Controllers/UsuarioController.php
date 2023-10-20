<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    //El usuario se podra registrar
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'apellidos' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
            'perfil'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error de validación', 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->input('nombres'),
            'apellidos' => $request->input('apellidos'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'perfil' => $request->input('perfil'),
        ]);    

        return response()->json([
            'message' => 'Usuario registrado con éxito',
            'data' => $user
        ]);
    }
    //El usuario podra inicar sesion
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'No autorizado'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        return response()->json([
            'message' => 'Hola ' . $user->name,
            'user' => $user
        ]);
    }
}
