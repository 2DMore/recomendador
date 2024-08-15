<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentReferenciaDatos extends Model
{
    use HasFactory;

    protected $fillable=['document_id', 'ref_datos'];

    public function document(){
        return $this->belongsTo(Document::class);
    }
}
