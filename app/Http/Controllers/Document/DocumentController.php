<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Document\DocumentAuthorsModel;
use App\Models\Document\DocumentModel;
use App\Models\Master\DosenModel;
use App\Models\Master\MahasiswaModel;
use App\Models\PermissionRoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function skripsiList(Request $request){
        $permissions = getUserPermissions();

        // Cek apakah user memiliki akses melihat dokumen skripsi
        if (empty($permissions['permissionDocSkripsi'])) {
            abort(404);
        }

        // Jika request adalah AJAX (untuk DataTables)
        if ($request->ajax()) {
            $data = DocumentModel::getListTugasAkhir()
                            ->where('documents.type', 'skripsi');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($permissions) {
                    $buttons = '';

                    if (!empty(PermissionRoleModel::getPermission('DetailDocSkripsi', Auth::user()->role_id))){
                        $buttons .= '<a href="' . url('doc/skripsi/detail/'.$row->document_id).'" class="btn btn-primary btn-sm btn-detail"><i class="fas fa-eye"></i> Detail</a>';
                    }

                    if (!empty(PermissionRoleModel::getPermission('EditDocSkripsi', Auth::user()->role_id))) {
                        $buttons .= '<a href="' . url('doc/skripsi/edit/' . $row->document_id) . '" class="btn btn-sm btn-warning mt-1"><i class="fas fa-edit"></i> Edit</a> ';
                    }

                    if (!empty(PermissionRoleModel::getPermission('DeleteDocSkripsi', Auth::user()->role_id))) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-btn mt-1" data-id="' . $row->document_id . '"><i class="fas fa-trash-alt"></i> Delete</button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Jika bukan request AJAX, kembalikan halaman view biasa
        $data['permissionAdd'] = PermissionRoleModel::getPermission('AddDocSkripsi', Auth::user()->role_id);
        // $data['permissionEdit'] = PermissionRoleModel::getPermission('EditDocSkripsi', Auth::user()->role_id);
        // $data['permissionDelete'] = PermissionRoleModel::getPermission('DeleteDocSkripsi', Auth::user()->role_id);
        // $data['permissionDetail'] = PermissionRoleModel::getPermission('DetailDocSkripsi', Auth::user()->role_id);

        return view('Document.skripsiIndex', $data);
    }

    public function skripsiDetail($id){
        $data['getRecord'] = DocumentModel::getDetailTugasAkhir($id);

        return view('Document.SkripsiDetail', $data);
    }

    public function skripsiAdd(){
        return view('Document.skripsiAdd');
    }

    public function cekNim($nim){
        $data = DocumentModel::where('penulis', $nim)->where('type', 'skripsi')->first();

        return response()->json([
            'exists' => $data ? true : false,
            'data' => $data
        ]);
    }

    public function skripsiInsert(Request $request){
        $request->validate([
            'penulis'         => 'required|string|max:50',
            'title'           => 'required|string|max:255',
            'abstract'        => 'nullable|string',
            'keywords'        => 'nullable|string',
            'tahun_akademik'  => 'required|string|max:10',
            'file_path'       => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120' // 5MB, valid extensions
        ]);

        // Tentukan status otomatis berdasarkan role
        $user = Auth::user();
        $status = ($user && $user->role_id == '1') ? 'approved' : 'pending';

        // Upload file
        $filePath = null;
        $fileSize = null;
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $storedPath = $file->store('uploads/skripsi', 'public');
            $fileSize = $file->getSize() / 1024 / 1024; // dalam MB

            // Ambil hanya nama filenya (tanpa folder)
            $filePath = basename($storedPath);
            // dd([
            //     'exists' => file_exists(storage_path('app/public/uploads/skripsi')),
            //     'canWrite' => is_writable(storage_path('app/public/uploads/skripsi')),
            //     'storeTest' => $file->store('uploads/skripsi', 'public'),
            // ]);
        }

        // Simpan data ke dalam database
        $skripsi = new DocumentModel;
        $skripsi->document_id     = (string) Str::uuid();
        $skripsi->penulis         = $request->penulis;
        $skripsi->title           = $request->title;
        $skripsi->abstract        = $request->abstract;
        $skripsi->keywords        = $request->keywords;
        $skripsi->tahun_akademik  = $request->tahun_akademik;
        $skripsi->file_path       = $filePath;
        $skripsi->file_size       = $fileSize;
        $skripsi->status          = $status;
        $skripsi->upload_date     = now();
        $skripsi->type            = 'skripsi';
        $skripsi->save();

        return redirect('doc/skripsi')->with('success', 'Skripsi berhasil ditambahkan.');
    }

    public function skripsiEdit($id){
        $data['getRecord'] = DocumentModel::getDetailTugasAkhir($id);

        return view('Document.skripsiEdit', $data);
    }

    public function skripsiUpdate(Request $request, $id){
        $request->validate([
            'penulis'         => 'required|string|max:50',
            'title'           => 'required|string|max:255',
            'abstract'        => 'nullable|string',
            'keywords'        => 'nullable|string',
            'tahun_akademik'  => 'required|string|max:10',
            'file_path'       => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120'
        ]);

        $skripsi = DocumentModel::findOrFail($id);

        // Tentukan status otomatis berdasarkan role user
        $user = Auth::user();
        $status = ($user && $user->role_id == '1') ? 'approved' : 'pending';

        // Upload file baru jika ada
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $storedPath = $file->store('uploads/skripsi', 'public');
            $fileName = basename($storedPath);

            // (Optional) Hapus file lama jika perlu
            if (!empty($skripsi->file_path) && Storage::disk('public')->exists('uploads/skripsi/' . $skripsi->file_path)) {
                Storage::disk('public')->delete('uploads/skripsi/' . $skripsi->file_path);
            }

            $skripsi->file_path = $fileName;
            $skripsi->file_size = $file->getSize() / 1024 / 1024; // dalam MB
        }

        // Update data lainnya
        $skripsi->penulis         = $request->penulis;
        $skripsi->title           = $request->title;
        $skripsi->abstract        = $request->abstract;
        $skripsi->keywords        = $request->keywords;
        $skripsi->tahun_akademik  = $request->tahun_akademik;
        $skripsi->status          = $status;
        $skripsi->upload_date     = now(); // bisa disesuaikan jika tidak ingin memperbarui tanggal
        $skripsi->save();

        return redirect('doc/skripsi')->with('success', 'Data skripsi berhasil diperbarui.');
    }

    public function skripsiDelete($id){
        // Ambil data skripsi berdasarkan ID
        $skripsi = DocumentModel::findOrFail($id);

        // Hapus file dari storage jika ada
        if (!empty($skripsi->file_path) && Storage::disk('public')->exists('uploads/skripsi/' . $skripsi->file_path)) {
            Storage::disk('public')->delete('uploads/skripsi/' . $skripsi->file_path);
        }

        // Hapus data dari database
        $skripsi->delete();

        // Redirect kembali dengan pesan sukses
        return redirect('doc/skripsi')->with('success', 'Data skripsi berhasil dihapus.');
    }

    // tesis
    public function tesisList(Request $request){
        $permissions = getUserPermissions();

        // Cek apakah user memiliki akses melihat dokumen skripsi
        if (empty($permissions['permissionDocTesis'])) {
            abort(404);
        }

        // Jika request adalah AJAX (untuk DataTables)
        if ($request->ajax()) {
            $data = DocumentModel::getListTugasAkhir()
                            ->where('documents.type', 'tesis');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($permissions) {
                    $buttons = '';

                    if (!empty(PermissionRoleModel::getPermission('DetailDoctesis', Auth::user()->role_id))){
                        $buttons .= '<a href="' . url('doc/tesis/detail/'.$row->document_id).'" class="btn btn-primary btn-sm btn-detail mt-1"><i class="fas fa-eye"></i> Detail</a><br>';
                    }

                    if (!empty(PermissionRoleModel::getPermission('EditDoctesis', Auth::user()->role_id))) {
                        $buttons .= '<a href="' . url('doc/tesis/edit/' . $row->document_id) . '" class="btn btn-sm btn-warning mt-1"><i class="fas fa-edit"></i> Edit</a> <br>';
                    }

                    if (!empty(PermissionRoleModel::getPermission('DeleteDoctesis', Auth::user()->role_id))) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-btn mt-1" data-id="' . $row->document_id . '"><i class="fas fa-trash-alt"></i> Delete</button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Jika bukan request AJAX, kembalikan halaman view biasa
        $data['permissionAdd'] = PermissionRoleModel::getPermission('AddDoctesis', Auth::user()->role_id);

        return view('Document.tesisIndex', $data);
    }

    public function tesisAdd(){
        return view('Document.tesisAdd');
    }

    public function tesisInsert(Request $request){
        $request->validate([
            'penulis'         => 'required|string|max:50',
            'title'           => 'required|string|max:255',
            'abstract'        => 'nullable|string',
            'keywords'        => 'nullable|string',
            'tahun_akademik'  => 'required|string|max:10',
            'file_path'       => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120' // 5MB, valid extensions
        ]);

        // Tentukan status otomatis berdasarkan role
        $user = Auth::user();
        $status = ($user && $user->role_id == '1') ? 'approved' : 'pending';

        // Upload file
        $filePath = null;
        $fileSize = null;
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $storedPath = $file->store('uploads/tesis', 'public');

            $fileSize = $file->getSize() / 1024 / 1024; // dalam MB

            // Ambil hanya nama filenya (tanpa folder)
            $filePath = basename($storedPath);
            // dd([
            //     'exists' => file_exists(storage_path('app/public/uploads/tesis')),
            //     'canWrite' => is_writable(storage_path('app/public/uploads/tesis')),
            //     'storeTest' => $file->store('uploads/tesis', 'public'),
            // ]);
        }

        // Simpan data ke dalam database
        $tesis = new DocumentModel;
        $tesis->document_id     = (string) Str::uuid();
        $tesis->penulis         = $request->penulis;
        $tesis->title           = $request->title;
        $tesis->abstract        = $request->abstract;
        $tesis->keywords        = $request->keywords;
        $tesis->tahun_akademik  = $request->tahun_akademik;
        $tesis->file_path       = $filePath;
        $tesis->file_size       = $fileSize;
        $tesis->status          = $status;
        $tesis->upload_date     = now();
        $tesis->type            = 'tesis';
        $tesis->save();

        return redirect('doc/tesis')->with('success', 'Tesis berhasil ditambahkan.');
    }

    public function tesisDetail($id){
        $data['getRecord'] = DocumentModel::getDetailTugasAkhir($id);

        return view('Document.tesisDetail', $data);
    }

    public function tesisEdit($id){
        $data['getRecord'] = DocumentModel::getDetailTugasAkhir($id);

        return view('Document.tesisEdit', $data);
    }

    public function tesisUpdate(Request $request, $id){
        $request->validate([
            'penulis'         => 'required|string|max:50',
            'title'           => 'required|string|max:255',
            'abstract'        => 'nullable|string',
            'keywords'        => 'nullable|string',
            'tahun_akademik'  => 'required|string|max:10',
            'file_path'       => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120'
        ]);

        $tesis = DocumentModel::findOrFail($id);

        // Tentukan status otomatis berdasarkan role user
        $user = Auth::user();
        $status = ($user && $user->role_id == '1') ? 'approved' : 'pending';

        // Upload file baru jika ada
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $storedPath = $file->store('uploads/tesis', 'public');
            $fileName = basename($storedPath);

            // (Optional) Hapus file lama jika perlu
            if (!empty($tesis->file_path) && Storage::disk('public')->exists('uploads/tesis/' . $tesis->file_path)) {
                Storage::disk('public')->delete('uploads/tesis/' . $tesis->file_path);
            }

            $tesis->file_path = $fileName;
            $tesis->file_size = $file->getSize() / 1024 / 1024; // dalam MB
        }

        // Update data lainnya
        $tesis->penulis         = $request->penulis;
        $tesis->title           = $request->title;
        $tesis->abstract        = $request->abstract;
        $tesis->keywords        = $request->keywords;
        $tesis->tahun_akademik  = $request->tahun_akademik;
        $tesis->status          = $status;
        $tesis->upload_date     = now(); // bisa disesuaikan jika tidak ingin memperbarui tanggal
        $tesis->save();

        return redirect('doc/tesis')->with('success', 'Data tesis berhasil diperbarui.');
    }

    public function tesisDelete($id){
        // Ambil data tesis berdasarkan ID
        $tesis = DocumentModel::findOrFail($id);

        // Hapus file dari storage jika ada
        if (!empty($tesis->file_path) && Storage::disk('public')->exists('uploads/tesis/' . $tesis->file_path)) {
            Storage::disk('public')->delete('uploads/tesis/' . $tesis->file_path);
        }

        // Hapus data dari database
        $tesis->delete();

        // Redirect kembali dengan pesan sukses
        return redirect('doc/tesis')->with('success', 'Data tesis berhasil dihapus.');
    }

    // Penelitian
    public function penelitianList(Request $request){
        $permissions = getUserPermissions();

        // Cek apakah user memiliki akses melihat dokumen skripsi
        if (empty($permissions['permissionDocPenelitian'])) {
            abort(404);
        }

        // Jika request adalah AJAX (untuk DataTables)
        if ($request->ajax()) {
            $data = DocumentModel::getListPenelitian()
                            ->where('documents.type', 'penelitian');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($permissions) {
                    $buttons = '';

                    if (!empty(PermissionRoleModel::getPermission('DetailDocPenelitian', Auth::user()->role_id))){
                        $buttons .= '<a href="' . url('doc/penelitian/detail/'.$row->document_id).'" class="btn btn-primary btn-sm btn-detail"><i class="fas fa-eye"></i> Detail</a><br>';
                    }

                    if (!empty(PermissionRoleModel::getPermission('EditDocPenelitian', Auth::user()->role_id))) {
                        $buttons .= '<a href="' . url('doc/penelitian/edit/' . $row->document_id) . '" class="btn btn-sm btn-warning mt-1"><i class="fas fa-edit"></i> Edit</a> <br>';
                    }

                    if (!empty(PermissionRoleModel::getPermission('DeleteDocPenelitian', Auth::user()->role_id))) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-btn mt-1" data-id="' . $row->document_id . '"><i class="fas fa-trash-alt"></i> Delete</button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Jika bukan request AJAX, kembalikan halaman view biasa
        $data['permissionAdd'] = PermissionRoleModel::getPermission('AddDocpenelitian', Auth::user()->role_id);

        return view('Document.penelitianIndex', $data);
    }

    public function penelitianAdd(){
        return view('Document.penelitianAdd');
    }

    public function penelitianInsert(Request $request){
        $request->validate([
            'penulis'                  => 'required|string|max:200',
            'email'                    => 'required|email|max:250',
            'afiliasi'                 => 'required|string|max:250',
            'title'                    => 'required|string',
            'abstract'                 => 'nullable|string',
            'keywords'                 => 'nullable|string',
            'biaya_penelitian'         => 'required|string|in:Mandiri,Dibiayai',
            'lembaga_biaya'            => 'nullable|string|max:250',
            'terbit'                   => 'required|in:Y,N',
            'indeks_nasional'          => 'nullable|in:Y,N',
            'peringkat_nasional'       => 'nullable|string|max:200',
            'indeks_internasional'     => 'nullable|in:Y,N',
            'peringkat_internasional'  => 'nullable|string|max:200',
            'indeks_lainnya'           => 'nullable|string|max:200',
            'link_jurnal'              => 'nullable|url|max:200',
            'tahun_akademik'           => 'required|string|max:5',
            'file_path'                => 'nullable|file|mimes:pdf|max:5120',
            'authors'                  => 'nullable|array',
            'authors.*.author_name'     => 'nullable|string|max:255',
            'authors.*.author_email'    => 'nullable|email|max:250',
            'authors.*.author_affiliation' => 'nullable|string|max:255'
        ]);

        $user = Auth::user();
        $status = ($user && $user->role_id == '1') ? 'approved' : 'pending';

        // Upload file
        $fileName = null;
        $fileSize = null;

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $storedPath = $file->store('uploads/penelitian', 'public');
            $fileName = basename($storedPath);
            $fileSize = $file->getSize() / 1024 / 1024; // dalam MB
        }

        // $documentId = (string) Str::uuid();

        $doc = new DocumentModel;
        // $doc->document_id             = $documentId;
        $doc->penulis                 = strtoupper($request->penulis);
        $doc->email                   = $request->email;
        $doc->afiliasi                = $request->afiliasi;
        $doc->title                   = $request->title;
        $doc->abstract                = $request->abstract;
        $doc->keywords                = $request->keywords;
        $doc->biaya_penelitian        = $request->biaya_penelitian;
        $doc->lembaga_biaya           = $request->biaya_penelitian === 'Dibiayai' ? $request->lembaga_biaya : null;
        $doc->terbit                  = $request->terbit;
        $doc->indeks_nasional         = $request->terbit === 'Y' ? $request->indeks_nasional : null;
        $doc->peringkat_nasional      = $request->terbit === 'Y' && $request->indeks_nasional === 'Y' ? $request->peringkat_nasional : null;
        $doc->indeks_internasional    = $request->terbit === 'Y' ? $request->indeks_internasional : null;
        $doc->peringkat_internasional = $request->terbit === 'Y' && $request->indeks_internasional === 'Y' ? $request->peringkat_internasional : null;
        $doc->indeks_lainnya          = $request->terbit === 'Y' ? $request->indeks_lainnya : null;
        $doc->link_jurnal             = $request->terbit === 'Y' ? $request->link_jurnal : null;
        $doc->upload_date             = now()->format('Y-m-d');
        $doc->type                    = 'penelitian';
        $doc->file_path               = $fileName;
        $doc->file_size               = $fileSize;
        $doc->tahun_akademik          = $request->tahun_akademik;
        $doc->status                  = $status;
        $doc->save();

        $documentId = $doc->document_id;

        // Simpan penulis tambahan (authors)
        if ($request->filled('authors')) {
            foreach ($request->authors as $author) {
                if (!empty($author['author_name'])) {
                    $authorRecord = new DocumentAuthorsModel();
                    $authorRecord->document_id         = $documentId;
                    $authorRecord->author_name         = strtoupper($author['author_name']);
                    $authorRecord->author_email        = $author['author_email'] ?? null;
                    $authorRecord->author_affiliation  = $author['author_affiliation'] ?? null;
                    $authorRecord->save();
                }
            }
        }

        return redirect('doc/penelitian')->with('success', 'Data penelitian berhasil ditambahkan.');
    }

    public function penelitianEdit($id){
        $data['getPenelitian'] = DocumentModel::getDetailPenelitian($id);

        $data['getAuthors'] = DocumentAuthorsModel::getDetailAuthors($id)->get();

        return view('Document.penelitianEdit', $data);
    }

    public function penelitianUpdate(Request $request, $id){
        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string',
            'keywords' => 'nullable|string',
            'email' => 'required|email',
            'afiliasi' => 'required|string',
            'tahun_akademik' => 'required|string',
            'file_path' => 'nullable|mimes:pdf|max:5120',
        ]);

        // Ambil data penelitian
        $penelitian = DocumentModel::findOrFail($id);

        // Upload file
        $fileName = null;
        $fileSize = null;

        // Cek dan hapus file lama jika file baru diunggah
        if ($request->hasFile('file_path')) {
            // Hapus file dari storage jika ada
            if (!empty($penelitian->file_path) && Storage::disk('public')->exists('uploads/penelitian/' . $penelitian->file_path)) {
                Storage::disk('public')->delete('uploads/penelitian/' . $penelitian->file_path);
            }

            $file = $request->file('file_path');
            $storedPath = $file->store('uploads/penelitian', 'public');
            $fileName = basename($storedPath);
            $fileSize = $file->getSize() / 1024 / 1024; // dalam MB

            $penelitian->file_path = $fileName;
            $penelitian->file_size = $fileSize;
        }

        // Update field lainnya
        $penelitian->penulis = $request->penulis;
        $penelitian->email = $request->email;
        $penelitian->afiliasi = $request->afiliasi;
        $penelitian->title = $request->title;
        $penelitian->abstract = $request->abstract;
        $penelitian->keywords = $request->keywords;
        $penelitian->biaya_penelitian = $request->biaya_penelitian;
        $penelitian->lembaga_biaya = $request->biaya_penelitian == 'Dibiayai' ? $request->lembaga_biaya : null;
        $penelitian->terbit = $request->terbit;
        $penelitian->indeks_nasional = $request->indeks_nasional ?? 'N';
        $penelitian->peringkat_nasional = $request->indeks_nasional == 'Y' ? $request->peringkat_nasional : null;
        $penelitian->indeks_internasional = $request->indeks_internasional ?? 'N';
        $penelitian->peringkat_internasional = $request->indeks_internasional == 'Y' ? $request->peringkat_internasional : null;
        $penelitian->indeks_lainnya = $request->indeks_lainnya;
        $penelitian->link_jurnal = $request->link_jurnal;
        $penelitian->tahun_akademik = $request->tahun_akademik;

        $penelitian->save();

        // Hapus semua co-authors lama
        DocumentAuthorsModel::where('document_id', $id)->delete();

        // Simpan co-authors baru
        if ($request->has('authors')) {
            foreach ($request->authors as $author) {
                if (!empty($author['author_name'])) {
                    $authorRecord = new DocumentAuthorsModel();
                    $authorRecord->document_id         = $id;
                    $authorRecord->author_name         = strtoupper($author['author_name']);
                    $authorRecord->author_email        = $author['author_email'] ?? null;
                    $authorRecord->author_affiliation  = $author['author_affiliation'] ?? null;
                    $authorRecord->save();
                }
            }
        }

        return redirect('doc/penelitian')->with('success', 'Data penelitian berhasil diperbarui.');
    }

    public function penelitianDelete($id){
        // Ambil data penelitian berdasarkan ID
        $penelitian = DocumentModel::findOrFail($id);

        // Hapus file dari storage jika ada
        if (!empty($penelitian->file_path) && file_exists(public_path('storage/uploads/penelitian/' . $penelitian->file_path))) {
            unlink(public_path('storage/uploads/penelitian/' . $penelitian->file_path));
        }

        // Hapus semua co-authors yang terkait
        DocumentAuthorsModel::where('document_id', $id)->delete();

        // Hapus data utama penelitian
        $penelitian->delete();

        // Redirect kembali dengan pesan sukses
        return redirect('doc/penelitian')->with('success', 'Data penelitian berhasil dihapus.');
    }

    public function cariPenulis(Request $request){
        $q = $request->get('q');

        if (strlen($q) < 3) {
            return response()->json([]);
        }

        // Ambil data dosen
        $dosen = DosenModel::select('nama_dosen as name', 'nidn as id')
            ->where(function($query) use ($q) {
                $query->where('nama_dosen', 'LIKE', '%' . $q . '%')
                    ->orWhere('nidn', 'LIKE', '%' . $q . '%');
            })
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->name,
                    'text' => "[Dosen] {$item->id} - {$item->name}"
                ];
            });

        // Ambil semua jenjang mahasiswa
        $mahasiswa = MahasiswaModel::getRecordList()
            ->where(function($query) use ($q) {
                $query->where('m_mahasiswa.nama_mahasiswa', 'LIKE', '%' . $q . '%')
                    ->orWhere('m_riwayat_pendidikan_mhs.nim', 'LIKE', '%' . $q . '%');
            })
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->nama_mahasiswa,
                    'text' => "[Mahasiswa] {$item->nim_mahasiswa} - {$item->nama_mahasiswa}"
                ];
            });

        // Gabungkan hasil dosen dan mahasiswa
        $results = $dosen->merge($mahasiswa);

        return response()->json($results);
    }

    public function penelitianDetail($id){
        $data['getPenelitian'] = DocumentModel::getDetailPenelitian($id);

        $data['getAuthors'] = DocumentAuthorsModel::getDetailAuthors($id)->get();

        return view('Document.penelitianDetail', $data);
    }

    // Pengabdian
    public function pengabdianList(Request $request){
        $permissions = getUserPermissions();

        // Cek apakah user memiliki akses melihat dokumen pengabdian
        if (empty($permissions['permissionDocPengabdian'])) {
            abort(404);
        }

        if ($request->ajax()) {
            $data = DocumentModel::getListPenelitian()
                        ->where('documents.type', 'pengabdian');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($permissions) {
                    $buttons = '';

                    if (!empty(PermissionRoleModel::getPermission('DetailDocPengabdian', Auth::user()->role_id))) {
                        $buttons .= '<a href="' . url('doc/pengabdian/detail/'.$row->document_id).'" class="btn btn-primary btn-sm btn-detail"><i class="fas fa-eye"></i> Detail</a><br>';
                    }

                    if (!empty(PermissionRoleModel::getPermission('EditDocPengabdian', Auth::user()->role_id))) {
                        $buttons .= '<a href="' . url('doc/pengabdian/edit/' . $row->document_id) . '" class="btn btn-sm btn-warning mt-1"><i class="fas fa-edit"></i> Edit</a> <br>';
                    }

                    if (!empty(PermissionRoleModel::getPermission('DeleteDocPengabdian', Auth::user()->role_id))) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-btn mt-1" data-id="' . $row->document_id . '"><i class="fas fa-trash-alt"></i> Delete</button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data['permissionAdd'] = PermissionRoleModel::getPermission('AddDocPengabdian', Auth::user()->role_id);

        return view('Document.pengabdianIndex', $data);
    }

    public function pengabdianAdd(){
        return view('Document.pengabdianAdd');
    }

    public function pengabdianInsert(Request $request){
        $request->validate([
            'penulis'                  => 'required|string|max:200',
            'email'                    => 'required|email|max:250',
            'afiliasi'                 => 'required|string|max:250',
            'title'                    => 'required|string',
            'abstract'                 => 'nullable|string',
            'keywords'                 => 'nullable|string',
            'biaya_penelitian'         => 'required|string|in:Mandiri,Dibiayai',
            'lembaga_biaya'            => 'nullable|string|max:250',
            'terbit'                   => 'required|in:Y,N',
            'indeks_nasional'          => 'nullable|in:Y,N',
            'peringkat_nasional'       => 'nullable|string|max:200',
            'indeks_lainnya'           => 'nullable|string|max:200',
            'link_jurnal'              => 'nullable|url|max:200',
            'tahun_akademik'           => 'required|string|max:5',
            'file_path'                => 'nullable|file|mimes:pdf|max:5120',
            'authors'                  => 'nullable|array',
            'authors.*.author_name'     => 'nullable|string|max:255',
            'authors.*.author_email'    => 'nullable|email|max:250',
            'authors.*.author_affiliation' => 'nullable|string|max:255'
        ]);

        $user = Auth::user();
        $status = ($user && $user->role_id == '1') ? 'approved' : 'pending';

        $fileName = null;
        $fileSize = null;

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $storedPath = $file->store('uploads/pengabdian', 'public');
            $fileName = basename($storedPath);
            $fileSize = $file->getSize() / 1024 / 1024;
        }

        $doc = new DocumentModel;
        $doc->penulis                 = strtoupper($request->penulis);
        $doc->email                   = $request->email;
        $doc->afiliasi                = $request->afiliasi;
        $doc->title                   = $request->title;
        $doc->abstract                = $request->abstract;
        $doc->keywords                = $request->keywords;
        $doc->biaya_penelitian        = $request->biaya_penelitian;
        $doc->lembaga_biaya           = $request->biaya_penelitian === 'Dibiayai' ? $request->lembaga_biaya : null;
        $doc->terbit                  = $request->terbit;
        $doc->indeks_nasional         = $request->terbit === 'Y' ? $request->indeks_nasional : null;
        $doc->peringkat_nasional      = $request->terbit === 'Y' && $request->indeks_nasional === 'Y' ? $request->peringkat_nasional : null;
        $doc->indeks_lainnya          = $request->terbit === 'Y' ? $request->indeks_lainnya : null;
        $doc->link_jurnal             = $request->terbit === 'Y' ? $request->link_jurnal : null;
        $doc->upload_date             = now()->format('Y-m-d');
        $doc->type                    = 'pengabdian';
        $doc->file_path               = $fileName;
        $doc->file_size               = $fileSize;
        $doc->tahun_akademik          = $request->tahun_akademik;
        $doc->status                  = $status;
        $doc->save();

        $documentId = $doc->document_id;

        if ($request->filled('authors')) {
            foreach ($request->authors as $author) {
                if (!empty($author['author_name'])) {
                    $authorRecord = new DocumentAuthorsModel();
                    $authorRecord->document_id         = $documentId;
                    $authorRecord->author_name         = strtoupper($author['author_name']);
                    $authorRecord->author_email        = $author['author_email'] ?? null;
                    $authorRecord->author_affiliation  = $author['author_affiliation'] ?? null;
                    $authorRecord->save();
                }
            }
        }

        return redirect('doc/pengabdian')->with('success', 'Data pengabdian berhasil ditambahkan.');
    }

    public function pengabdianEdit($id){
        $data['getPengabdian'] = DocumentModel::getDetailPenelitian($id);

        $data['getAuthors'] = DocumentAuthorsModel::getDetailAuthors($id)->get();

        return view('Document.pengabdianEdit', $data);
    }

    public function pengabdianUpdate(Request $request, $id){
        $request->validate([
            'penulis' => 'required|string|max:200',
            'email' => 'required|email|max:250',
            'afiliasi' => 'required|string|max:250',
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string',
            'keywords' => 'nullable|string',
            'biaya_penelitian' => 'required|in:Mandiri,Dibiayai',
            'lembaga_biaya' => 'nullable|string|max:255',
            'terbit' => 'required|in:Y,N',
            'indeks_nasional' => 'nullable|in:Y,N',
            'peringkat_nasional' => 'nullable|string|max:200',
            'indeks_lainnya' => 'nullable|string|max:200',
            'link_jurnal' => 'nullable|url|max:255',
            'tahun_akademik' => 'required|string|max:5',
            'file_path' => 'nullable|mimes:pdf|max:5120',
        ]);

        $pengabdian = DocumentModel::findOrFail($id);

        // Upload file
        if ($request->hasFile('file_path')) {
            // Hapus file lama jika ada
            if (!empty($pengabdian->file_path) && Storage::disk('public')->exists('uploads/pengabdian/' . $pengabdian->file_path)) {
                Storage::disk('public')->delete('uploads/pengabdian/' . $pengabdian->file_path);
            }

            $file = $request->file('file_path');
            $storedPath = $file->store('uploads/pengabdian', 'public');
            $pengabdian->file_path = basename($storedPath);
            $pengabdian->file_size = $file->getSize() / 1024 / 1024;
        }

        // Update data
        $pengabdian->penulis = strtoupper($request->penulis);
        $pengabdian->email = $request->email;
        $pengabdian->afiliasi = $request->afiliasi;
        $pengabdian->title = $request->title;
        $pengabdian->abstract = $request->abstract;
        $pengabdian->keywords = $request->keywords;
        $pengabdian->biaya_penelitian = $request->biaya_penelitian;
        $pengabdian->lembaga_biaya = $request->biaya_penelitian === 'Dibiayai' ? $request->lembaga_biaya : null;
        $pengabdian->terbit = $request->terbit;
        $pengabdian->indeks_nasional = $request->terbit === 'Y' ? ($request->indeks_nasional ?? 'N') : null;
        $pengabdian->peringkat_nasional = $request->terbit === 'Y' && $request->indeks_nasional === 'Y' ? $request->peringkat_nasional : null;
        $pengabdian->indeks_lainnya = $request->terbit === 'Y' ? $request->indeks_lainnya : null;
        $pengabdian->link_jurnal = $request->terbit === 'Y' ? $request->link_jurnal : null;
        $pengabdian->tahun_akademik = $request->tahun_akademik;
        $pengabdian->save();

        // Hapus semua co-authors lama
        DocumentAuthorsModel::where('document_id', $id)->delete();

        // Tambahkan kembali co-authors jika ada
        if ($request->has('authors')) {
            foreach ($request->authors as $author) {
                if (!empty($author['author_name'])) {
                    $authorRecord = new DocumentAuthorsModel();
                    $authorRecord->document_id = $id;
                    $authorRecord->author_name = strtoupper($author['author_name']);
                    $authorRecord->author_email = $author['author_email'] ?? null;
                    $authorRecord->author_affiliation = $author['author_affiliation'] ?? null;
                    $authorRecord->save();
                }
            }
        }

        return redirect('doc/pengabdian')->with('success', 'Data pengabdian berhasil diperbarui.');
    }

    public function pengabdianDetail($id){
        $data['getPengabdian'] = DocumentModel::getDetailPenelitian($id);
        $data['getAuthors'] = DocumentAuthorsModel::getDetailAuthors($id)->get();
        return view('Document.pengabdianDetail', $data);
    }

    public function pengabdianDelete($id){
        // Ambil data pengabdian berdasarkan ID
        $pengabdian = DocumentModel::findOrFail($id);

        // Hapus file dari storage jika ada
        if (!empty($pengabdian->file_path) && file_exists(public_path('storage/uploads/pengabdian/' . $pengabdian->file_path))) {
            unlink(public_path('storage/uploads/pengabdian/' . $pengabdian->file_path));
        }

        // Hapus semua co-authors yang terkait
        DocumentAuthorsModel::where('document_id', $id)->delete();

        // Hapus data utama pengabdian
        $pengabdian->delete();

        // Redirect kembali dengan pesan sukses
        return redirect('doc/pengabdian')->with('success', 'Data pengabdian berhasil dihapus.');
    }

}
