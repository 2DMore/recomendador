<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentMateria extends Model
{
    use HasFactory;

    protected $fillable=['document_id', 'materia'];

    public function document(){
        return $this->belongsTo(Document::class);
    }
}
