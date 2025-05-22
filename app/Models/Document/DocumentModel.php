<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentModel extends Model
{
    use HasFactory;
    protected $table = 'documents';

    protected $primaryKey = 'document_id';

    public $incrementing = false; // Nonaktifkan auto-increment

    protected $keyType = 'string'; // Tentukan tipe primary key sebagai string

    protected static function boot()
    {
        parent::boot();
        // generate UUID pada saat model sedang dibuat
        static::creating(function($model){
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    public static function getListTugasAkhir(){
        return self::select(['m_riwayat_pendidikan_mhs.nim',
                            'm_mahasiswa.nama_mahasiswa',
                            'm_riwayat_pendidikan_mhs.nama_program_studi',
                            'documents.title',
                            'documents.document_id',
                            'documents.abstract',
                            'documents.keywords',
                            'documents.tahun_akademik',
                            'documents.status',
                            'documents.upload_date',
                            'documents.file_path'])
                    ->join('m_riwayat_pendidikan_mhs', 'm_riwayat_pendidikan_mhs.nim', '=', 'documents.penulis')
                    ->join('m_mahasiswa', 'm_mahasiswa.id_mahasiswa','=','m_riwayat_pendidikan_mhs.id_mahasiswa')
                    // ->where('documents.type', 'skripsi')
                    ->orderBy('documents.tahun_akademik', 'DESC');
    }

    public static function getDetailTugasAkhir($id){
        return self::select(['m_riwayat_pendidikan_mhs.nim',
                            'm_mahasiswa.nama_mahasiswa',
                            'm_riwayat_pendidikan_mhs.nama_program_studi',
                            'documents.title',
                            'documents.document_id',
                            'documents.abstract',
                            'documents.keywords',
                            'documents.tahun_akademik',
                            'documents.status',
                            'documents.upload_date',
                            'documents.file_path'
                            ])
                            ->join('m_riwayat_pendidikan_mhs', 'm_riwayat_pendidikan_mhs.nim', '=', 'documents.penulis')
                            ->join('m_mahasiswa', 'm_mahasiswa.id_mahasiswa','=','m_riwayat_pendidikan_mhs.id_mahasiswa')
                            ->where('documents.document_id', $id)->first();
    }

    public static function getListPenelitian(){
        return self::select(['documents.document_id',
                            'documents.penulis',
                            'documents.email',
                            'documents.title',
                            'documents.afiliasi',
                            'documents.abstract',
                            'documents.keywords',
                            'documents.biaya_penelitian',
                            'documents.lembaga_biaya',
                            'documents.terbit',
                            'documents.indeks_nasional',
                            'documents.peringkat_nasional',
                            'documents.indeks_internasional',
                            'documents.peringkat_internasional',
                            'documents.indeks_lainnya',
                            'documents.link_jurnal',
                            'documents.upload_date',
                            'documents.type',
                            'documents.file_path',
                            'documents.status',
                            'documents.tahun_akademik'])
                    ->orderBy('documents.tahun_akademik', 'DESC');
    }

    public static function getDetailPenelitian($id){
        return self::select(['documents.document_id',
                            'documents.penulis',
                            'documents.email',
                            'documents.title',
                            'documents.afiliasi',
                            'documents.abstract',
                            'documents.keywords',
                            'documents.biaya_penelitian',
                            'documents.lembaga_biaya',
                            'documents.terbit',
                            'documents.indeks_nasional',
                            'documents.peringkat_nasional',
                            'documents.indeks_internasional',
                            'documents.peringkat_internasional',
                            'documents.indeks_lainnya',
                            'documents.link_jurnal',
                            'documents.upload_date',
                            'documents.type',
                            'documents.file_path',
                            'documents.status',
                            'documents.tahun_akademik'])
                    ->where('documents.document_id', $id)->first();
    }
}
