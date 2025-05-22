<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class ProdiModel extends Model
{
    use HasFactory;
    // mematikan auto increment
    public $incrementing = false;

    // tipe data kunci utama adalah string
    protected $keyType = 'string';

    protected $table = 'rev_prodi';

    protected $primaryKey = 'id_prodi';

    protected static function boot()
    {
        parent::boot();
        // generate UUID pada saat model sedang dibuat
        static::creating(function($model){
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    static public function getRecord(){
        return self::get();
    }

    static public function getProdiFakultas($id){
        return self::where('fakultas_id', $id)->get();
    }

    static public function getProdiperFakultas($id){
        return self::where('fakultas_id', $id)->pluck('id_prodi')->toArray();
    }

    static public function getSingle($id){
        return self::find($id);
    }
}
