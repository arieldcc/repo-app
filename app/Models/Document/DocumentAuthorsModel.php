<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentAuthorsModel extends Model
{
    use HasFactory;
    protected $table = 'documentauthors';

    // protected $primaryKey = 'document_id';

    public static function getRecordAuthors($documentId){
        return self::select([
                                'documentauthors.id',
                                'documentauthors.document_id',
                                'documentauthors.author_name',
                                'documentauthors.author_email',
                                'documentauthors.author_affiliation'
                            ])
                            ->orderBy('documentauthors.document_id', 'ASC');
    }

    public static function getDetailAuthors($id){
        return self::select([
                                'documentauthors.id',
                                'documentauthors.document_id',
                                'documentauthors.author_name',
                                'documentauthors.author_email',
                                'documentauthors.author_affiliation'
                            ])
                            ->where('documentauthors.document_id', $id);
    }
}
