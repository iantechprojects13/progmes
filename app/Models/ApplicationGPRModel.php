<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationGPRModel extends Model
{
    use HasFactory;

    protected $table = 'application_gpr';

    protected $fillable = [
        'no',
        'item',
        'isActive',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}