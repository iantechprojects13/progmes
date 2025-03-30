<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportDocumentModel extends Model
{
    use HasFactory;

    protected $table = 'support_document';

    protected $fillable = [
        'itemId',
        'url',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
