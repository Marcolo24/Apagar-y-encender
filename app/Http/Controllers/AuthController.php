<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->id_rol == 1 
                ? redirect()->route('dashboard.admin') 
                : redirect()->route('home');
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

        // Definir la clave de control de intentos fallidos
        $throttleKey = 'login_attempts_' . $request->ip();

        // Verificar si hay demasiados intentos
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->withErrors(['error' => 'Demasiados intentos. Inténtalo en 60 segundos.']);
        }

        // Buscar el usuario por email
        $user = User::where('email', $request->email)->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            RateLimiter::clear($throttleKey); // Resetear intentos fallidos

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
                    return redirect()->route('home');
            }
        }

        // Registrar el intento fallido
        RateLimiter::hit($throttleKey, 60); // Bloquea por 60 segundos después de 5 intentos

        return back()->withErrors(['credentials' => 'Credenciales incorrectas.']);
    }

    /*public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('index');
    }*/
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidar sesión y regenerar token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index');
    }

}
