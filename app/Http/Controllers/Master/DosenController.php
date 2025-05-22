<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\DosenModel;
use App\Models\PermissionRoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DosenController extends Controller
{
    public function list(Request $request){
        $permissions = getUserPermissions();
        if(empty($permissions['permissionMahasiswa'])){
            abort(404);
        }

        // Jika request adalah AJAX dari DataTables
        if ($request->ajax()) {
            $query = DosenModel::getRecord(); // ambil data dengan custom select

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($permissions) {
                    $buttons = '';

                    if (!empty(PermissionRoleModel::getPermission('EditDosen', Auth::user()->role_id))) {
                        $buttons .= '<a href="' . url('master/dosen/edit/' . $row->id_dosen) . '" class="btn btn-sm btn-warning">Edit</a> ';
                    }

                    if (!empty(PermissionRoleModel::getPermission('DeleteDosen', Auth::user()->role_id))) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id_dosen . '">Delete</button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['action']) // agar HTML pada kolom action tidak di-escape
                ->make(true);
        }

        // Jika request bukan AJAX, tampilkan halaman Blade biasa
        $data['permissionAdd'] = PermissionRoleModel::getPermission('AddDosen', Auth::user()->role_id);
        $data['permissionEdit'] = PermissionRoleModel::getPermission('EditDosen', Auth::user()->role_id);
        $data['permissionDelete'] = PermissionRoleModel::getPermission('DeleteDosen', Auth::user()->role_id);
        $data['permissionDetail'] = PermissionRoleModel::getPermission('DetailDosen', Auth::user()->role_id);

        return view('Master.Dosen.index', $data);
    }

    public function cariDosen(Request $request){
        $q = $request->get('q');

        if (strlen($q) < 3) {
            return response()->json([]);
        }

        $results = DosenModel::select('nama_dosen', 'nidn')
            ->where(function($query) use ($q) {
                $query->where('nama_dosen', 'LIKE', '%' . $q . '%')
                    ->orWhere('nidn', 'LIKE', '%' . $q . '%');
            })
            ->limit(20)
            ->get();

        return response()->json($results);
    }
}
