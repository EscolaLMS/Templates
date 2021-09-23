<?php

namespace EscolaLms\Templates\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * @todo remove neccesity of using 'web' guard
 */
class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $apiAdmin = Role::findOrCreate('admin', 'api');
        $permissions = ['delete templates', 'create templates', 'update templates'];

        // TODO this should also include "list" and "updateOwn" 

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'api');
        }

        $apiAdmin->givePermissionTo($permissions);
    }
}
