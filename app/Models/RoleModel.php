<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;

    protected $table = 'role';

    protected $fillable = [
        'userId',
        'institutionId',
        'disciplineId',
        'programId',
        'role',
        'isActive',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function institution()
    {
        return $this->belongsTo(InstitutionModel::class, 'institutionId', 'id')->select(['id', 'name']);
    }

    public function discipline()
    {
        return $this->belongsTo(DisciplineModel::class, 'disciplineId', 'id')->select(['id', 'discipline']);
    }

    public function program()
    {
        return $this->belongsTo(ProgramModel::class, 'programId', 'id')->select(['id', 'program']);
    }

    public function tool()
    {
        return $this->hasMany(EvaluationFormModel::class, 'disciplineId', 'disciplineId');
    }
    
}
