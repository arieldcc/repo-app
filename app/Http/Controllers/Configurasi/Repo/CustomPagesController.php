<?php

namespace App\Http\Controllers\Configurasi\Repo;

use App\Http\Controllers\Controller;
use App\Models\Konfigurasi\Repo\CustomPagesModel;
use App\Models\PermissionRoleModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class CustomPagesController extends Controller
{
    public function listCustomPage(Request $request){
        $permissions = getUserPermissions();
        if (empty($permissions['permissionConfRepoCustomPages'])) {
            abort(404);
        }

        if ($request->ajax()) {
            $query = CustomPagesModel::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status === 'Y'
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-secondary">Nonaktif</span>';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $buttons = '';
                    if (!empty(PermissionRoleModel::getPermission('EditConfRepoCustomPages', Auth::user()->role_id))) {
                        $buttons .= '<a href="' . url('conf-repo/custom-pages/edit/' . $row->id) . '" class="btn btn-sm btn-warning mt-1"><i class="fas fa-edit"></i> Edit</a> ';
                    }

                    if (!empty(PermissionRoleModel::getPermission('DeleteConfRepoCustomPages', Auth::user()->role_id))) {
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

        $data['permissionAdd'] = PermissionRoleModel::getPermission('AddConfRepoCustomPages', Auth::user()->role_id);

        return view('Konfigurasi.Repository.customPageIndex', $data);
    }

    public function addCustomPage(){
        return view('Konfigurasi.Repository.customPageAdd');
    }

    public function insertCustomPage(Request $request){
        $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'nullable|string',
            'status'    => 'required|in:Y,N',
        ]);

        try {
            // Auto generate slug dari title
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($request->title)));
            $slug = trim($slug, '-');

            // Cek jika slug sudah ada
            $existing = CustomPagesModel::where('page_key', $slug)->first();
            if ($existing) {
                return back()->withErrors(['title' => 'Halaman dengan judul serupa sudah ada.'])->withInput();
            }

            // Simpan data
            $page = new CustomPagesModel();
            $page->page_key = $slug;
            $page->title    = $request->title;
            $page->content  = $request->content;
            $page->status   = $request->status;
            $page->save();

            return redirect('conf-repo/custom-pages')->with('success', 'Halaman berhasil ditambahkan.');
        } catch (Exception $e) {
            report($e);
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.'])->withInput();
        }
    }

    public function updateStatus(Request $request){
        $setting = CustomPagesModel::findOrFail($request->id);

        if ($request->status === 'true') {
            // Nonaktifkan semua data lain terlebih dahulu
            CustomPagesModel::where('status', 'Y')->update(['status' => 'N']);
            $setting->status = 'Y';
        } else {
            $setting->status = 'N';
        }

        $setting->save();

        return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui.']);
    }

    public function editCustomPage($id){
        $data['getRecord'] = CustomPagesModel::getSingle($id);

        return view('Konfigurasi.Repository.customPageEdit', $data);
    }

    public function updateCustomPage(Request $request, $id){
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
            'status'  => 'required|in:Y,N',
        ]);

        try {
            $customPage = CustomPagesModel::findOrFail($id);

            // Jika title berubah, update page_key
            if ($customPage->title !== $request->title) {
                $customPage->page_key = Str::slug($request->title, '-');
            }

            $customPage->title   = $request->title;
            $customPage->content = $request->content;
            $customPage->status  = $request->status;
            $customPage->updated_at = now();

            $customPage->save();

            return redirect('conf-repo/custom-pages')->with('success', 'Halaman berhasil diperbarui.');
        } catch (Exception $e) {
            report($e);
            return back()->withErrors('Terjadi kesalahan saat memperbarui halaman.')->withInput();
        }
    }

    public function deleteCustomPage($id){
        // Ambil data Repository berdasarkan ID
        $repo = CustomPagesModel::findOrFail($id);

        // Hapus data dari database
        $repo->delete();

        // Redirect kembali dengan pesan sukses
        return redirect('conf-repo/custom-pages')->with('success', 'Data Frontend Setting berhasil dihapus.');
    }


}
