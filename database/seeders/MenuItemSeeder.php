<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuItem::insert(
            [
                [
                    'name' => 'Dashboard',
                    'route' => 'dashboard',
                    'permission_name' => 'dashboard',
                    'menu_group_id' => 1,
                ],
                [
                    'name' => 'Periode',
                    'route' => 'master-management/periode',
                    'permission_name' => 'periode.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Mata Pelajaran Seleksi',
                    'route' => 'master-management/mata-pelajaran-seleksi',
                    'permission_name' => 'mata-pelajaran-seleksi.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Kategori Prestasi',
                    'route' => 'master-management/kategori-prestasi',
                    'permission_name' => 'kategori-prestasi.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Bobot Pendaftaran',
                    'route' => 'master-management/bobot-pendaftaran',
                    'permission_name' => 'bobot-pendaftaran.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Jadwal Pendaftaran',
                    'route' => 'master-management/jadwal-pendaftaran',
                    'permission_name' => 'jadwal-pendaftaran.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'FAQ',
                    'route' => 'master-management/faq',
                    'permission_name' => 'faq.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Quote',
                    'route' => 'master-management/quote',
                    'permission_name' => 'quote.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Contact',
                    'route' => 'master-management/contact',
                    'permission_name' => 'contact.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Biodata Calon Siswa',
                    'route' => 'pendaftaran-management/biodata-calon-siswa',
                    'permission_name' => 'biodata-calon-siswa.index',
                    'menu_group_id' => 3,
                ],
                [
                    'name' => 'Pendaftaran',
                    'route' => 'pendaftaran-management/pendaftaran',
                    'permission_name' => 'pendaftaran.index',
                    'menu_group_id' => 3,
                ],
                [
                    'name' => 'Pengumuman',
                    'route' => 'pendaftaran-management/pengumuman',
                    'permission_name' => 'pengumuman.index',
                    'menu_group_id' => 3,
                ],
                [
                    'name' => 'User List',
                    'route' => 'user-management/user',
                    'permission_name' => 'user.index',
                    'menu_group_id' => 4,
                ],
                [
                    'name' => 'Role List',
                    'route' => 'role-and-permission/role',
                    'permission_name' => 'role.index',
                    'menu_group_id' => 5,
                ],
                [
                    'name' => 'Permission List',
                    'route' => 'role-and-permission/permission',
                    'permission_name' => 'permission.index',
                    'menu_group_id' => 5,
                ],
                [
                    'name' => 'Permission To Role',
                    'route' => 'role-and-permission/assign',
                    'permission_name' => 'assign.index',
                    'menu_group_id' => 5,
                ],
                [
                    'name' => 'User To Role',
                    'route' => 'role-and-permission/assign-user',
                    'permission_name' => 'assign.user.index',
                    'menu_group_id' => 5,
                ],
                [
                    'name' => 'Menu Group',
                    'route' => 'menu-management/menu-group',
                    'permission_name' => 'menu-group.index',
                    'menu_group_id' => 6,
                ],
                [
                    'name' => 'Menu Item',
                    'route' => 'menu-management/menu-item',
                    'permission_name' => 'menu-item.index',
                    'menu_group_id' => 6,
                ],
            ]
        );
    }
}
