<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCitacion extends Model
{
    use HasFactory;

    protected $fillable=['document_id', 'citacion'];

    public function document(){
        return $this->belongsTo(Document::class);
    }
}
