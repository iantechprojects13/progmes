<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramModel extends Model
{
    use HasFactory;

    protected $table = 'program';

    protected $fillable = [
        'disciplineId',
        'program',
        'major',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function discipline()
    {
        return $this->belongsTo(DisciplineModel::class, 'disciplineId', 'id');
    }

    public function cmo()
    {
        return $this->hasMany(CMOModel::class, 'programId', 'id');
    }

    public function institutionProgram()
    {
        return $this->hasMany(InstitutionProgramModel::class, 'programId', 'id');
    }

    public function active_cmo()
    {
        return $this->hasOne(CMOModel::class, 'programId', 'id')->where('isActive', 1);
    }
    
}
