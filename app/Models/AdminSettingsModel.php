<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSettingsModel extends Model
{
    use HasFactory;

    protected $table = 'admin_settings';

    protected $fillable = [
        'currentAcademicYear',
        'setting_category',
        'setting_key',
        'setting_value',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
