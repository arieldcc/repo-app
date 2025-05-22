<?php

use App\Models\PermissionRoleModel;
use Illuminate\Support\Facades\Auth;

// Fungsi untuk mengambil semua permission untuk user yang terautentikasi
if (! function_exists('getUserPermissions')) {
    function getUserPermissions()
    {
        if (Auth::user() != null) {
            return [
                'permissionUser' => PermissionRoleModel::getPermission('User', Auth::user()->role_id),
                'permissionDashboard' => PermissionRoleModel::getPermission('Dashboard', Auth::user()->role_id),
                'permissionRole' => PermissionRoleModel::getPermission('Role', Auth::user()->role_id),
                'permissionMahasiswa' => PermissionRoleModel::getPermission('Mahasiswa', Auth::user()->role_id),
                'permissionDosen' => PermissionRoleModel::getPermission('Dosen', Auth::user()->role_id),

                // panel menu konfigurasi
                'permissionKonfigurasi' => PermissionRoleModel::getPermission('Konfigurasi', Auth::user()->role_id),
                'permissionConfRepo' => PermissionRoleModel::getPermission('ConfRepo', Auth::user()->role_id),
                'permissionConfRepoFrontendSettings' => PermissionRoleModel::getPermission('ConfRepoFrontendSettings', Auth::user()->role_id),
                'permissionConfRepoSliders' => PermissionRoleModel::getPermission('ConfRepoSliders', Auth::user()->role_id),
                'permissionConfRepoCustomPages' => PermissionRoleModel::getPermission('ConfRepoCustomPages', Auth::user()->role_id),

                'permissionSetting' => PermissionRoleModel::getPermission('Setting', Auth::user()->role_id),

                // pannel menu
                'permissionDataMaster' => PermissionRoleModel::getPermission('DataMaster', Auth::user()->role_id),

                // Dokumen
                'permissionDocument' => PermissionRoleModel::getPermission('Dokumen', Auth::user()->role_id),
                'permissionDocSkripsi' => PermissionRoleModel::getPermission('DocSkripsi', Auth::user()->role_id),
                'permissionDocTesis' => PermissionRoleModel::getPermission('DocTesis', Auth::user()->role_id),
                'permissionDocPenelitian' => PermissionRoleModel::getPermission('DocPenelitian', Auth::user()->role_id),
                'permissionDocPengabdian' => PermissionRoleModel::getPermission('DocPengabdian', Auth::user()->role_id),
                'permissionDocLaporan' => PermissionRoleModel::getPermission('DocLaporan', Auth::user()->role_id),
                'permissionDocBukuAjar' => PermissionRoleModel::getPermission('DocBukuAjar', Auth::user()->role_id),

                'permissionDataMasterFakultas' => PermissionRoleModel::getPermission('Fakultas', Auth::user()->role_id),
            ];
        }

        return [];
    }
}
