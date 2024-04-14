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
        'visitDate',
        'submissionDate',
        'programHead',
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

}
