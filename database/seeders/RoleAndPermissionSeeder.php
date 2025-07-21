<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'dashboard']);
        Permission::create(['name' => 'master.management']);
        Permission::create(['name' => 'pendaftaran.management']);
        Permission::create(['name' => 'user.management']);
        Permission::create(['name' => 'role.permission.management']);
        Permission::create(['name' => 'menu.management']);
        //user
        Permission::create(['name' => 'user.index']);
        Permission::create(['name' => 'user.create']);
        Permission::create(['name' => 'user.edit']);
        Permission::create(['name' => 'user.destroy']);
        Permission::create(['name' => 'user.import']);
        Permission::create(['name' => 'user.export']);

        //role
        Permission::create(['name' => 'role.index']);
        Permission::create(['name' => 'role.create']);
        Permission::create(['name' => 'role.edit']);
        Permission::create(['name' => 'role.destroy']);
        Permission::create(['name' => 'role.import']);
        Permission::create(['name' => 'role.export']);

        //permission
        Permission::create(['name' => 'permission.index']);
        Permission::create(['name' => 'permission.create']);
        Permission::create(['name' => 'permission.edit']);
        Permission::create(['name' => 'permission.destroy']);
        Permission::create(['name' => 'permission.import']);
        Permission::create(['name' => 'permission.export']);

        //assignpermission
        Permission::create(['name' => 'assign.index']);
        Permission::create(['name' => 'assign.create']);
        Permission::create(['name' => 'assign.edit']);
        Permission::create(['name' => 'assign.destroy']);

        //assingusertorole
        Permission::create(['name' => 'assign.user.index']);
        Permission::create(['name' => 'assign.user.create']);
        Permission::create(['name' => 'assign.user.edit']);

        //menu group 
        Permission::create(['name' => 'menu-group.index']);
        Permission::create(['name' => 'menu-group.create']);
        Permission::create(['name' => 'menu-group.edit']);
        Permission::create(['name' => 'menu-group.destroy']);

        //menu item 
        Permission::create(['name' => 'menu-item.index']);
        Permission::create(['name' => 'menu-item.create']);
        Permission::create(['name' => 'menu-item.edit']);
        Permission::create(['name' => 'menu-item.destroy']);

        //periode
        Permission::create(['name' => 'periode.index']);
        Permission::create(['name' => 'periode.create']);
        Permission::create(['name' => 'periode.edit']);
        Permission::create(['name' => 'periode.destroy']);

        //matapelajaranseleksi
        Permission::create(['name' => 'mata-pelajaran-seleksi.index']);
        Permission::create(['name' => 'mata-pelajaran-seleksi.create']);
        Permission::create(['name' => 'mata-pelajaran-seleksi.edit']);
        Permission::create(['name' => 'mata-pelajaran-seleksi.destroy']);

        //kategori prestasi
        Permission::create(['name' => 'kategori-prestasi.index']);
        Permission::create(['name' => 'kategori-prestasi.create']);
        Permission::create(['name' => 'kategori-prestasi.edit']);
        Permission::create(['name' => 'kategori-prestasi.destroy']);

        //bobot-pendaftaran
        Permission::create(['name' => 'bobot-pendaftaran.index']);
        Permission::create(['name' => 'bobot-pendaftaran.create']);
        Permission::create(['name' => 'bobot-pendaftaran.edit']);
        Permission::create(['name' => 'bobot-pendaftaran.destroy']);

        //jadwal-pendaftaran
        Permission::create(['name' => 'jadwal-pendaftaran.index']);
        Permission::create(['name' => 'jadwal-pendaftaran.create']);
        Permission::create(['name' => 'jadwal-pendaftaran.edit']);
        Permission::create(['name' => 'jadwal-pendaftaran.destroy']);

        //biodata-calon-siswa
        Permission::create(['name' => 'biodata-calon-siswa.index']);
        Permission::create(['name' => 'biodata-calon-siswa.create']);
        Permission::create(['name' => 'biodata-calon-siswa.edit']);
        Permission::create(['name' => 'biodata-calon-siswa.destroy']);

        //pendaftaran
        Permission::create(['name' => 'pendaftaran.index']);
        Permission::create(['name' => 'pendaftaran.create']);
        Permission::create(['name' => 'pendaftaran.edit']);
        Permission::create(['name' => 'pendaftaran.destroy']);
        Permission::create(['name' => 'pendaftaran.status']);


        //pengumuman
        Permission::create(['name' => 'pengumuman.index']);

        //faq
        Permission::create(['name' => 'faq.index']);
        Permission::create(['name' => 'faq.create']);
        Permission::create(['name' => 'faq.edit']);
        Permission::create(['name' => 'faq.destroy']);

        //quote
        Permission::create(['name' => 'quote.index']);
        Permission::create(['name' => 'quote.create']);
        Permission::create(['name' => 'quote.edit']);
        Permission::create(['name' => 'quote.destroy']);


        // create roles 
        $roleUser = Role::create(['name' => 'admin']);
        $roleUser->givePermissionTo([
            'dashboard',
            'user.management',
            'user.index',
            'pendaftaran.management',
            'pendaftaran.index',
            'pendaftaran.create',
            'pendaftaran.edit',
            'pendaftaran.destroy',
            'pendaftaran.status',
            'pengumuman.index',
        ]);

        $roleCalonSiswa = Role::create(['name' => 'calon-siswa']);
        $roleCalonSiswa->givePermissionTo([
            'dashboard',
            'pendaftaran.management',
            'biodata-calon-siswa.index',
            'biodata-calon-siswa.create',
            'biodata-calon-siswa.edit',
            'pendaftaran.index',
            'pendaftaran.create',
            'pendaftaran.edit',
            'pendaftaran.status',
            'pengumuman.index',
        ]);

        // create Super Admin
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        //assign user id 1 ke super admin
        $user = User::find(1);
        $user->assignRole('super-admin');
        $user = User::find(2);
        $user->assignRole('admin');
    }
}
