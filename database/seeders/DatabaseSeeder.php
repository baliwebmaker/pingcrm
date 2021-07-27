<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Contact;
use App\Models\Organization;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $account = Account::create(['name' => 'Gak Jelas Serabutan']);

        //Create Roles
        $role_admin = Role::create(['name'=>'admin']);
        $role_user = Role::create(['name'=>'user']);

        //Add a user namely admin
        $admin = User::factory()->create([
            'account_id' => $account->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'owner' => true,
        ]);

        //Assign role admin to user admin
        $admin->assignRole($role_admin->name);

        //Create Permissions
        Permission::create(['name'=>'edit users']);
        Permission::create(['name'=>'create users']);
        Permission::create(['name'=>'delete users']);

        // Get all permission
        $permissions = Permission::pluck('id','id')->all();

        //Give all permission to admin
        $role_admin->syncPermissions($permissions);

        //give spesific permission
        $role_user->givePermissionTo('edit users');

        //give spesific user role to each user created
        User::factory(5)
        ->create(['account_id' => $account->id])
        ->each(function($user){
            $user->assignRole('user');
         }
        );

        $organizations = Organization::factory(100)
            ->create(['account_id' => $account->id]);

        Contact::factory(100)
            ->create(['account_id' => $account->id])
            ->each(function ($contact) use ($organizations) {
                $contact->update(['organization_id' => $organizations->random()->id]);
            });
    }
}