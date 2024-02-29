<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationItemModel extends Model
{
    use HasFactory;

    protected $table = 'evaluation_item';

    protected $fillable = [
        'evaluationFormId',
        'criteriaId',
        'actualSituation',
        'findings',
        'recommendations',
        'selfEvaluationStatus',
        'evaluationStatus',
        'evidenceId',
        'evidenceLink',
        'canUploadEvidence',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function evaluationForm()
    {
        return $this->belongsTo(EvaluationFormModel::class, 'evaluationFormId', 'id');
    }

    public function criteria()
    {
        return $this->belongsTo(CriteriaModel::class, 'criteriaId', 'id');
    }

    public function evidence()
    {
        return $this->hasMany(EvidenceModel::class, 'itemId', 'id');
    }
}
