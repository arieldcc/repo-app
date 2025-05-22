<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\DosenModel;
use App\Models\Master\FakultasModel;
use App\Models\Master\JenisJabatanModel;
use App\Models\Master\PejabatFakultasModel;
use App\Models\Master\ProdiModel;
use App\Models\PermissionRoleModel;
use App\Models\Reverensi\JenjangPendidikanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FakultasController extends Controller
{
    public function list(){
        $permissions = getUserPermissions();
        if(empty($permissions['permissionDataMasterFakultas'])){
            abort(404);
        }

        $data['permissionAdd'] = PermissionRoleModel::getPermission('AddFakultas', Auth::user()->role_id);
        $data['permissionEdit'] = PermissionRoleModel::getPermission('EditFakultas', Auth::user()->role_id);
        $data['permissionDelete'] = PermissionRoleModel::getPermission('DeleteFakultas', Auth::user()->role_id);
        $data['permissionDetail'] = PermissionRoleModel::getPermission('DetailFakultas', Auth::user()->role_id);

        $data['getRecord'] = FakultasModel::getRecord();

        return view('Master.fakultas.list', $data);
    }

    public function add(){
        $data['getJenjangPendidikan'] = JenjangPendidikanModel::getRecord();

        return view('Master.fakultas.add', $data);
    }

    public function insert(Request $request){
        $request->validate([
            'logo_fakultas' => 'nullable|file|mimes:jpg,jpeg,png|max:600',
        ]);

        if ($request->hasFile('logo_fakultas')) {
            $file = $request->file('logo_fakultas');
            $filePath = $file->store('uploads/logo_fakultas', 'public');
            // dd($filePath);
        }

        $fakultas = new FakultasModel;
        $fakultas->nama_fakultas = trim($request->nama_fakultas);
        $fakultas->status = trim($request->status);
        $fakultas->jenjang_didik_id = $request->jenjang_didik_id;
        $fakultas->singkatan = trim($request->singkatan);
        $fakultas->logo = $filePath ?? null;
        $fakultas->save();

        return redirect('master/fakultas')->with('success', 'Data berhasil di simpan.');
    }

    public function edit($id){
        $data['getRecord'] = FakultasModel::getSingle($id);
        $data['getJenjangPendidikan'] = JenjangPendidikanModel::getRecord();

        return view('master.fakultas.edit', $data);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'logo_fakultas' => 'nullable|file|mimes:jpg,jpeg,png|max:600',
        ]);

        $fakultas = FakultasModel::getSingle($id);

        // Default: gunakan logo lama
        $logoPath = $fakultas->logo;

        if ($request->hasFile('logo_fakultas')) {
            // Hapus file lama jika ada dan eksis di disk 'public'
            if (!empty($fakultas->logo) && Storage::disk('public')->exists($fakultas->logo)) {
                Storage::disk('public')->delete($fakultas->logo);
            }

            // Simpan file baru dan catat path-nya
            $file = $request->file('logo_fakultas');
            $logoPath = $file->store('uploads/logo_fakultas', 'public');
        }

        // Update data
        $fakultas->nama_fakultas = trim($request->nama_fakultas);
        $fakultas->status = trim($request->status);
        $fakultas->jenjang_didik_id = $request->jenjang_didik_id;
        $fakultas->singkatan = trim($request->singkatan);
        $fakultas->logo = $logoPath;
        $fakultas->save();

        return redirect('master/fakultas')->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id){
        $fakultas = FakultasModel::getSingle($id);
        // Hapus file terkait jika ada
        if ($fakultas->logo && Storage::disk('public')->exists($fakultas->logo)) {
            Storage::disk('public')->delete($fakultas->logo);
        }

        $fakultas->delete();

        return redirect('master/fakultas')->with('success', 'Data berhasil di Hapus.');
    }

    public function detail($id){
        $data['getRecord'] = FakultasModel::getSingle($id);
        $data['getPejabatFakultas'] = PejabatFakultasModel::getRecord($id)->get();
        $data['getPejabatPerFakultas'] = PejabatFakultasModel::getDetailFakultas($id, 'A');
        $data['getProdiFakultas'] = ProdiModel::getProdiFakultas($id);


        $data['permissionAdd'] = PermissionRoleModel::getPermission('AddPejabatFakultas', Auth::user()->role_id);
        $data['permissionEdit'] = PermissionRoleModel::getPermission('EditPejabatFakultas', Auth::user()->role_id);
        $data['permissionDelete'] = PermissionRoleModel::getPermission('DeletePejabatFakultas', Auth::user()->role_id);
        $data['permissionDetail'] = PermissionRoleModel::getPermission('DetailPejabatFakultas', Auth::user()->role_id);
        $data['permissionAddProdi'] = PermissionRoleModel::getPermission('AddProdiFakultas', Auth::user()->role_id);

        return view('master.fakultas.detail', $data);
    }

    public function addProdi($id){
        $data['getRecord'] = FakultasModel::getSingle($id);
        // $data['getFakultas'] = FakultasModel::getSingle($id);
        $data['programStudi'] = ProdiModel::getRecord($id);
        $data['getProdiFakultas'] = ProdiModel::getProdiperFakultas($id);
        // dd($data['getProdiFakultas']);

        return view('Master.fakultas.addprodi', $data);
    }

    public function updateProdi(Request $request, $id){
        // Validasi input
        $request->validate([
            'selected_prodi' => 'required|array',  // Pastikan data yang diterima adalah array
        ]);

        // Mengambil fakultas berdasarkan ID
        $fakultas = FakultasModel::getSingle($id);
        if (!$fakultas) {
            return response()->json(['message' => 'Fakultas tidak ditemukan'], 404);
        }

        // Menangani update program studi yang dipilih
        $selectedProdi = $request->selected_prodi; // Program studi yang masih dipilih
        $removedProdi = $request->removed_prodi; // Program studi yang dikeluarkan

        // Update fakultas_id untuk program studi yang masih dipilih
        ProdiModel::wherein('id_prodi', $selectedProdi)
                ->update(['fakultas_id' => $id]);  // Set fakultas_id dengan ID fakultas

        // Set fakultas_id menjadi null untuk program studi yang dikeluarkan
        ProdiModel::where('id_prodi', $removedProdi)
                ->where('fakultas_id', $id)
                ->update(['fakultas_id' => '']);  // Set fakultas_id menjadi null (kosong)

        // Mengembalikan respons sukses
        return response()->json(['message' => 'Program Studi berhasil diperbarui']);
    }

    // Pejabat Fakultas
    public function addPejabat($id){
        $data['getFakultas'] = FakultasModel::getSingle($id);
        $data['getJenisJabatan'] = JenisJabatanModel::getRecordPerSMS_ID('4');
        $data['getDosen'] = DosenModel::getRecord();

        return view('master.fakultas.addpejabat', $data);
    }

    public function insertPejabat($id, Request $request){
        $request->validate([
            'file_sk' => 'file|mimes:pdf,jpg,jpeg,png|max:600',
        ]);

        if ($request->hasFile('file_sk')) {
            $file = $request->file('file_sk');
            $filePath = $file->store('uploads/file_sk', 'public');
        }

        $pejabatFakultas = new PejabatFakultasModel;
        $pejabatFakultas->jenis_jabatan_id = trim($request->jenis_jabatan_id);
        $pejabatFakultas->fakultas_id = $id;
        $pejabatFakultas->dosen_id = $request->dosen_id;
        $pejabatFakultas->tanggal_mulai = trim($request->tanggal_mulai);
        $pejabatFakultas->tanggal_selesai = trim($request->tanggal_selesai);
        $pejabatFakultas->no_sk = trim($request->no_sk);
        $pejabatFakultas->status = trim($request->status);
        $pejabatFakultas->file_sk = $filePath ?? null;
        $pejabatFakultas->save();

        return redirect('master/fakultas/detail/'.$id.'#pimpinan-fakultas')->with('success', 'Data berhasil di simpan.');
    }
}
