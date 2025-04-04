<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibCriteriaModel extends Model
{
    use HasFactory;

    protected $table = 'lib_criteria';

    protected $fillable = [
        'no',
        'area',
        'minimumRequirement',
        'isActive',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
