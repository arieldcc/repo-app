<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class JenisJabatanModel extends Model
{
    use HasFactory;
    // mematikan auto increment
    public $incrementing = false;

    // tipe data kunci utama adalah string
    protected $keyType = 'string';

    protected $table = 'm_jenis_jabatan';

    protected $primaryKey = 'id_jenis_jabatan';

    protected static function boot()
    {
        parent::boot();
        // generate UUID pada saat model sedang dibuat
        static::creating(function($model){
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    static public function getRecord() {
        return self::select('m_jenis_jabatan.*', 'rev_jenis_sms.nama_jenis_sms')
                ->join('rev_jenis_sms', 'rev_jenis_sms.id_jenis_sms', '=', 'm_jenis_jabatan.jenis_sms_id')
                ->orderBy('m_jenis_jabatan.urut_jabatan', 'Asc')
                ->get();
    }

    static public function getSingle($id){
        return self::find($id);
    }

    static public function getRecordPerSMS_ID($jenis_sms_id){
        // 1 = Fakultas
        return self::select('m_jenis_jabatan.*')
                ->where('jenis_sms_id','=',$jenis_sms_id)
                ->orderBy('m_jenis_jabatan.urut_jabatan', 'Asc')
                ->get();
    }
}
