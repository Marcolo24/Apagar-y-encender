<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function showUsers()
    {
        // Obtener todos los usuarios
        $usuarios = User::with('rol', 'sede')->get();        

        // Retornar la vista con los datos
        return view('dashboard.admin', compact('usuarios'));
    }
}