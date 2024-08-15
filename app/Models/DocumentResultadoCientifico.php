<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentResultadoCientifico extends Model
{
    use HasFactory;

    protected $fillable=['document_id', 'res_cientifico'];

    public function document(){
        return $this->belongsTo(Document::class);
    }
}
