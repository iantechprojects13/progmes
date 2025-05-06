<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionProgramModel extends Model
{
    use HasFactory;

    protected $table = 'institution_program';

    protected $fillable = [
        'institutionId',
        'disciplineId',
        'programId',
        'isActive',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    
    public function institution()
    {
        return $this->belongsTo(InstitutionModel::class, 'institutionId', 'id')->select(['id', 'name'])->orderBy('name', 'asc');
    }

    public function discipline()
    {
        return $this->belongsTo(DisciplineModel::class, 'disciplineId', 'id')->select(['id', 'discipline']);
    }

    public function program()
    {
        return $this->belongsTo(ProgramModel::class, 'programId', 'id')->select(['id', 'disciplineId', 'program', 'major']);
    }

    public function evaluationForm()
    {
        return $this->hasMany(EvaluationFormModel::class, 'institutionProgramId', 'id');
    }

    public function monitoringSchedule()
    {
        return $this->hasMany(MonitoringScheduleModel::class, 'institutionId', 'institutionId');
    }
}
