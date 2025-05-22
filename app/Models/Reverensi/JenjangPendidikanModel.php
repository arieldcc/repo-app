<?php

namespace App\Models\Reverensi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenjangPendidikanModel extends Model
{
    use HasFactory;
    protected $table = 'rev_jenjang_pendidikan';

    protected $primaryKey = 'id_jenjang_didik';

    static public function getRecord(){
        return self::get();
    }
}
