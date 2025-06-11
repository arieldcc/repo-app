<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Configurasi\Repo\CustomPagesController;
use App\Http\Controllers\Configurasi\Repo\FrontendSettingController;
use App\Http\Controllers\Configurasi\Repo\SliderController;
use App\Http\Controllers\ControllerDashboard;
use App\Http\Controllers\Document\DocumentController;
use App\Http\Controllers\FrontEnd\frontRepoController;
use App\Http\Controllers\Master\DosenController;
use App\Http\Controllers\Master\FakultasController;
use App\Http\Controllers\Master\MahasiswaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\Master\FakultasModel;
use Illuminate\Support\Facades\Route;


Route::get('dashboard/v1', function () {
    return view('welcome');
});

Route::get('dashboard/v2', function () {
    return view('welcome');
});

Route::controller(frontRepoController::class)->group(function(){
    Route::get('/', 'repoIndex');
    Route::get('/skripsi', 'repoSkripsi');
    Route::get('/doc/detail/{type}/{id}', 'detailTA');
    Route::get('/doc/download/{type}/{id}', 'downloadTA');

    Route::get('/tesis', 'repoTesis');
    Route::get('/penelitian', 'repoPenelitian');
    Route::get('/pengabdian', 'repoPengabdian');

    Route::get('/penelitian/detail/{type}/{id}', 'docDetail');

    Route::get('/api/autocomplete-skripsi', 'autocompleteSkripsi');
});

Route::get('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'auth_login']);
Route::get('logout', [AuthController::class, 'logout']);

Route::group(['middleware' => 'useradmin'], function(){
    Route::get('panel/dashboard', [ControllerDashboard::class, 'dashboard']);

    Route::controller(DosenController::class)->group(function(){
        Route::get('master/dosen', 'list');
        Route::get('master/dosen/add', 'add');
        Route::post('master/dosen/add', 'insert');
        Route::get('master/dosen/edit/{id}', 'edit');
        Route::post('master/dosen/edit/{id}', 'update');
        Route::get('master/dosen/delete/{id}', 'delete');

        Route::get('/api/cari-dosen','cariDosen');
    });

    Route::controller(MahasiswaController::class)->group(function(){
        Route::get('master/mahasiswa', 'list');
        Route::get('master/mahasiswa/add', 'add');
        Route::post('master/mahasiswa/add', 'insert');
        Route::get('master/mahasiswa/edit/{id}', 'edit');
        Route::post('master/mahasiswa/edit/{id}', 'update');
        Route::get('master/mahasiswa/delete/{id}', 'delete');

        Route::get('/api/cari-mahasiswa/{jenjang}','cariMahasiswa');
    });

    Route::controller(FakultasController::class)->group(function(){
        Route::get('master/fakultas', 'list');
        Route::get('master/fakultas/add', 'add');
        Route::post('master/fakultas/add', 'insert');
        Route::get('master/fakultas/edit/{id}', 'edit');
        Route::post('master/fakultas/edit/{id}', 'update');
        Route::get('master/fakultas/delete/{id}', 'delete');

        Route::get('master/fakultas/detail/{id}', 'detail');

        Route::get('master/fakultas/addpejabat/{id}', 'addPejabat');
        Route::post('master/fakultas/addpejabat/{id}', 'insertPejabat');
        Route::get('master/fakultas/editpejabat/{id}', 'editPejabat');
        Route::post('master/fakultas/editpejabat/{id}', 'updatePejabat');
        Route::get('master/fakultas/detail/deletepejabat/{id}', 'deletePejabat');

        Route::get('master/fakultas/addpeprodi/{id}', 'addProdi');
        Route::post('master/fakultas/updateprodifakultas/{id}', 'updateProdi');
    });

    Route::controller(RoleController::class)->group(function(){
        Route::get('panel/role', 'list');
        Route::get('panel/role/add', 'add');
        Route::post('panel/role/add', 'insert');
        Route::get('panel/role/edit/{id}', 'edit');
        Route::post('panel/role/edit/{id}', 'update');
        Route::get('panel/role/delete/{id}', 'delete');
    });

    Route::controller(UserController::class)->group(function(){
        Route::get('panel/user', 'list');
        Route::get('panel/user/add', 'add');
        Route::post('panel/user/add', 'insert');
        Route::get('panel/user/edit/{id}', 'edit');
        Route::post('panel/user/edit/{id}', 'update');
        Route::get('panel/user/delete/{id}', 'delete');
    });

    // Documents
    Route::controller(DocumentController::class)->group(function(){
        Route::get('dashboard/repodashboard', 'dashboardDoc');
        Route::get('dashboard/repodashboard/data-filter', 'dashboardData');
        Route::get('doc/dashboard/pie-prodi', 'dashboardProdiPie')->name('dashboard.pie-prodi');
        Route::get('doc/dashboard/prodi-by-type', 'getProdiByType')->name('dashboard.prodi-by-type');
        Route::get('doc/dashboard/chart-per-prodi', 'getChartByProdi')->name('dashboard.chart-by-prodi');
        
        Route::get('doc/skripsi', 'skripsiList');
        Route::get('doc/skripsi/add', 'skripsiAdd');
        Route::post('doc/skripsi/add', 'skripsiInsert');
        Route::get('doc/skripsi/edit/{id}', 'skripsiEdit');
        Route::post('doc/skripsi/edit/{id}', 'skripsiUpdate');
        Route::get('doc/skripsi/delete/{id}', 'skripsiDelete');

        Route::get('/api/cek-nim/{type}/{nim}', 'cekNim');

        Route::get('doc/skripsi/detail/{id}', 'skripsiDetail');

        Route::get('doc/tesis', 'tesisList');
        Route::get('doc/tesis/add', 'tesisAdd');
        Route::post('doc/tesis/add', 'tesisInsert');
        Route::get('doc/tesis/edit/{id}', 'tesisEdit');
        Route::post('doc/tesis/edit/{id}', 'tesisUpdate');
        Route::get('doc/tesis/delete/{id}', 'tesisDelete');
        Route::get('doc/tesis/detail/{id}', 'tesisDetail');

        Route::get('doc/penelitian', 'penelitianList');
        Route::get('doc/penelitian/add', 'penelitianAdd');
        Route::post('doc/penelitian/add', 'penelitianInsert');
        Route::get('doc/penelitian/edit/{id}', 'penelitianEdit');
        Route::post('doc/penelitian/edit/{id}', 'penelitianUpdate');
        Route::get('doc/penelitian/delete/{id}', 'penelitianDelete');
        Route::get('doc/penelitian/detail/{id}', 'penelitianDetail');
        Route::get('api/check-penelitian', 'checkPenelitian');

        Route::get('api/cari-penulis', 'cariPenulis');

        Route::get('doc/pengabdian', 'pengabdianList');
        Route::get('doc/pengabdian/add', 'pengabdianAdd');
        Route::post('doc/pengabdian/add', 'pengabdianInsert');
        Route::get('doc/pengabdian/edit/{id}', 'pengabdianEdit');
        Route::post('doc/pengabdian/edit/{id}', 'pengabdianUpdate');
        Route::get('doc/pengabdian/delete/{id}', 'pengabdianDelete');
        Route::get('doc/pengabdian/detail/{id}', 'pengabdianDetail');

    });

    // Konfigurasi
    Route::controller(FrontendSettingController::class)->group(function(){
        Route::get('conf-repo/frontend-setting', 'listFrontendSetting');
        Route::get('conf-repo/frontend-setting/add', 'addFrontendSetting');
        Route::post('conf-repo/frontend-setting/add', 'insertFrontendSetting');
        Route::get('conf-repo/frontend-setting/edit/{id}', 'editFrontendSetting');
        Route::post('conf-repo/frontend-setting/edit/{id}', 'updateFrontendSetting');
        Route::get('conf-repo/frontend-setting/delete/{id}', 'deleteFrontendSetting');

        Route::post('conf-repo/frontend-setting/update-status','updateStatus');
    });

    Route::controller(SliderController::class)->group(function(){
        Route::get('conf-repo/sliderbar', 'listSliderbar');
        Route::get('conf-repo/sliderbar/add', 'addSliderbar');
        Route::post('conf-repo/sliderbar/add', 'insertSliderbar');
        Route::get('conf-repo/sliderbar/edit/{id}', 'editSliderbar');
        Route::post('conf-repo/sliderbar/edit/{id}', 'updateSliderbar');
        Route::get('conf-repo/sliderbar/delete/{id}', 'deleteSliderbar');

        Route::post('conf-repo/sliderbar/update-status', 'updateStatus');
    });

    Route::controller(CustomPagesController::class)->group(function(){
        Route::get('conf-repo/custom-pages', 'listCustomPage');
        Route::get('conf-repo/custom-pages/add', 'addCustomPage');
        Route::post('conf-repo/custom-pages/add', 'insertCustomPage');
        Route::get('conf-repo/custom-pages/edit/{id}', 'editCustomPage');
        Route::post('conf-repo/custom-pages/edit/{id}', 'updateCustomPage');
        Route::get('conf-repo/custom-pages/delete/{id}', 'deleteCustomPage');

        Route::post('conf-repo/custom-pages/update-status', 'updateStatus');
    });

});
