<?php

namespace App\Models\Konfigurasi\Repo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SliderModel extends Model
{
    use HasFactory;
    protected $table = 'repo_sliders';

    public static function getRecord(){
        return self::get();
    }

    public static function getSingle($id){
        return self::find($id);
    }
}
