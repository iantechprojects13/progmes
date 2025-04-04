<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibEvaluationFormModel extends Model
{
    use HasFactory;

    protected $table = 'lib_evaluation_form';

    protected $fillable = [
        'institutionId',
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

    public function institution()
    {
        return $this->belongsTo(InstitutionModel::class, 'institutionId', 'id');
    }

    public function item()
    {
        return $this->hasMany(LibEvaluationItemModel::class, 'libEvaluationFormId', 'id');
    }

    public function complied()
    {
        return $this->hasMany(LibEvaluationItemModel::class, 'libEvaluationFormId', 'id')
        ->where('selfEvaluationStatus', '=', 'Complied');
    }

    public function not_complied()
    {
        return $this->hasMany(LibEvaluationItemModel::class, 'libEvaluationFormId', 'id')
        ->where('selfEvaluationStatus', '=', 'Not complied');
    }

    public function no_status()
    {
        return $this->hasMany(LibEvaluationItemModel::class, 'libEvaluationFormId', 'id')
        ->where('selfEvaluationStatus', '=', null);
    }

    public function evaluated_complied()
    {
        return $this->hasMany(LibEvaluationItemModel::class, 'libEvaluationFormId', 'id')
        ->where('evaluationStatus', '=', 'Complied');
    }

    public function not_applicable()
    {
        return $this->hasMany(LibEvaluationItemModel::class, 'libEvaluationFormId', 'id')
        ->where('selfEvaluationStatus', '=', 'Not applicable');
    }
    
}
