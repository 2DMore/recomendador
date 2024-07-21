<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentEditor extends Model
{
    use HasFactory;

    protected $fillable=['document_id', 'editor'];

    public function document(){
        return $this->belongsTo(Document::class);
    }
}
