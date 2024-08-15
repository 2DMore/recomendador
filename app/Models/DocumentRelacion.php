<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRelacion extends Model
{
    use HasFactory;

    protected $fillable=['document_id', 'relacion'];

    public function document(){
        return $this->belongsTo(Document::class);
    }
}
