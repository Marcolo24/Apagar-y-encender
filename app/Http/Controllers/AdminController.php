<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sede;
use App\Models\Location;
use App\Models\Rol;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public function showUsers()
    {
        if (Auth::user()->id_rol != 1) {
            return redirect()->route('index')->withErrors(['access' => 'No tienes permiso para acceder aquí.']);
        }
        // Obtener solo usuarios activos y sedes
        $usuarios = User::with('rol', 'sede')
            ->where('id_estado_usuario', 1)
            ->get();
        $sedes = Sede::all();

        // Retornar la vista con los datos necesarios
        return view('dashboard.admin', compact('usuarios', 'sedes'));
    }

    public function buscarUsuarios(Request $request)
    {
        $nombre = $request->input('nombre');
        $email = $request->input('email');
        $sede = $request->input('sede');
        $orderBy = $request->input('orderBy');
        $orderDirection = $request->input('orderDirection');
        
        $query = User::with(['rol', 'sede'])
            ->where('id_estado_usuario', 1); // Añadir filtro de estado

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
            $usuario->update(['id_estado_usuario' => 2]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al desactivar el usuario'], 500);
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
            // Verificar si existe un usuario con el mismo correo
            $existingUser = User::where('correo', $request->email)
                ->where('id_estado_usuario', 1)
                ->first();

            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe un usuario activo con este correo electrónico'
                ], 422);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
                ],
                'sede' => 'required|numeric|exists:sede,id',
                'rol' => 'required|numeric|exists:rol,id',
                'password' => 'required|string|min:6'
            ]);

            User::create([
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
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCategorias()
    {
        try {
            // Solo obtener categorías principales (no subcategorías)
            $categorias = Categoria::all(['id', 'nombre']);
            
            return response()->json([
                'success' => true,
                'categorias' => $categorias
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las categorías: ' . $e->getMessage()
            ], 500);
        }
    }

    public function crearCategoria(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'tipo' => 'required|in:categoria,subcategoria',
                'nombre' => 'required|string|max:255',
                'id_categoria' => 'nullable|required_if:tipo,subcategoria|exists:categoria,id'
            ], [
                'id_categoria.required_if' => 'Debe seleccionar una categoría padre para crear una subcategoría',
                'id_categoria.exists' => 'La categoría padre seleccionada no existe',
                'nombre.required' => 'El nombre es obligatorio'
            ]);

            DB::beginTransaction();
            
            if ($validatedData['tipo'] === 'categoria') {
                Categoria::create([
                    'nombre' => $validatedData['nombre']
                ]);
            } else {
                Subcategoria::create([
                    'nombre' => $validatedData['nombre'],
                    'id_categoria' => $validatedData['id_categoria']
                ]);
            }
            
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ($validatedData['tipo'] === 'categoria' ? 'Categoría' : 'Subcategoría') . ' creada correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la categoría: ' . $e->getMessage()
            ], 500);
        }
    }
}