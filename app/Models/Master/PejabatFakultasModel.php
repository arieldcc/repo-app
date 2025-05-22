<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class PejabatFakultasModel extends Model
{
    use HasFactory;
    // mematikan auto increment
    public $incrementing = false;

    // tipe data kunci utama adalah string
    protected $keyType = 'string';

    protected $table = 'm_pejabat_fakultas';

    protected $primaryKey = 'id_pejabat_fakultas';

    protected static function boot(){
        parent::boot();
        // generate UUID pada saat model sedang dibuat
        static::creating(function($model){
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    static public function getRecord($id){
        return self::select('m_pejabat_fakultas.*', 'rev_fakultas.nama_fakultas', 'm_jenis_jabatan.id_jenis_jabatan', 'm_jenis_jabatan.nama_jenis_jabatan', 'm_dosen.nama_dosen', 'm_dosen.nidn', 'm_dosen.id_dosen')
                    ->join('rev_fakultas', 'm_pejabat_fakultas.fakultas_id', '=', 'rev_fakultas.id_fakultas')
                    ->join('m_jenis_jabatan', 'm_pejabat_fakultas.jenis_jabatan_id', '=', 'm_jenis_jabatan.id_jenis_jabatan')
                    ->join('m_dosen', 'm_pejabat_fakultas.dosen_id', '=', 'm_dosen.id_dosen')
                    ->where('m_pejabat_fakultas.fakultas_id','=',$id)
                    ->orderBy('m_jenis_jabatan.urut_jabatan', 'ASC');
                    // ->get();
    }

    static public function getSingle($id){
        return self::select('m_pejabat_fakultas.*', 'rev_fakultas.nama_fakultas', 'm_jenis_jabatan.nama_jenis_jabatan', 'm_dosen.nama_dosen')
                        ->join('rev_fakultas', 'm_pejabat_fakultas.fakultas_id', '=', 'rev_fakultas.id_fakultas')
                        ->join('m_jenis_jabatan', 'm_pejabat_fakultas.jenis_jabatan_id', '=', 'm_jenis_jabatan.id_jenis_jabatan')
                        ->join('m_dosen', 'm_pejabat_fakultas.dosen_id', '=', 'm_dosen.id_dosen')
                        ->where('id_pejabat_fakultas','=',$id)->first();
    }

    static public function getDetailFakultas($id_fakultas, $status){
        return self::select('m_pejabat_fakultas.*', 'rev_fakultas.nama_fakultas', 'm_jenis_jabatan.nama_jenis_jabatan', 'm_dosen.nama_dosen')
                    ->join('rev_fakultas', 'm_pejabat_fakultas.fakultas_id', '=', 'rev_fakultas.id_fakultas')
                    ->join('m_jenis_jabatan', 'm_pejabat_fakultas.jenis_jabatan_id', '=', 'm_jenis_jabatan.id_jenis_jabatan')
                    ->join('m_dosen', 'm_pejabat_fakultas.dosen_id', '=', 'm_dosen.id_dosen')
                    ->where([
                        ['m_pejabat_fakultas.fakultas_id', '=', $id_fakultas],
                        ['m_pejabat_fakultas.status', '=', $status]
                    ])
                    ->orderBy('m_jenis_jabatan.urut_jabatan', 'ASC')
                    ->get();
    }

    static public function getDekan($id_fakultas, $id_jenis_jabatan = '6210cef1-445e-458d-824d-99040613cdef'){
        return self::select('m_dosen.nama_dosen', 'm_dosen.nidn')
                ->join('m_dosen', 'm_dosen.id_dosen', 'm_pejabat_fakultas.dosen_id')
                ->where('m_pejabat_fakultas.jenis_jabatan_id', $id_jenis_jabatan)
                ->where('m_pejabat_fakultas.fakultas_id', $id_fakultas)
                ->where('m_pejabat_fakultas.status', 'A')
                ->first();
    }
}
