<?php

namespace App\Http\Controllers\FrontEnd;

use App\Helpers\logRequestRepo;
use App\Http\Controllers\Controller;
use App\Models\Document\DocumentAuthorsModel;
use App\Models\Document\DocumentModel;
use App\Models\Document\LogRequestModel;
use App\Models\Konfigurasi\Repo\CustomPagesModel;
use App\Models\Konfigurasi\Repo\FrontendSettingModel;
use App\Models\Konfigurasi\Repo\SliderModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class frontRepoController extends Controller
{
    public function repoIndex(){
        $activeSetting = FrontendSettingModel::where('status', 'Y')->first();
        $sliders = SliderModel::where('status', 'Y')->orderBy('order')->get();
        $customPages = CustomPagesModel::where('status', 'Y')->get();

        return view('FrontEndRepo.Repo.repoIndex', [
            'setting' => $activeSetting,
            'sliders' => $sliders,
            'customPages' => $customPages
        ]);
    }

    public function repoSkripsi(Request $request){
        $query = DocumentModel::getListTugasAkhir()->where('documents.type', 'skripsi')->where('documents.status', 'approved');

        // Filter berdasarkan judul
        if ($request->filled('judul')) {
            $query->where('documents.title', 'LIKE', '%' . $request->judul . '%');
        }

        // Filter berdasarkan tahun akademik
        if ($request->filled('tahun')) {
            $query->where('documents.tahun_akademik', $request->tahun);
        }

        // Filter berdasarkan abstrak
        if ($request->filled('abstrak')) {
            $query->where('documents.abstract', 'LIKE', '%' . $request->abstrak . '%');
        }

        // Filter berdasarkan program studi
        if ($request->filled('prodi')) {
            $query->where('m_riwayat_pendidikan_mhs.nama_program_studi', $request->prodi);
        }

        $tahun_query = DocumentModel::join('m_riwayat_pendidikan_mhs', 'm_riwayat_pendidikan_mhs.nim', '=', 'documents.penulis')
            ->where('documents.type', 'skripsi')
            ->where('documents.status', 'approved');

        if ($request->filled('prodi')) {
            $tahun_query->where('m_riwayat_pendidikan_mhs.nama_program_studi', $request->prodi);
        }

        $tahun_list = $tahun_query
                    ->select('documents.tahun_akademik')
                    ->selectRaw('COUNT(*) as jumlah_data')
                    ->groupBy('documents.tahun_akademik')
                    ->orderByDesc('documents.tahun_akademik')
                    ->get();

        // Ambil semua data setelah filter
        $skripsi_all = $query->orderByDesc('documents.upload_date')->get();
        $jum_data = $skripsi_all->count();

        // Daftar program studi
        $prodi_list = DocumentModel::getListTugasAkhir()
        ->where('documents.type', 'skripsi')
        ->where('documents.status', 'approved')
        ->select('m_riwayat_pendidikan_mhs.nama_program_studi')
        ->distinct()
        ->orderBy('m_riwayat_pendidikan_mhs.nama_program_studi')
        ->get();

        // Tentukan ikon berdasarkan ekstensi file
        foreach ($skripsi_all as $item) {
            $extension = pathinfo($item->file_path, PATHINFO_EXTENSION);
            $item->icon = match (strtolower($extension)) {
                'pdf' => 'pdf.png',
                'doc', 'docx' => 'doc.png',
                'ppt', 'pptx' => 'ppt.png',
                'xls', 'xlsx' => 'excel.png',
                'rar', 'zip' => 'zip.png',
                default => 'empty.png',
            };
        }

        // Paginasi manual
        $page = $request->get('page', 1);
        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        $paginated_skripsi = $skripsi_all->slice($offset, $perPage);
        $total_pages = (int) ceil($jum_data / $perPage);

        return view('FrontEndRepo.Repo.skripsi', [
            'skripsi_data' => $paginated_skripsi,
            'jum_data' => $jum_data,
            'page' => $page,
            'total_pages' => $total_pages,
            'tahun_list' => $tahun_list,
            'prodi_list' => $prodi_list,
            'selected_tahun' => $request->tahun,
            'selected_judul' => $request->judul,
            'selected_abstrak' => $request->abstrak,
            'selected_prodi' => $request->prodi
        ]);
    }

    public function repoTesis(Request $request){
        $query = DocumentModel::getListTugasAkhir()
            ->where('documents.type', 'tesis')
            ->where('documents.status', 'approved');

        if ($request->filled('judul')) {
            $query->where('documents.title', 'LIKE', '%' . $request->judul . '%');
        }

        if ($request->filled('tahun')) {
            $query->where('documents.tahun_akademik', $request->tahun);
        }

        if ($request->filled('abstrak')) {
            $query->where('documents.abstract', 'LIKE', '%' . $request->abstrak . '%');
        }

        if ($request->filled('prodi')) {
            $query->where('m_riwayat_pendidikan_mhs.nama_program_studi', $request->prodi);
        }

        $tahun_query = DocumentModel::join('m_riwayat_pendidikan_mhs', 'm_riwayat_pendidikan_mhs.nim', '=', 'documents.penulis')
            ->where('documents.type', 'tesis')
            ->where('documents.status', 'approved');

        if ($request->filled('prodi')) {
            $tahun_query->where('m_riwayat_pendidikan_mhs.nama_program_studi', $request->prodi);
        }

        $tahun_list = $tahun_query
            ->select('documents.tahun_akademik')
            ->selectRaw('COUNT(*) as jumlah_data')
            ->groupBy('documents.tahun_akademik')
            ->orderByDesc('documents.tahun_akademik')
            ->get();

        $tesis_all = $query->orderByDesc('documents.upload_date')->get();
        $jum_data = $tesis_all->count();

        $prodi_list = DocumentModel::getListTugasAkhir()
            ->where('documents.type', 'tesis')
            ->where('documents.status', 'approved')
            ->select('m_riwayat_pendidikan_mhs.nama_program_studi')
            ->distinct()
            ->orderBy('m_riwayat_pendidikan_mhs.nama_program_studi')
            ->get();

        foreach ($tesis_all as $item) {
            $extension = pathinfo($item->file_path, PATHINFO_EXTENSION);
            $item->icon = match (strtolower($extension)) {
                'pdf' => 'pdf.png',
                'doc', 'docx' => 'doc.png',
                'ppt', 'pptx' => 'ppt.png',
                'xls', 'xlsx' => 'excel.png',
                'rar', 'zip' => 'zip.png',
                default => 'empty.png',
            };
        }

        $page = $request->get('page', 1);
        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        $paginated_tesis = $tesis_all->slice($offset, $perPage);
        $total_pages = (int) ceil($jum_data / $perPage);

        return view('FrontEndRepo.Repo.tesis', [
            'tesis_data' => $paginated_tesis,
            'jum_data' => $jum_data,
            'page' => $page,
            'total_pages' => $total_pages,
            'tahun_list' => $tahun_list,
            'prodi_list' => $prodi_list,
            'selected_tahun' => $request->tahun,
            'selected_judul' => $request->judul,
            'selected_abstrak' => $request->abstrak,
            'selected_prodi' => $request->prodi
        ]);
    }

    public function repoPenelitian(Request $request){
        $query = DocumentModel::getListPenelitian()
            ->where('documents.type', 'penelitian')
            ->where('documents.status', 'approved');

        if ($request->filled('judul')) {
            $query->where('documents.title', 'LIKE', '%' . $request->judul . '%');
        }

        if ($request->filled('abstrak')) {
            $query->where('documents.abstract', 'LIKE', '%' . $request->abstrak . '%');
        }

        if ($request->filled('tahun')) {
            $query->where('documents.tahun_akademik', $request->tahun);
        }

        $tahun_list = DocumentModel::where('type', 'penelitian')
            ->where('status', 'approved')
            ->select('tahun_akademik')
            ->selectRaw('COUNT(*) as jumlah_data')
            ->groupBy('tahun_akademik')
            ->orderByDesc('tahun_akademik')
            ->get();

        $penelitian_all = $query->orderByDesc('upload_date')->get();
        $jum_data = $penelitian_all->count();

        foreach ($penelitian_all as $item) {
            $extension = pathinfo($item->file_path, PATHINFO_EXTENSION);
            $item->icon = match (strtolower($extension)) {
                'pdf' => 'pdf.png',
                'doc', 'docx' => 'doc.png',
                'ppt', 'pptx' => 'ppt.png',
                'xls', 'xlsx' => 'excel.png',
                'rar', 'zip' => 'zip.png',
                default => 'empty.png',
            };
        }

        $page = $request->get('page', 1);
        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        $paginated = $penelitian_all->slice($offset, $perPage);
        $total_pages = (int) ceil($jum_data / $perPage);

        return view('FrontEndRepo.Repo.penelitian', [
            'penelitian_data' => $paginated,
            'jum_data' => $jum_data,
            'page' => $page,
            'total_pages' => $total_pages,
            'tahun_list' => $tahun_list,
            'selected_tahun' => $request->tahun,
            'selected_judul' => $request->judul,
            'selected_abstrak' => $request->abstrak,
        ]);
    }

    public function repoPengabdian(Request $request){
        $query = DocumentModel::getListPenelitian()
            ->where('documents.type', 'pengabdian')
            ->where('documents.status', 'approved');

        if ($request->filled('judul')) {
            $query->where('documents.title', 'LIKE', '%' . $request->judul . '%');
        }

        if ($request->filled('abstrak')) {
            $query->where('documents.abstract', 'LIKE', '%' . $request->abstrak . '%');
        }

        if ($request->filled('tahun')) {
            $query->where('documents.tahun_akademik', $request->tahun);
        }

        $tahun_list = DocumentModel::where('type', 'pengabdian')
            ->where('status', 'approved')
            ->select('tahun_akademik')
            ->selectRaw('COUNT(*) as jumlah_data')
            ->groupBy('tahun_akademik')
            ->orderByDesc('tahun_akademik')
            ->get();

        $pengabdian_all = $query->orderByDesc('upload_date')->get();
        $jum_data = $pengabdian_all->count();

        foreach ($pengabdian_all as $item) {
            $extension = pathinfo($item->file_path, PATHINFO_EXTENSION);
            $item->icon = match (strtolower($extension)) {
                'pdf' => 'pdf.png',
                'doc', 'docx' => 'doc.png',
                'ppt', 'pptx' => 'ppt.png',
                'xls', 'xlsx' => 'excel.png',
                'rar', 'zip' => 'zip.png',
                default => 'empty.png',
            };
        }

        $page = $request->get('page', 1);
        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        $paginated = $pengabdian_all->slice($offset, $perPage);
        $total_pages = (int) ceil($jum_data / $perPage);

        return view('FrontEndRepo.Repo.pengabdian', [
            'pengabdian_data' => $paginated,
            'jum_data' => $jum_data,
            'page' => $page,
            'total_pages' => $total_pages,
            'tahun_list' => $tahun_list,
            'selected_tahun' => $request->tahun,
            'selected_judul' => $request->judul,
            'selected_abstrak' => $request->abstrak,
        ]);
    }

    public function autocompleteSkripsi(Request $request){
        $term = $request->term;
        $pencarian = $request->pencarian ?? 'judul';
        $type = $request->type ?? 'skripsi';

        $query = DocumentModel::getListTugasAkhir()
            ->where('documents.type', $type)
            ->where('documents.status', 'approved');

        // Filter term
        if ($pencarian == 'judul') {
            $query->where('documents.title', 'like', '%' . $term . '%');
        } else {
            $query->where('documents.abstract', 'like', '%' . $term . '%');
        }

        // Filter prodi jika dikirim
        if ($request->filled('prodi')) {
            $query->where('m_riwayat_pendidikan_mhs.nama_program_studi', $request->prodi);
        }

        // Filter tahun jika dikirim
        if ($request->filled('tahun')) {
            $query->where('documents.tahun_akademik', $request->tahun);
        }

        $results = $query->limit(10)->get();

        $data = [];
        foreach ($results as $item) {
            $data[] = [
                'label' => $item->title,
                'value' => $item->title,
                'penulis' => $item->nama_mahasiswa,
                'tahun' => $item->tahun_akademik
            ];
        }

        return response()->json($data);
    }

    public function detailTA($type, $id, Request $request){
        try {
            // $type = ($type ?? '') === 'skripsi' ? 'SKRIPSI' : 'TESIS';
            $start_time = microtime(true);

            // Ambil data detail skripsi
            $skripsi = DocumentModel::select(
                'documents.*',
                'm_riwayat_pendidikan_mhs.nim',
                'm_mahasiswa.nama_mahasiswa',
                'm_riwayat_pendidikan_mhs.nama_program_studi'
            )
            ->join('m_riwayat_pendidikan_mhs', 'm_riwayat_pendidikan_mhs.nim', '=', 'documents.penulis')
            ->join('m_mahasiswa', 'm_mahasiswa.id_mahasiswa', '=', 'm_riwayat_pendidikan_mhs.id_mahasiswa')
            ->where('documents.document_id', $id)
            ->firstOrFail();

            // Tentukan ikon berdasarkan ekstensi
            $ekstensi = strtolower(pathinfo($skripsi->file_path, PATHINFO_EXTENSION));
            $ikon_file = match ($ekstensi) {
                'pdf' => 'img/pdf.png',
                'doc', 'docx' => 'img/doc.png',
                'ppt', 'pptx' => 'img/ppt.png',
                'xls', 'xlsx' => 'img/excel.png',
                'rar', 'zip' => 'img/zip.png',
                default => 'img/empty.png',
            };

            $file_path = $skripsi->file_path ? asset('public/storage/uploads/'.$type.'/' . $skripsi->file_path) : '#';

            $waktu_respon = microtime(true) - $start_time;

            // Simulasi logging (bisa disesuaikan dengan kebutuhan sistem logging real)
            // Simpan log dengan model
            logRequestRepo::logAccess([
                'document' => $skripsi,
                'aksi' => Str::contains($request->fullUrl(), 'download') ? 'download' : 'detail',
                'start_time' => $start_time,
                'access_origin' => 'storage',
                'cache_hit' => 0,
                'data_json' => json_encode([
                                'title' => $skripsi->title,
                                'file_path' => $skripsi->file_path,
                                'uploaded_at' => $skripsi->upload_date,
                                'prodi' => $skripsi->nama_program_studi,
                                'mahasiswa' => $skripsi->nama_mahasiswa,
                                'tahun_akademik' => $skripsi->tahun_akademik,
                            ])
            ]);


            $type = strtoupper($type);

            return view('FrontEndRepo.Repo.skripsiDetail', [
                'skripsi_list' => $skripsi,
                'ikon_file' => $ikon_file,
                'file_path' => $file_path,
                'type' => $type
            ]);

        } catch (Exception $e) {
            report($e);
            return abort(500, 'Terjadi kesalahan saat mengambil data skripsi.');
        }
    }

    public function docDetail($type, $id, Request $request){
        try {
            $start_time = microtime(true);

            $penelitian = DocumentModel::getDetailPenelitian($id);
            $coAuthors = DocumentAuthorsModel::getDetailAuthors($id)->get();

            // File path dan ikon file
            $ekstensi = strtolower(pathinfo($penelitian->file_path, PATHINFO_EXTENSION));
            $ikon_file = match ($ekstensi) {
                'pdf' => 'img/pdf.png',
                'doc', 'docx' => 'img/doc.png',
                'ppt', 'pptx' => 'img/ppt.png',
                'xls', 'xlsx' => 'img/excel.png',
                'rar', 'zip' => 'img/zip.png',
                default => 'img/empty.png',
            };

            // $file_path = $penelitian->file_path ? asset('public/storage/uploads/penelitian/' . $penelitian->file_path) : '#';

            // Logging

            logRequestRepo::logAccess([
                'document' => $penelitian,
                'aksi' => Str::contains($request->fullUrl(), 'download') ? 'download' : 'detail',
                'start_time' => $start_time,
                'access_origin' => 'storage',
                'cache_hit' => 0,
                'data_json' => json_encode([
                                'title' => $penelitian->title,
                                'file_path' => $penelitian->file_path,
                                'uploaded_at' => $penelitian->upload_date,
                                'prodi' => $penelitian->nama_program_studi,
                                'mahasiswa' => $penelitian->nama_mahasiswa,
                                'tahun_akademik' => $penelitian->tahun_akademik,
                            ])
            ]);

            return view('FrontEndRepo.Repo.penelitianDetail', [
                'penelitian' => $penelitian,
                'coAuthors' => $coAuthors,
                'file_path' => $penelitian->file_path,
                'ikon_file' => $ikon_file
            ]);

        } catch (Exception $e) {
            report($e);
            return abort(500, 'Terjadi kesalahan saat mengambil detail penelitian.');
        }
    }

    public function downloadTA($type, $id){
        try {
            $start_time = microtime(true);

            $tugasAkhir = DocumentModel::where('document_id', $id)->firstOrFail();

            $file_path = public_path('storage/uploads/'. strtolower($type) .'/' . $tugasAkhir->file_path);

            if (!file_exists($file_path)) {
                abort(404, 'File tidak ditemukan.');
            }

            // Logging akses

            $log_id = logRequestRepo::logAccess([
                'document' => $tugasAkhir,
                'aksi' => 'download',
                'start_time' => $start_time,
                'data_json' => json_encode([
                                'title' => $tugasAkhir->title,
                                'file_path' => $tugasAkhir->file_path,
                                'uploaded_at' => $tugasAkhir->upload_date,
                                'prodi' => $tugasAkhir->nama_program_studi,
                                'mahasiswa' => $tugasAkhir->nama_mahasiswa,
                                'tahun_akademik' => $tugasAkhir->tahun_akademik,
                            ])
            ]);

            $response = new StreamedResponse(function () use ($file_path, $start_time, $log_id) {
                $stream = fopen($file_path, 'rb');
                fpassthru($stream);
                fclose($stream);

                $transfer_duration = round((microtime(true) - $start_time) * 1000, 2);

                LogRequestModel::where('log_id', $log_id)
                    ->update(['transfer_duration_ms' => $transfer_duration]);

                // Log::channel('requestlog')->info("Transfer complete for log $log_id | Duration: {$transfer_duration}ms");
            });

            $response->headers->set('Content-Type', mime_content_type($file_path));
            $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                basename($file_path)
            ));
            $response->headers->set('X-Log-Id', $log_id);

            return $response;

            // Cek mode preview
            if (request()->has('preview')) {
                // $log->aksi = 'preview';
                // $log->save();z

                // Tampilkan file langsung di browser (inline)
                return response()->file($file_path, [
                    'Content-Type' => mime_content_type($file_path)
                ]);
            }

            return response()->download($file_path, basename($tugasAkhir->file_path));

        } catch (Exception $e) {
            report($e);
            return abort(500, 'Terjadi kesalahan saat mengunduh file.');
        }
    }

}
