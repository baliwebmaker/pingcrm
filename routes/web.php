<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

//admin role
Route::group([ 'middleware' => ['role:admin'] ], function () { 
    
        // Users
        Route::get('users', [UsersController::class, 'index'])
        ->name('users')
        ->middleware('auth');
    
        Route::get('users/create', [UsersController::class, 'create'])
        ->name('users.create')
        ->middleware('auth');
    
        Route::post('users', [UsersController::class, 'store'])
        ->name('users.store')
        ->middleware('auth');

        Route::delete('users/{user}', [UsersController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('auth');

        Route::put('users/{user}/restore', [UsersController::class, 'restore'])
        ->name('users.restore')
        ->middleware('auth');

        Route::put('users/{user}/trash', [UsersController::class, 'trash'])
        ->name('users.trash')
        ->middleware('auth');

        //Permissions
        Route::get('permissions', [PermissionsController::class, 'index'])
        ->name('permissions')
        ->middleware('auth');

        Route::get('permissions/create', [PermissionsController::class, 'create'])
        ->name('permissions.create')
        ->middleware('auth');

        Route::get('permissions/{permission}/edit', [PermissionsController::class, 'edit'])
        ->name('permissions.edit')
        ->middleware('auth');

        Route::post('permissions', [PermissionsController::class, 'store'])
        ->name('permissions.store')
        ->middleware('auth');

        Route::post('permissions/{permission}/update', [PermissionsController::class, 'update'])
        ->name('permissions.update')
        ->middleware('auth');

        Route::delete('permissions/{permission}', [PermissionsController::class, 'destroy'])
        ->name('permissions.destroy')
        ->middleware('auth');

        //Roles
        Route::get('roles', [RolesController::class, 'index'])
        ->name('roles')
        ->middleware('auth');

        Route::get('roles/create', [RolesController::class, 'create'])
        ->name('roles.create')
        ->middleware('auth');

        Route::get('roles/{role}/edit', [RolesController::class, 'edit'])
        ->name('roles.edit')
        ->middleware('auth');

        Route::post('roles', [RolesController::class, 'store'])
        ->name('roles.store')
        ->middleware('auth');

        Route::post('roles/{role}/update', [RolesController::class, 'update'])
        ->name('roles.update')
        ->middleware('auth');

        Route::delete('roles/{role}', [RolesController::class, 'destroy'])
        ->name('roles.destroy')
        ->middleware('auth');


 });

//admin and user can edit and update
Route::group(['middleware' => ['role:admin|user']],function () {  
   
    Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');
    
    Route::put('users/{user}', [UsersController::class, 'update'])
        ->name('users.update')
        ->middleware('auth');
});


// Organizations

Route::get('organizations', [OrganizationsController::class, 'index'])
    ->name('organizations')
    ->middleware('auth');

Route::get('organizations/create', [OrganizationsController::class, 'create'])
    ->name('organizations.create')
    ->middleware('auth');

Route::post('organizations', [OrganizationsController::class, 'store'])
    ->name('organizations.store')
    ->middleware('auth');

Route::get('organizations/{organization}/edit', [OrganizationsController::class, 'edit'])
    ->name('organizations.edit')
    ->middleware('auth');

Route::put('organizations/{organization}', [OrganizationsController::class, 'update'])
    ->name('organizations.update')
    ->middleware('auth');

Route::delete('organizations/{organization}', [OrganizationsController::class, 'destroy'])
    ->name('organizations.destroy')
    ->middleware('auth');

Route::put('organizations/{organization}/restore', [OrganizationsController::class, 'restore'])
    ->name('organizations.restore')
    ->middleware('auth');

// Contacts

Route::get('contacts', [ContactsController::class, 'index'])
    ->name('contacts')
    ->middleware('auth');

Route::get('contacts/create', [ContactsController::class, 'create'])
    ->name('contacts.create')
    ->middleware('auth');

Route::post('contacts', [ContactsController::class, 'store'])
    ->name('contacts.store')
    ->middleware('auth');

Route::get('contacts/{contact}/edit', [ContactsController::class, 'edit'])
    ->name('contacts.edit')
    ->middleware('auth');

Route::put('contacts/{contact}', [ContactsController::class, 'update'])
    ->name('contacts.update')
    ->middleware('auth');

Route::delete('contacts/{contact}', [ContactsController::class, 'destroy'])
    ->name('contacts.destroy')
    ->middleware('auth');

Route::put('contacts/{contact}/restore', [ContactsController::class, 'restore'])
    ->name('contacts.restore')
    ->middleware('auth');

// Reports

Route::get('reports', [ReportsController::class, 'index'])
    ->name('reports')
    ->middleware('auth');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');
