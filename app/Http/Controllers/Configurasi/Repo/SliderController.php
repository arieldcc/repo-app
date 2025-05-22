<?php

namespace App\Http\Controllers\Configurasi\Repo;

use App\Http\Controllers\Controller;
use App\Models\Konfigurasi\Repo\SliderModel;
use App\Models\PermissionRoleModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SliderController extends Controller
{
    public function listSliderbar(Request $request){
        $permissions = getUserPermissions();
        if (empty($permissions['permissionConfRepoSliders'])) {
            abort(404);
        }

        if ($request->ajax()) {
            $query = SliderModel::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status === 'Y'
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-secondary">Nonaktif</span>';
                })
                ->addColumn('image', function ($row) {
                    return '<img src="' . url('/public/storage/uploads/reposlider/' . $row->image_path) . '" alt="slider" class="slider-thumbnail" data-src="' . url('/public/storage/uploads/reposlider/' . $row->image_path) . '" style="max-height:60px; cursor:pointer;">';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $buttons = '';
                    if (!empty(PermissionRoleModel::getPermission('EditConfRepoSliders', Auth::user()->role_id))) {
                        $buttons .= '<a href="' . url('conf-repo/sliderbar/edit/' . $row->id) . '" class="btn btn-sm btn-warning mt-1"><i class="fas fa-edit"></i> Edit</a> ';
                    }

                    if (!empty(PermissionRoleModel::getPermission('DeleteConfRepoSliders', Auth::user()->role_id))) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-btn mt-1" data-id="' . $row->id . '"><i class="fas fa-trash-alt"></i> Delete</button>';
                    }
                    return $buttons;
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status == 'Y' ? 'checked' : '';
                    return '
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-status" type="checkbox" data-id="'.$row->id.'" '.$checked.'>
                        </div>';
                })

                ->rawColumns(['status', 'image', 'action'])
                ->make(true);
        }

        $data['permissionAdd'] = PermissionRoleModel::getPermission('AddConfRepoSliders', Auth::user()->role_id);
        $data['permissionEdit'] = PermissionRoleModel::getPermission('EditConfRepoSliders', Auth::user()->role_id);

        return view('Konfigurasi.Repository.sliderIndex', $data);
    }

    public function addSliderbar(){
        return view('Konfigurasi.Repository.sliderAdd');
    }

    public function insertSliderbar(Request $request){
        $request->validate([
            'image_path' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'title'      => 'required|string|max:255',
            'subtitle'   => 'nullable|string|max:255',
            'order'      => 'required|integer',
            'status'     => 'required|in:Y,N',
        ]);

        try {
            // Nonaktifkan slider lain jika yang ini akan diaktifkan
            // if ($request->status === 'Y') {
            //     SliderModel::where('status', 'Y')->update(['status' => 'N']);
            // }

            // Proses upload gambar
            $filePath = null;
            if ($request->hasFile('image_path')) {
                $file = $request->file('image_path');
                $path = $file->store('uploads/reposlider', 'public');
                $filePath = basename($path);
            }

            // Simpan data ke database
            $slider = new SliderModel();
            $slider->image_path = $filePath;
            $slider->title      = $request->title;
            $slider->subtitle   = $request->subtitle;
            $slider->order      = $request->order;
            $slider->status     = $request->status;
            $slider->save();

            return redirect('conf-repo/sliderbar')->with('success', 'Slider berhasil ditambahkan.');
        } catch (Exception $e) {
            report($e);
            return back()->withErrors('Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    public function editSliderbar($id){
        $data['getRecord'] = SliderModel::getSingle($id);

        return view('Konfigurasi.Repository.sliderbarEdit', $data);
    }

    public function updateSliderbar(Request $request, $id){
        $request->validate([
            'title'      => 'required|string|max:255',
            'subtitle'   => 'nullable|string|max:255',
            'order'      => 'required|integer|min:1',
            'status'     => 'required|in:Y,N',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // 2MB max
        ]);

        try {
            // Ambil data slider yang akan diupdate
            $slider = SliderModel::findOrFail($id);

            // Jika ada file gambar baru
            if ($request->hasFile('image_path')) {
                $file = $request->file('image_path');
                $storedPath = $file->store('uploads/reposlider', 'public');
                $fileName = basename($storedPath);

                // Hapus file lama jika ada
                if ($slider->image_path && Storage::disk('public')->exists('uploads/reposlider/' . $slider->image_path)) {
                    Storage::disk('public')->delete('uploads/reposlider/' . $slider->image_path);
                }

                $slider->image_path = $fileName;
            }

            // Update data lainnya
            $slider->title    = $request->title;
            $slider->subtitle = $request->subtitle;
            $slider->order    = $request->order;
            $slider->status   = $request->status;
            $slider->save();

            return redirect('conf-repo/sliderbar')->with('success', 'Slider berhasil diperbarui.');
        } catch (Exception $e) {
            Log::error('Update slider gagal: ' . $e->getMessage());
            return back()->withErrors('Terjadi kesalahan saat memperbarui slider.')->withInput();
        }
    }

    public function deleteSliderbar($id){
        // Ambil data slidebar berdasarkan ID
        $slider = SliderModel::findOrFail($id);

        // Hapus file dari storage jika ada
        if (!empty($slider->image_path) && Storage::disk('public')->exists('uploads/reposlider/' . $slider->image_path)) {
            Storage::disk('public')->delete('uploads/reposlider/' . $slider->image_path);
        }

        // Hapus data dari database
        $slider->delete();

        // Redirect kembali dengan pesan sukses
        return redirect('conf-repo/sliderbar')->with('success', 'Data slider berhasil dihapus.');
    }

    public function updateStatus(Request $request){
        $slider = SliderModel::findOrFail($request->id);
        $slider->status = $request->status === 'true' ? 'Y' : 'N';
        $slider->save();

        return response()->json(['success' => true, 'message' => 'Status slider berhasil diperbarui.']);
    }

}
