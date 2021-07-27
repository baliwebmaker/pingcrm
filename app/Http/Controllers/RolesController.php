<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Inertia\Inertia;

use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Roles/Index', [
            'roles' => Role::orderBy('id','ASC')
            ->get()
            ->transform( function ($role) {
                    return[
                        'id' => $role->id,
                        'name' => $role->name,
                        'guard_name' => $role->guard_name,
                        'assignedPermissions' => $role->permissions
                        ->map
                        ->only('id', 'name'),
                         ];
                    }
			)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Roles/Create', [
            'unassignedPermissions' => Permission::all()
            ->map->only('id', 'name'),
            'assignedPermissions' => array(),

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate input
        $request->validate(
            [
                'name' => ['required', 'max:50'],
                'guard_name' => ['required', 'max:50'],
            ]
        );

        $role = Role::create(
            [
                'name' => $request->input('name'),
                'guard_name' => $request->input('guard_name'),
            ]
        );

        $permissions = $request->input('0');

        $role->syncPermissions($permissions);

        return Redirect::route('roles')->with('success', 'Role created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
        return Inertia::render('Roles/Edit', [

            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
            ],

            'unassignedPermissions' => Permission::whereNotIn('id', $role->permissions->map->only('id'))
                ->get()
                ->map
                ->only('id', 'name') ,

            'assignedPermissions' => $role->permissions->map->only('id', 'name')

        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $role)
    {
        //validate input
        $request->validate(
            [
                'name' => ['required', 'max:50'],
                'guard_name' => ['required', 'max:50'],
            ]
        );

         $permissions = $request->input('0');

         $role = Role::find($role);

         $role->name = $request->input('name');

         $role->guard_name = $request->input('guard_name');

         $role->save();

         $role->syncPermissions($permissions);

         return Redirect::back()->with('success', 'Role updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //erase completely
        $role->forceDelete();

        return Redirect::route('roles')->with('success', 'Role Deleted.');
    }
}