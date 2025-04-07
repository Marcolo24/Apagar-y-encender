<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sede;
use App\Models\Location;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function showUsers()
    {
        // Obtener todos los usuarios y sedes
        $usuarios = User::with('rol', 'sede')->get();
        $sedes = Sede::all();

        // Retornar la vista con los datos
        return view('dashboard.admin', compact('usuarios', 'sedes'));
    }

    public function buscarUsuarios(Request $request)
    {
        $nombre = $request->input('nombre');
        $email = $request->input('email');
        $sede = $request->input('sede');
        $orderBy = $request->input('orderBy');
        $orderDirection = $request->input('orderDirection');
        
        $query = User::with(['rol', 'sede']);

        if ($nombre) {
            $query->where('name', 'LIKE', "%{$nombre}%");
        }

        if ($email) {
            $query->where('correo', 'LIKE', "%{$email}%");
        }

        if ($sede) {
            $query->whereHas('sede', function($q) use ($sede) {
                $q->where('id', $sede);
            });
        }

        // Asegurarse de que el query base incluya los campos necesarios
        $query->select('users.*');

        if ($orderBy && $orderDirection) {
            if ($orderBy === 'sede') {
                $query->leftJoin('sede', 'users.id_sede', '=', 'sede.id')
                      ->orderBy('sede.nombre', $orderDirection);
            } elseif ($orderBy === 'rol') {
                $query->leftJoin('rol', 'users.id_rol', '=', 'rol.id')
                      ->orderBy('rol.nombre', $orderDirection);
            } else {
                $query->orderBy($orderBy, $orderDirection);
            }
        }

        $usuarios = $query->get();

        return response()->json($usuarios);
    }

    public function eliminarUsuario($id)
    {
        try {
            $usuario = User::findOrFail($id);
            $usuario->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar el usuario'], 500);
        }
    }

    public function getSedesRoles()
    {
        $sedes = Sede::all();
        $roles = Rol::all();
        
        return response()->json([
            'sedes' => $sedes,
            'roles' => $roles
        ]);
    }

    public function editarUsuario(Request $request, $id)
    {
        try {
            $usuario = User::findOrFail($id);
            
            $usuario->update([
                'name' => $request->name,
                'apellidos' => $request->apellidos,
                'correo' => $request->email,
                'id_sede' => $request->sede,
                'id_rol' => $request->rol
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function crearUsuario(Request $request)
    {
        try {
            Log::info('Datos recibidos:', $request->all());

            // Validación
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:users,correo',
                    'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
                ],
                'sede' => 'required|numeric|exists:sede,id',
                'rol' => 'required|numeric|exists:rol,id',
                'password' => 'required|string|min:6'
            ], [
                'sede.exists' => 'La sede seleccionada no existe',
                'rol.exists' => 'El rol seleccionado no existe',
                'email.unique' => 'Este correo ya está registrado',
                'email.regex' => 'El correo debe ser una dirección de Gmail válida'
            ]);

            // Crear el usuario
            $usuario = User::create([
                'name' => $validatedData['name'],
                'apellidos' => $validatedData['apellidos'],
                'email' => $validatedData['email'],
                'correo' => $validatedData['email'],
                'id_sede' => $validatedData['sede'],
                'id_rol' => $validatedData['rol'],
                'password' => Hash::make($validatedData['password']),
                'id_estado_usuario' => 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado correctamente'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al crear usuario:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario: ' . $e->getMessage()
            ], 500);
        }
    }
}