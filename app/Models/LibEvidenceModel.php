<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibEvidenceModel extends Model
{
    use HasFactory;

    protected $table = 'lib_evidence';

    protected $fillable = [
        'itemId',
        'type',
        'text',
        'url',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function item()
    {
        return $this->belongsTo(LibEvaluationItemModel::class, 'itemId', 'id');
    }
}
