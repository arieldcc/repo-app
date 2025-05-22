<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DosenModel extends Model
{
    use HasFactory;
    protected $table = 'm_dosen';

    protected $primaryKey = 'id_dosen';

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

    public static function getRecord(){
        return self::select([
            'nama_dosen',
            'nidn',
            DB::raw("CASE
                        WHEN jenis_kelamin = 'L' THEN 'Laki-Laki'
                        WHEN jenis_kelamin = 'P' THEN 'Perempuan'
                        ELSE jenis_kelamin
                     END AS j_kelamin"),
            'nama_agama',
            'nama_status_aktif',
            'tanggal_lahir'
        ])->get();
    }
}
