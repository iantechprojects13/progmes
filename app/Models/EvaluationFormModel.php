<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EvaluationFormModel extends Model
{
    use HasFactory;

    protected $table = 'evaluation_form';

    protected $fillable = [
        'institutionProgramId',
        'disciplineId',
        'cmoId',
        'effectivity',
        'evaluationDate',
        'submissionDate',
        'archivedDate',
        'conforme1',
        'conforme1Title',
        'conforme2',
        'conforme2Title',
        'evaluatedBy',
        'evaluatedByTitle',
        'reviewedBy',
        'reviewedByTitle',
        'notedBy',
        'notedByTitle',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function institution_program()
    {
        return $this->belongsTo(InstitutionProgramModel::class, 'institutionProgramId', 'id');
    }

    public function discipline()
    {
        return $this->belongsTo(DisciplineModel::class, 'disciplineId', 'id');
    }
    
    public function cmo()
    {
        return $this->belongsTo(CMOModel::class, 'cmoId', 'id');
    }

    public function item()
    {
        return $this->hasMany(EvaluationItemModel::class, 'evaluationFormId', 'id');
    }

    public function complied()
    {
        return $this->hasMany(EvaluationItemModel::class, 'evaluationFormId', 'id')
        ->where('selfEvaluationStatus', '=', 'Complied');
    }

    public function not_complied()
    {
        return $this->hasMany(EvaluationItemModel::class, 'evaluationFormId', 'id')
        ->where('selfEvaluationStatus', '=', 'Not complied');
    }

    public function no_status()
    {
        return $this->hasMany(EvaluationItemModel::class, 'evaluationFormId', 'id')
        ->where('selfEvaluationStatus', '=', null);
    }

    public function evaluated_complied()
    {
        return $this->hasMany(EvaluationItemModel::class, 'evaluationFormId', 'id')
        ->where('evaluationStatus', '=', 'Complied');
    }

    public function not_applicable()
    {
        return $this->hasMany(EvaluationItemModel::class, 'evaluationFormId', 'id')
        ->where('selfEvaluationStatus', '=', 'Not applicable');
    }

    public function compliancePercentage()
    {
        $compliedCount = $this->complied()->count();
        $notCompliedCount = $this->not_complied()->count();

        $total = $compliedCount + $notCompliedCount;

        if ($total === 0) {
            return 0;
        }

        return ($compliedCount / $total) * 100;
    }

}
