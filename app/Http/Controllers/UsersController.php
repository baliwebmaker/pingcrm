<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class UsersController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'owner', 'role', 'trashed'),
            'users' => Auth::user()->account->users()
                ->orderByName()
                ->filter(Request::only('search', 'owner', 'role', 'trashed'))
                ->get()
                ->transform(function ($user) {  
                    return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'owner' => $user->owner,
                    'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $user->deleted_at,
                    'role' => $user->getRoleNames(),
                    ];
                }),
                'roles' => Role::orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create',[
            'roles' => Role::orderBy('name')
            ->get()
            ->map
            ->only('id', 'name'),
        ]
        );
    }

    public function store()
    {
        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
            'role' => ['required'],
        ]);

        $user = Auth::user()->account->users()->create([
            'first_name' => Request::get('first_name'),
            'last_name' => Request::get('last_name'),
            'email' => Request::get('email'),
            'password' => Request::get('password'),
            'owner' => Request::get('owner'),
            'photo_path' => Request::file('photo') ? Request::file('photo')->store('users') : null,
        ]);

        $user->assignRole(Request::get('role'));

        return Redirect::route('users')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
         //Just user with same ID can edit
         if(!($user->id === Auth::user()->id)&&  !(Auth::user()->hasRole('admin'))) abort (403,'Unauthorized Action');

        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'owner' => $user->owner,
                'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'deleted_at' => $user->deleted_at,
                'roles'=> $user->roles,
            ],
            'roles'=>Role::orderBy('name')
            ->get()
            ->map->only('id','name'),
        ]);
    }

    public function update(User $user)
    {
        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('error', 'Updating the demo user is not allowed.');
        }

        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
            'role' => ['required'],
        ]);

        $user->update(Request::only('first_name', 'last_name', 'email', 'owner'));

        //Remove role 1st
        $user->removeRole(Request::get('role'));

        //then assign role
        $user->assignRole(Request::get('role'));

        if (Request::file('photo')) {
            $user->update(['photo_path' => Request::file('photo')->store('users')]);
        }

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('error', 'Deleting the demo user is not allowed.');
        }

        $user->delete();

        return Redirect::back()->with('success', 'User deleted.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', 'User restored.');
    }

    public function trash(User $user){
        $role = $user->getRoleNames()[0];
        $user->removeRole($role);
        $user->forceDelete();
        return Redirect::route('users')->with('success', 'User Trashed.');
    }
}