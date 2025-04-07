<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Rol;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            switch (Auth::user()->id_rol) {
                case 1:
                    return redirect()->route('dashboard.admin');
                case 2:
                    return redirect()->route('dashboard.tecnico');
                case 3:
                    return redirect()->route('dashboard.gestor');
                case 4:
                    return redirect()->route('dashboard.cliente');
                default:
                    Auth::logout();
                    return redirect()->route('index')->withErrors(['access' => 'No tienes acceso al sistema.']);
            }
        }
        return view('auth.index');
    }


    public function login(Request $request)
    {
        // Validación del formulario
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        

        // Buscar el usuario por email
        $user = User::where('email', $request->email)->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user); // Resetear intentos fallidos

            switch ($user->id_rol) {
                case 1:
                    return redirect()->route('dashboard.admin');
                case 2:
                    return redirect()->route('dashboard.tecnico');
                case 3:
                    return redirect()->route('dashboard.gestor');
                case 4:
                    return redirect()->route('dashboard.cliente');
                default:
                    return redirect()->route('index');
            }
        }

        return back()->withErrors(['credentials' => 'Credenciales incorrectas.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidar sesión y regenerar token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index');
    }
}
