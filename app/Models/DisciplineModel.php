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

    public function program()
    {
        return $this->hasMany(ProgramModel::class, 'disciplineId', 'id');
    }

    public function tool()
    {
        return $this->hasMany(EvaluationFormModel::class, 'disciplineId', 'id');
    }

    public function cmo()
    {
        return $this->hasMany(CMOModel::class, 'disciplineId', 'id');
    }
    
    public function institution_program()
    {
        return $this->hasManyThrough(InstitutionProgramModel::class, ProgramModel::class, 'disciplineId', 'programId', 'id', 'id');
    }

    public function role()
    {
        return $this->hasMany(RoleModel::class, 'disciplineId', 'id');
    }
}
