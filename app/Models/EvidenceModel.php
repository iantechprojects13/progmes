<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvidenceModel extends Model
{
    use HasFactory;

    protected $table = 'evidence';

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
        return $this->belongsTo(EvaluationItemModel::class, 'itemId', 'id');
    }
}
