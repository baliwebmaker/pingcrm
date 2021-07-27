<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use Inertia\Inertia;
use Spatie\Permission\Models\Permission;


use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Permissions/Index', [
            'permissions' => Permission::orderBy('id','ASC')
            ->get()
            ->transform( 
                function ($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'guard_name' => $permission->guard_name,
                    ];
                }
            ),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Permissions/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'max:50'],
                'guard_name' => ['required', 'max:50'],
            ]
        );
        //Create permissions
        Permission::create(
            [
                'name' => $request->input('name'),
                'guard_name' => $request->input('guard_name'),
            ]
        );

        return Redirect::route('permissions')->with('success', 'Permission created.');
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
    public function edit(Permission $permission)
    {
        return Inertia::render('Permissions/Edit', [ 
            'permission' =>[
                'id' => $permission->id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $permission)
    {
        $request->validate(
            [
                'name' => ['required', 'max:50'],
                'guard_name' => ['required', 'max:50'],
            ]
        );

        $permission = Permission::find($permission);

        $permission->name = $request->input('name');

        $permission->guard_name = $request->input('guard_name');

        $permission->save();

        return Redirect::back()->with('success', 'Permission updated.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Permission $permission)
    {
        $permission->forceDelete();

        return Redirect::route('permissions')->with('success', 'Permission Trashed.');

    }
}