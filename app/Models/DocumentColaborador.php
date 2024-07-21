<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentColaborador extends Model
{
    use HasFactory;

    protected $fillable=['document_id', 'contributor','contributor_id', 'contributor_id_type'];

    public function document(){
        return $this->belongsTo(Document::class);
    }
}
