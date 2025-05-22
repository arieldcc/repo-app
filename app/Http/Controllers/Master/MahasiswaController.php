<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\MahasiswaModel;
use App\Models\PermissionRoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaController extends Controller
{
    public function list(Request $request){
        $permissions = getUserPermissions();
        if(empty($permissions['permissionMahasiswa'])){
            abort(404);
        }

        if ($request->ajax()) {
            // Query Data dengan Join
            $query = MahasiswaModel::getRecordList();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($permissions) {
                    $buttons = '';
                    if (!empty(PermissionRoleModel::getPermission('EditMahasiswa', Auth::user()->role_id))) {
                        $buttons .= '<a href="' . url('master/mahasiswa/edit/' . $row->id_mahasiswa) . '" class="btn btn-sm btn-warning">Edit</a> ';
                    }
                    if (!empty(PermissionRoleModel::getPermission('DeleteMahasiswa', Auth::user()->role_id))) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id_mahasiswa . '">Delete</button>';
                    }
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data['permissionAdd'] = PermissionRoleModel::getPermission('AddMahasiswa', Auth::user()->role_id);
        $data['permissionEdit'] = PermissionRoleModel::getPermission('EditMahasiswa', Auth::user()->role_id);
        $data['permissionDelete'] = PermissionRoleModel::getPermission('DeleteMahasiswa', Auth::user()->role_id);
        $data['permissionDetail'] = PermissionRoleModel::getPermission('DetailMahasiswa', Auth::user()->role_id);

        return view('Master.Mahasiswa.list', $data);
    }

    public function cariMahasiswa(Request $request, $jenjang){
        $q = $request->get('q');

        if (strlen($q) < 3) {
            return response()->json([]);
        }

        $mahasiswa = MahasiswaModel::getRecordList()
            ->where('rev_prodi.nama_jenjang_pendidikan', '=', $jenjang)
            ->where(function($query) use ($q) {
                $query->where('m_mahasiswa.nama_mahasiswa', 'LIKE', "%$q%")
                    ->orWhere('m_riwayat_pendidikan_mhs.nim', 'LIKE', "%$q%");
            })
            ->limit(20)
            ->get(['nim_mahasiswa', 'm_mahasiswa.nama_mahasiswa']);

        return response()->json($mahasiswa);
    }
}
