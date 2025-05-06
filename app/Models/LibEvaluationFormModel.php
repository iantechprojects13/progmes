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
        ->where('selfEvaluationStatus', '=', 'Complied')
        ->where(function($query) {
            $query->whereNotNull('actualSituation')
                ->where('actualSituation', '!=', '');
        })
        ->whereHas('evidence', function ($query) {
            $query->whereColumn('itemId', 'lib_evaluation_item.id');
        });
    }

    public function not_complied()
    {
        return $this->hasMany(LibEvaluationItemModel::class, 'libEvaluationFormId', 'id')
        ->where('selfEvaluationStatus', '=', 'Not complied')
        ->where(function($query) {
            $query->whereNotNull('actualSituation')
                ->where('actualSituation', '!=', '');
        });
    }

    public function no_status()
    {
        return $this->hasMany(LibEvaluationItemModel::class, 'libEvaluationFormId', 'id')
        ->where(function($query) {
            $query->where('selfEvaluationStatus', '=', null)
                ->orWhere(function($query) {
                    $query->whereIn('selfEvaluationStatus', ['Complied', 'Not complied'])
                        ->where(function($query) {
                            $query->whereNull('actualSituation')
                                ->orWhere('actualSituation', '=', '');
                        });
                });
        })
        ->orWhere(function($query) {
            $query->where('selfEvaluationStatus', '=', 'Complied')
                ->whereDoesntHave('evidence', function ($query) {
                    $query->whereColumn('itemId', 'lib_evaluation_item.id');
                });
        });
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
