<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentResumen extends Model
{
    use HasFactory;

    protected $fillable=['document_id', 'resumen'];

    public function document(){
        return $this->belongsTo(Document::class);
    }
}
