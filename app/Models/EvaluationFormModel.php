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

    public function totalItems()
    {
        return $this->item()->count();
    }

    // to qualify the item as complied, the selfEvaluationStatus should be complied and the actualSituation should not be null or empty and must have evidence
    public function complied()
    {
        return $this->hasMany(EvaluationItemModel::class, 'evaluationFormId', 'id')
            ->where('selfEvaluationStatus', '=', 'Complied')
            ->where(function($query) {
                $query->whereNotNull('actualSituation')
                    ->where('actualSituation', '!=', '')
                    ->where('actualSituation', '!=', '<p></p>')
                    ->where('actualSituation', '!=', '<p>&nbsp;</p>')
                    ->where('actualSituation', '!=', '<br>')
                    ->where('actualSituation', '!=', '<br/>')
                    ->whereRaw("TRIM(actualSituation) != ''")
                    ->whereRaw("LENGTH(TRIM(REPLACE(REPLACE(actualSituation, '<p></p>', ''), '&nbsp;', ''))) > 0");
            })
            ->whereHas('evidence', function ($query) {
                $query->whereColumn('itemId', 'evaluation_item.id');
            });
    }

    public function not_complied()
    {
        return $this->hasMany(EvaluationItemModel::class, 'evaluationFormId', 'id')
            ->where('selfEvaluationStatus', '=', 'Not complied')
            ->where(function($query) {
                $query->whereNotNull('actualSituation')
                    ->where('actualSituation', '!=', '')
                    ->where('actualSituation', '!=', '<p></p>')
                    ->where('actualSituation', '!=', '<p>&nbsp;</p>')
                    ->where('actualSituation', '!=', '<br>')
                    ->where('actualSituation', '!=', '<br/>')
                    ->whereRaw("TRIM(actualSituation) != ''")
                    ->whereRaw("LENGTH(TRIM(REPLACE(REPLACE(actualSituation, '<p></p>', ''), '&nbsp;', ''))) > 0");
            });
    }

    public function not_applicable()
    {
        return $this->hasMany(EvaluationItemModel::class, 'evaluationFormId', 'id')
        ->where('selfEvaluationStatus', '=', 'Not applicable');
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

    public function evaluated_notComplied()
    {
        return $this->hasMany(EvaluationItemModel::class, 'evaluationFormId', 'id')
        ->where('evaluationStatus', '=', 'Not complied');
    }

    public function evaluated_notApplicable()
    {
        return $this->hasMany(EvaluationItemModel::class, 'evaluationFormId', 'id')
        ->where('evaluationStatus', '=', 'Not applicable');
    }

    public function evaluated_noStatus()
    {
        return $this->hasMany(EvaluationItemModel::class, 'evaluationFormId', 'id')
        ->where('evaluationStatus', '=', null);
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

    public function selfEvaluationProgress()
    {
        $compliedCount = $this->complied()->count();
        $notCompliedCount = $this->not_complied()->count();
        $notApplicable = $this->not_applicable()->count();
        $totalItems = $this->item()->count();
        
        $totalWithInput = $compliedCount + $notCompliedCount + $notApplicable;

        if ($totalItems === 0) {
            return 0;
        }

        return ($totalWithInput / $totalItems) * 100;
    }

    public function evaluationProgress()
    {
        $evaluatedCompliedCount = $this->evaluated_complied()->count();
        $evaluatedNotCompliedCount = $this->evaluated_notComplied()->count();
        $evaluatedNotApplicable = $this->evaluated_notApplicable()->count();
        $totalItems = $this->item()->count();
        
        $totalWithInput = $evaluatedCompliedCount + $evaluatedNotCompliedCount + $evaluatedNotApplicable;

        if ($totalItems === 0) {
            return 0;
        }

        return ($totalWithInput / $totalItems) * 100;
    }

    

}
