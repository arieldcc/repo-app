<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class FakultasModel extends Model
{
    use HasFactory;
    protected $table = 'rev_fakultas';

    protected $primaryKey = 'id_fakultas';

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

    static public function getRecord(){
        return self::select('rev_fakultas.*', 'rev_jenjang_pendidikan.nama_jenjang_didik')
                    ->join('rev_jenjang_pendidikan', 'rev_fakultas.jenjang_didik_id', '=', 'rev_jenjang_pendidikan.id_jenjang_didik')
                    ->get();
    }

    static public function getSingle($id){
        return self::find($id);
    }
}
