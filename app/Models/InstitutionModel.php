<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionModel extends Model
{
    use HasFactory;

    protected $table = 'institution';
    
    protected $fillable = [
        'code',
        'name',
        'isActive',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    public function institution_program()
    {
        return $this->hasMany(InstitutionProgramModel::class, 'institutionId', 'id');
    }

    public function active_program()
    {
        return $this->hasMany(InstitutionProgramModel::class, 'institutionId', 'id')->where('isActive', 1);
    }

    public function role()
    {
        return $this->hasMany(RoleModel::class, 'institutionId', 'id');
    }

    public function evaluationForm()
    {
        return $this->hasMany(LibEvaluationFormModel::class, 'institutionId', 'id');
    }

    public function monitoringSchedule()
    {
        return $this->hasMany(MonitoringScheduleModel::class, 'institutionId', 'id');
    }
}
