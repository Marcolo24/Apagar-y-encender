<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->role_id == 1 
                ? redirect()->route('admin.index') 
                : redirect()->route('home');
        }
        return view('auth.index');
    }

    public function login(Request $request)
    {
        // Validación solo para el email, que sea válido y exista en la base de datos
        $request->validate([
            'email' => 'required|email', // Verificamos que el email sea válido
            'password' => 'required', // La contraseña es obligatoria
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        // Buscamos al usuario por su email
        $user = User::where('email', $request->email)->first();

        // Verificamos si el usuario existe y la contraseña es correcta
        if ($user && Hash::check($request->password, $user->password)) {
            // Si las credenciales son correctas, iniciamos sesión
            Auth::login($user);  
            return redirect()->route('home');
        }

        // Si las credenciales no son correctas, retornamos con un error
        return back()->withErrors(['credentials' => 'Credenciales incorrectas.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('index');
    }
}