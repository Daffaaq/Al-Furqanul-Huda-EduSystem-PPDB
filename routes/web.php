<?php

use App\Http\Controllers\BiodataCalonSiswaController;
use App\Http\Controllers\BobotPendaftaranController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\JadwalPendaftaranController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\MataPelajaranSeleksiController;
use App\Http\Controllers\Menu\MenuGroupController;
use App\Http\Controllers\Menu\MenuItemController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\RoleAndPermission\AssignPermissionController;
use App\Http\Controllers\RoleAndPermission\AssignUserToRoleController;
use App\Http\Controllers\RoleAndPermission\PermissionController;
use App\Http\Controllers\RoleAndPermission\RoleController;
use App\Http\Controllers\TahapanLamaranController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LandingPageController::class, 'index'])->name('landing-page');
Route::post('/pendaftaran', [LandingPageController::class, 'PendaftaranCalonSiswa'])->middleware('guest')->name('pendaftaran.guest.store');
Route::get('/quotes/login', [QuoteController::class, 'ajax'])->name('quotes.login')->middleware('guest');
Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/generate-dummy-pendaftar', [DashboardController::class, 'generateDummyPendaftar'])->name('generate-dummy-pendaftar');
    Route::post('/publish-ranking', [DashboardController::class, 'publishRanking'])->name('publish-ranking');
    Route::post('/move-next-jadwal', [DashboardController::class, 'moveToNextJadwal'])->name('move-next-jadwal');
    Route::post('/set/biodata', [DashboardController::class, 'SettingBiodata'])->name('set-biodata');
    Route::post('/set/upload-nilai', [DashboardController::class, 'settingUploadNilai'])->name('set-upload-nilai');

    Route::post('/re-registration', [PengumumanController::class, 'reRegistration'])->name('re-registration');

    Route::post('/cadangan/decision', [PengumumanController::class, 'handleCadanganDecision'])->name('cadangan.decision');

    Route::get('/periode/active/topbar', function () {
        return view('layouts.periodeactive');
    });


    Route::prefix('master-management')->group(function () {
        Route::resource('periode', PeriodeController::class);
        Route::post('/periode/list', [PeriodeController::class, 'list'])->name('periode.list');
        Route::get('/periode/{periode}/check-active-except', [PeriodeController::class, 'checkActiveExcept']);
        Route::post('/periode/{periode}/update-status', [PeriodeController::class, 'updateStatus'])->name('periode.updateStatus');

        //mata-pelajaran-seleksi
        Route::resource('mata-pelajaran-seleksi', MataPelajaranSeleksiController::class);
        Route::post('/mata-pelajaran-seleksi/list', [MataPelajaranSeleksiController::class, 'list'])->name('mata-pelajaran-seleksi.list');

        //bobot-pendaftaran
        Route::resource('bobot-pendaftaran', BobotPendaftaranController::class);
        Route::post('/bobot-pendaftaran/list', [BobotPendaftaranController::class, 'list'])->name('bobot-pendaftaran.list');

        //jadwal-pendaftaran
        Route::resource('jadwal-pendaftaran', JadwalPendaftaranController::class);
        Route::post('/jadwal-pendaftaran/list', [JadwalPendaftaranController::class, 'list'])->name('jadwal-pendaftaran.list');

        //kategori-prestasi
        Route::resource('kategori-prestasi', PrestasiController::class);
        Route::post('/kategori-prestasi/list', [PrestasiController::class, 'listPrestasi'])->name('kategori-prestasi.list');
        Route::get('/kategori-prestasi/{kategori}/prestasi-count', [PrestasiController::class, 'checkPrestasiCount'])
            ->name('kategori-prestasi.prestasi-count');
        Route::delete('/kategori-prestasi/{id}/kategori', [PrestasiController::class, 'destroyKategoriPrestasi']);
        Route::post('/kategori-prestasi/prestasi/list', [PrestasiController::class, 'listKategoriPrestasi'])->name('kategori-prestasi.prestasi.list');
        Route::get('/kategori-prestasi/{id}/kategori', [PrestasiController::class, 'editKategoriPrestasi'])->name('kategori-prestasi.prestasi.edit');
        Route::put('/kategori-prestasi/{id}/kategori', [PrestasiController::class, 'updateKategoriPrestasi'])->name('kategori-prestasi.prestasi.update');
        Route::get('/kategori-prestasi/{id}/add-prestasi', [PrestasiController::class, 'addPrestasi'])->name('kategori-prestasi.prestasi.add');
        Route::post('/kategori-prestasi/{id}/store-prestasi', [PrestasiController::class, 'storePrestasi'])->name('kategori-prestasi.prestasi.store');

        //faq
        Route::resource('faq', FaqController::class);
        Route::post('/faq/list', [FaqController::class, 'list'])->name('faq.list');

        //quote
        Route::resource('quote', QuoteController::class);
        Route::post('/quote/list', [QuoteController::class, 'list'])->name('quote.list');

        //contact
        Route::resource('contact', KontakController::class);
        Route::post('/contact/updateJamOperasional', [KontakController::class, 'updateJamOperasional'])->name('contact.updateJamOperasional');
    });

    Route::prefix('pendaftaran-management')->group(function () {
        //biodata-calon-siswa
        Route::resource('biodata-calon-siswa', BiodataCalonSiswaController::class);
        Route::post('/biodata-calon-siswa/list', [BiodataCalonSiswaController::class, 'list'])->name('biodata-calon-siswa.list');

        //pendaftaran
        Route::resource('pendaftaran', PendaftaranController::class);
        Route::post('/pendaftaran/list', [PendaftaranController::class, 'list'])->name('pendaftaran.list');
        Route::post('/pendaftaran/daftar/{id}', [PendaftaranController::class, 'daftar'])->name('pendaftaran.daftar');
        Route::get('/pendaftaran/{id}/raport-seleksi', [PendaftaranController::class, 'showRaport'])->name('pendaftaran.raport');
        Route::post('/pendaftaran/{id}/raport-seleksi', [PendaftaranController::class, 'storeSeleksi'])->name('pendaftaran.raport.store');
        Route::post('/pendaftaran/{id}/mata-pelajaran-seleksi', [PendaftaranController::class, 'listSeleksiMapelAkademik'])->name('pendaftaran.mata-pelajaran-seleksi.list');
        Route::post('/pendaftaran/{id}/mata-pelajaran-seleksi/list/all', [PendaftaranController::class, 'listSeleksiMapelAkademikAll'])->name('pendaftaran.mata-pelajaran-seleksi-all.list');
        Route::post('/pendaftaran/{id}/prestasi-seleksi', [PendaftaranController::class, 'listSeleksiPrestasi'])->name('pendaftaran.prestasi.list');
        Route::post('/pendaftaran/{id}/prestasi-seleksi/list/all', [PendaftaranController::class, 'listSeleksiPrestasiAll'])->name('pendaftaran.prestasi-all.list');
        Route::post('/pendaftaran/filter-gelombang', [PendaftaranController::class, 'getGelombang'])->name('filter.gelombang');
        Route::post('/pendaftaran/{id}/update-status-seleksi', [PendaftaranController::class, 'updateStatusSeleksi'])->name('pendaftaran.update-status-seleksi');
        Route::get('/pendaftaran/{id}/update-status-seleksi/show-history', [PendaftaranController::class, 'showStatusHistory'])->name('pendaftaran.update-status-seleksi-history');
        Route::post('/pendaftaran/update-status-semua', [PendaftaranController::class, 'updateStatusSemua'])->name('pendaftaran.updateStatusSemua');
        Route::post('/pendaftaran/{id}/diskualifikasi', [PendaftaranController::class, 'disqualify'])->name('pendaftaran.diskualifikasi');
        Route::get('/pendaftaran/{id}/diskualifikasi/show-history', [PendaftaranController::class, 'showDisqualify'])->name('pendaftaran.diskualifikasi-history');
        Route::post('/pendaftaran/{id}/batal-diskualifikasi', [PendaftaranController::class, 'cancelDisqualification'])->name('pendaftaran.batal-diskualifikasi');
        Route::post('/pendaftaran/{id}/update-status-nilai-akademik', [PendaftaranController::class, 'updateStatusSeleksiAkademik'])->name('pendaftaran.update-status-nilai-akademik');
        Route::post('/pendaftaran/{id}/update-status-nilai-prestasi', [PendaftaranController::class, 'updateStatusSeleksiPrestasi'])->name('pendaftaran.update-status-nilai-prestasi');
        Route::get('/pendaftaran/{id}/show-revisi-nilai-akademik', [PendaftaranController::class, 'revisiNilaiAkademik'])->name('pendaftaran.show-revisi-nilai-akademik');
        Route::post('/pendaftaran/{id}/update-revisi-nilai-akademik', [PendaftaranController::class, 'updateNilaiAkademik'])->name('pendaftaran.update-revisi-nilai-akademik');

        //pengumuman
        Route::resource('pengumuman', PengumumanController::class);
        Route::post('/pengumuman/list', [PengumumanController::class, 'list'])->name('pengumuman.list');
    });

    Route::prefix('user-management')->group(function () {
        Route::resource('user', UserController::class);
        Route::post('/user/list', [UserController::class, 'list'])->name('user.list');
    });
    Route::prefix('category-management')->group(function () {
        Route::resource('category', CategoryController::class);
    });

    Route::prefix('menu-management')->group(function () {
        Route::resource('menu-group', MenuGroupController::class);
        Route::post('/menu-group/list', [MenuGroupController::class, 'list'])->name('menu-group.list');

        Route::resource('menu-item', MenuItemController::class);
        Route::post('/menu-item/list', [MenuItemController::class, 'list'])->name('menu-item.list');
    });

    Route::group(['prefix' => 'role-and-permission'], function () {
        //role
        Route::resource('role', RoleController::class);
        Route::post('/role/list', [RoleController::class, 'list'])->name('role.list');

        //permission
        Route::resource('permission', PermissionController::class);
        Route::post('/permission/list', [PermissionController::class, 'list'])->name('permission.list');

        //assign permission
        Route::get('assign', [AssignPermissionController::class, 'index'])->name('assign.index');
        Route::get('assign/create', [AssignPermissionController::class, 'create'])->name('assign.create');
        Route::get('assign/{role}/edit', [AssignPermissionController::class, 'edit'])->name('assign.edit');
        Route::put('assign/{role}', [AssignPermissionController::class, 'update'])->name('assign.update');
        Route::post('assign', [AssignPermissionController::class, 'store'])->name('assign.store');
        Route::post('/assign/list', [AssignPermissionController::class, 'list'])->name('assign.list');

        //assign user to role
        Route::get('assign-user', [AssignUserToRoleController::class, 'index'])->name('assign.user.index');
        Route::get('assign-user/create', [AssignUserToRoleController::class, 'create'])->name('assign.user.create');
        Route::post('assign-user', [AssignUserToRoleController::class, 'store'])->name('assign.user.store');
        Route::get('assign-user/{user}/edit', [AssignUserToRoleController::class, 'edit'])->name('assign.user.edit');
        Route::put('assign-user/{user}', [AssignUserToRoleController::class, 'update'])->name('assign.user.update');
        Route::post('/assign-user/list', [AssignUserToRoleController::class, 'list'])->name('assign.user.list');
    });
});
