<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentFormato extends Model
{
    use HasFactory;

    protected $fillable=['document_id', 'format'];

    public function document(){
        return $this->belongsTo(Document::class);
    }
}
