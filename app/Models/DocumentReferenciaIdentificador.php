<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentReferenciaIdentificador extends Model
{
    use HasFactory;

    protected $fillable=['document_id', 'ref_identificador'];

    public function document(){
        return $this->belongsTo(Document::class);
    }
}
