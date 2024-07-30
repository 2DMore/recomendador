<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentIdioma extends Model
{
    use HasFactory;

    protected $fillable=['document_id', 'idioma'];

    public function document(){
        return $this->belongsTo(Document::class);
    }
}
