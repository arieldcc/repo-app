<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MahasiswaModel extends Model
{
    use HasFactory;
    // mematikan auto increment
    public $incrementing = false;

    // tipe data kunci utama adalah string
    protected $keyType = 'string';

    protected $table = 'm_mahasiswa';

    protected $primaryKey = 'id_mahasiswa';

    protected static function boot(){
        parent::boot();
        // generate UUID pada saat model sedang dibuat
        static::creating(function($model){
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    static public function getRecordList(){
        return self::select(
                    'm_mahasiswa.id_mahasiswa',
                    'm_mahasiswa.nama_mahasiswa',
                    'm_riwayat_pendidikan_mhs.nim as nim_mahasiswa',
                    DB::raw("CASE
                        WHEN m_mahasiswa.jenis_kelamin = 'L' THEN 'Laki-laki'
                        WHEN m_mahasiswa.jenis_kelamin = 'P' THEN 'Perempuan'
                        ELSE m_mahasiswa.jenis_kelamin
                    END AS jenis_kelamin"),
                    'm_mahasiswa.nama_agama as agama_mahasiswa',
                    'm_mahasiswa.tanggal_lahir',
                    'm_riwayat_pendidikan_mhs.nama_program_studi',
                    DB::raw("IFNULL(t_mahasiswa_lulus_do.nama_jenis_keluar, 'Aktif') AS status_mahasiswa"),
                    'rev_semester.id_tahun_ajaran as angkatan',
                    'rev_prodi.nama_jenjang_pendidikan'
                )
                ->join('m_riwayat_pendidikan_mhs', 'm_riwayat_pendidikan_mhs.id_mahasiswa', '=', 'm_mahasiswa.id_mahasiswa')
                ->join('rev_semester', 'rev_semester.id_semester', '=','m_riwayat_pendidikan_mhs.id_periode_masuk')
                ->leftJoin('t_mahasiswa_lulus_do', 't_mahasiswa_lulus_do.nim', '=', 'm_riwayat_pendidikan_mhs.nim')
                ->join('rev_prodi', 'rev_prodi.id_prodi', '=', 'm_riwayat_pendidikan_mhs.id_prodi')
                ->orderBy('angkatan', 'DESC');
    }

    static public function getRecordBayar($sop_biaya_id = null){
        return self::select(
                'm_mahasiswa.id_mahasiswa',
                'm_mahasiswa.nama_mahasiswa as nama_mahasiswa',
                'm_riwayat_pendidikan_mhs.nim as nim_mahasiswa',
                DB::raw("CASE
                    WHEN m_mahasiswa.jenis_kelamin = 'L' THEN 'Laki-laki'
                    WHEN m_mahasiswa.jenis_kelamin = 'P' THEN 'Perempuan'
                    ELSE m_mahasiswa.jenis_kelamin
                END AS jenis_kelamin"),
                'm_mahasiswa.nama_agama as agama_mahasiswa',
                'm_mahasiswa.tanggal_lahir',
                'm_riwayat_pendidikan_mhs.id_prodi',
                'm_riwayat_pendidikan_mhs.nama_program_studi',
                DB::raw("IFNULL(t_mahasiswa_lulus_do.nama_jenis_keluar, 'Aktif') AS status_mahasiswa"),
                'rev_semester.id_tahun_ajaran as angkatan',
                DB::raw("(SELECT t.qty
                      FROM t_mhs_bayar AS t
                      WHERE t.sop_biaya_id = '$sop_biaya_id'
                        AND t.nim = m_riwayat_pendidikan_mhs.nim
                      ORDER BY t.tanggal DESC
                      LIMIT 1) AS qty"), // Perubahan di sini
                // Ambil status_bayar terbaru untuk setiap mahasiswa dan sop_biaya_id
                DB::raw("(SELECT t.status_bayar
                FROM t_mhs_bayar AS t
                WHERE t.sop_biaya_id = '$sop_biaya_id'
                AND t.nim = m_riwayat_pendidikan_mhs.nim
                ORDER BY t.tanggal DESC
                LIMIT 1) AS status_bayar"),
                DB::raw("IFNULL(SUM(CASE WHEN t_mhs_bayar.sop_biaya_id = '$sop_biaya_id' THEN t_mhs_bayar.bayar ELSE 0 END), 0) as total_bayar"), // Total pembayaran hanya untuk sop_biaya_id tertentu
                DB::raw("IFNULL(SUM(CASE WHEN t_mhs_bayar.sop_biaya_id = '$sop_biaya_id' THEN t_mhs_bayar.diskon ELSE 0 END), 0) as total_diskon"), // Total diskon hanya untuk sop_biaya_id tertentu
                DB::raw("MAX(CASE WHEN t_mhs_bayar.sop_biaya_id = '$sop_biaya_id' THEN t_mhs_bayar.tanggal ELSE NULL END) as tanggal_terakhir") // Tanggal pembayaran terakhir untuk sop_biaya_id tertentu
            )
            ->join('m_riwayat_pendidikan_mhs', 'm_riwayat_pendidikan_mhs.id_mahasiswa', '=', 'm_mahasiswa.id_mahasiswa')
            ->join('rev_semester', 'rev_semester.id_semester', '=', 'm_riwayat_pendidikan_mhs.id_periode_masuk')
            ->join('rev_prodi', 'rev_prodi.id_prodi', '=', 'm_riwayat_pendidikan_mhs.id_prodi')
            ->leftJoin('t_mahasiswa_lulus_do', 't_mahasiswa_lulus_do.nim', '=', 'm_riwayat_pendidikan_mhs.nim')
            ->leftJoin('t_mhs_bayar', 't_mhs_bayar.nim', '=', 'm_riwayat_pendidikan_mhs.nim')
            ->groupBy('m_mahasiswa.id_mahasiswa') // Grup berdasarkan mahasiswa
            ->orderBy('angkatan', 'DESC');
    }

    static public function getAllAngkatan(){
        return self::select('rev_semester.id_tahun_ajaran as angkatan')
            ->join('m_riwayat_pendidikan_mhs', 'm_riwayat_pendidikan_mhs.id_mahasiswa', '=', 'm_mahasiswa.id_mahasiswa')
            ->join('rev_semester', 'rev_semester.id_semester', '=', 'm_riwayat_pendidikan_mhs.id_periode_masuk')
            ->groupBy('rev_semester.id_tahun_ajaran')
            ->orderBy('rev_semester.id_tahun_ajaran', 'DESC')
            ->get();
    }

    static public function getAngkatanFakultas($fakultas_id){
        return self::select('rev_semester.id_tahun_ajaran as angkatan')
            ->join('m_riwayat_pendidikan_mhs', 'm_riwayat_pendidikan_mhs.id_mahasiswa', '=', 'm_mahasiswa.id_mahasiswa')
            ->join('rev_semester', 'rev_semester.id_semester', '=', 'm_riwayat_pendidikan_mhs.id_periode_masuk')
            ->join('rev_prodi', 'rev_prodi.id_prodi', '=', 'm_riwayat_pendidikan_mhs.id_prodi')
            ->where('rev_prodi.fakultas_id', $fakultas_id)
            ->groupBy('rev_semester.id_tahun_ajaran')
            ->orderBy('rev_semester.id_tahun_ajaran', 'DESC')
            ->get();
    }

    static public function getAngkatanByProdi($prodi_id){
        return self::select('rev_semester.id_tahun_ajaran as angkatan')
            ->join('m_riwayat_pendidikan_mhs', 'm_riwayat_pendidikan_mhs.id_mahasiswa', '=', 'm_mahasiswa.id_mahasiswa')
            ->join('rev_semester', 'rev_semester.id_semester', '=', 'm_riwayat_pendidikan_mhs.id_periode_masuk')
            ->where('m_riwayat_pendidikan_mhs.id_prodi', $prodi_id)
            ->groupBy('rev_semester.id_tahun_ajaran')
            ->orderBy('rev_semester.id_tahun_ajaran', 'DESC')
            ->get();
    }
}
