<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = Auth::user();
        if (!$user->can('view users')) {
            abort(403, 'No tienes permiso para ver la lista de usuarios.');
        }

        $users = User::select('id', 'name', 'email', 'created_at')
            ->with('roles')
            ->get();

        return Inertia::render('user/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user->can('create users')) {
            abort(403, 'No tienes permiso para ver la lista de usuarios.');
        }

        $roles = Role::all(['id', 'name']);

        return Inertia::render('user/CreateUser', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userAuth = Auth::user();
        if (!$userAuth->can('create users')) {
            abort(403, 'No tienes permiso para ver la lista de usuarios.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'selectedRoles' => ['required', 'array', 'min:1'], 
            'selectedRoles.*' => ['string', Rule::exists('roles', 'name')],
        ]);

        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if ($request->has('selectedRoles')) {
                $user->syncRoles($request->selectedRoles);
            }

            return redirect()->route('users.index')
                ->with('success', 'Usuario creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar al usuario. Intenta de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $user = User::find($id);

        if (!$user->can('view users')) {
            abort(403, 'No tienes permiso para ver la lista de usuarios.');
        }

        $user->load('roles');
        $roles = Role::all(['id', 'name']);
        $userRoles = $user->roles->pluck('name')->toArray();

        // dd($user->toArray());
        return Inertia::render('user/UpdateUser', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        $user = User::find($id);
        if (!$user->can('view users')) {
            abort(403, 'No tienes permiso para ver la lista de usuarios.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'selectedRoles' => ['required', 'array', 'min:1'],
            'selectedRoles.*' => ['string', Rule::exists('roles', 'name')],
        ]);

        try {

            $user->update($data);

            $user->syncRoles($data['selectedRoles'] ?? []);
            $permissions = Permission::whereIn('name', ['viewer'])->where('guard_name', 'web')->get();
            $user->syncPermissions($permissions);

            return redirect()->route('users.index')
                ->with('success', 'Usuario actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar al usuario. Intenta de nuevo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $user = User::find($id);
            if (!$user->can('view users')) {
                abort(403, 'No tienes permiso para ver la lista de usuarios.');
            }

            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo eliminar el usuario. Intenta de nuevo.');
        }
    }
}
