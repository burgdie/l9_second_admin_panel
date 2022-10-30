<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        // $roles = Role::all();
        $roles = Role::whereNotIn('name', ['admin','manager'])->orderBy('id')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create(){
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'min:3']
        ]);

        Role::create($validated);

        return to_route('admin.roles.index')->with('message', 'New Role Added');

    }

    public function edit(Role $role)
    {

        $permissions = Permission::all();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'min:3']
        ]);

        $role->update($validated);

        return to_route('admin.roles.index')->with('message', 'Role Updated');

    }

    public function destroy(Role  $role)
    {
        $role->delete();
        return to_route('admin.roles.index')->with('message', 'Role successful deleted');
    }

    public function assignPermissions(Request $request, Role $role)
    {
        $role->permissions()->sync($request->permissions);

        return back()->with('message', 'Permissions assigned');

    }
}
