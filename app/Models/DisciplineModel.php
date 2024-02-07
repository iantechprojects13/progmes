<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplineModel extends Model
{
    use HasFactory;

    protected $table = 'discipline';

    protected $fillable = [
        'discipline',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
