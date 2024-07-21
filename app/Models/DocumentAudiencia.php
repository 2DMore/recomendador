<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentAudiencia extends Model
{
    use HasFactory;

    protected $fillable=['document_id', 'audiencia'];

    public function document(){
        return $this->belongsTo(Document::class);
    }
}
