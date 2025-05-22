<?php

namespace App\Http\Controllers\Configurasi\Repo;

use App\Http\Controllers\Controller;
use App\Models\Konfigurasi\Repo\FrontendSettingModel;
use App\Models\PermissionRoleModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class FrontendSettingController extends Controller
{
    public function listFrontendSetting(Request $request){
        $permissions = getUserPermissions();
        if (empty($permissions['permissionConfRepoFrontendSettings'])) {
            abort(404);
        }

        if ($request->ajax()) {
            $query = FrontendSettingModel::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status === 'Y'
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-secondary">Nonaktif</span>';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $buttons = '';
                    if (!empty(PermissionRoleModel::getPermission('EditConfRepoFrontendSettings', Auth::user()->role_id))) {
                        $buttons .= '<a href="' . url('conf-repo/frontend-setting/edit/' . $row->id) . '" class="btn btn-sm btn-warning mt-1"><i class="fas fa-edit"></i> Edit</a> ';
                    }

                    if (!empty(PermissionRoleModel::getPermission('DeleteConfRepoFrontendSettings', Auth::user()->role_id))) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-btn mt-1" data-id="' . $row->id . '"><i class="fas fa-trash-alt"></i> Delete</button>';
                    }
                    return $buttons;
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status === 'Y' ? 'checked' : '';
                    return '
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-status" type="checkbox" data-id="'.$row->id.'" '.$checked.'>
                        </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $data['permissionAdd'] = PermissionRoleModel::getPermission('AddConfRepoFrontendSettings', Auth::user()->role_id);
        $data['permissionEdit'] = PermissionRoleModel::getPermission('EditConfRepoFrontendSettings', Auth::user()->role_id);

        return view('Konfigurasi.Repository.frontendSettingIndex', $data);
    }

    public function addFrontendSetting(){
        return view('Konfigurasi.Repository.frontendSettingAdd');
    }

    public function insertFrontendSetting(Request $request){
        $request->validate([
            'site_name'     => 'required|string|max:255',
            'site_tagline'  => 'nullable|string|max:255',
            'version'       => 'required|string|max:255',
            'footer_text'   => 'nullable|string',
            'status'        => 'required|in:Y,N',
        ]);

        try {

            // Jika status yang dipilih adalah 'Y', nonaktifkan semua lainnya
            if ($request->status === 'Y') {
                FrontendSettingModel::where('status', 'Y')->update(['status' => 'N']);
            }

            $setting = new FrontendSettingModel;
            $setting->site_name    = $request->site_name;
            $setting->site_tagline = $request->site_tagline;
            $setting->version      = $request->version;
            $setting->footer_text  = $request->footer_text;
            $setting->status       = $request->status;
            $setting->save();

            return redirect('conf-repo/frontend-setting')->with('success', 'Pengaturan frontend berhasil ditambahkan.');
        } catch (Exception $e) {
            report($e);
            return back()->withErrors('Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    public function editFrontendSetting($id){
        $data['getRecord'] = FrontendSettingModel::getSingle($id);

        return view('Konfigurasi.Repository.frontendSettingEdit', $data);
    }

    public function updateFrontendSetting(Request $request, $id){
        $request->validate([
            'site_name'     => 'required|string|max:255',
            'site_tagline'  => 'nullable|string|max:255',
            'version'       => 'required|string|max:50',
            'footer_text'   => 'nullable|string|max:255',
            'status'        => 'required|string',
        ]);

        // Jika status yang dipilih adalah 'Y', nonaktifkan semua lainnya
        if ($request->status === 'Y') {
            FrontendSettingModel::where('status', 'Y')->update(['status' => 'N']);
        }

        $setting = FrontendSettingModel::findOrFail($id);
        $setting->site_name     = $request->site_name;
        $setting->site_tagline  = $request->site_tagline;
        $setting->version       = $request->version;
        $setting->footer_text   = $request->footer_text;
        $setting->status        = $request->status;
        $setting->save();

        return redirect('conf-repo/frontend-setting')->with('success', 'Data Frontend Setting berhasil diperbarui.');
    }

    public function deleteFrontendSetting($id){
        // Ambil data Repository berdasarkan ID
        $repo = FrontendSettingModel::findOrFail($id);

        // Hapus data dari database
        $repo->delete();

        // Redirect kembali dengan pesan sukses
        return redirect('conf-repo/frontend-setting')->with('success', 'Data Frontend Setting berhasil dihapus.');
    }

    public function updateStatus(Request $request){
        $setting = FrontendSettingModel::findOrFail($request->id);

        if ($request->status === 'true') {
            // Nonaktifkan semua data lain terlebih dahulu
            FrontendSettingModel::where('status', 'Y')->update(['status' => 'N']);
            $setting->status = 'Y';
        } else {
            $setting->status = 'N';
        }

        $setting->save();

        return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui.']);
    }

}
