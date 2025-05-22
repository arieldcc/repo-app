<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class LogRequestModel extends Model
{
    use HasFactory;
    protected $table = 'log_requests';

    protected $primaryKey = 'log_id';

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
}
