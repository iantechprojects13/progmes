<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibEvaluationItemModel extends Model
{
    use HasFactory;

    protected $table = 'lib_evaluation_item';

    protected $fillable = [
        'libEvaluationFormId',
        'libCriteriaId',
        'actualSituation',
        'findings',
        'recommendations',
        'selfEvaluationStatus',
        'evaluationStatus',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function evaluationForm()
    {
        return $this->belongsTo(LibEvaluationFormModel::class, 'libEvaluationFormId', 'id');
    }

    public function criteria()
    {
        return $this->belongsTo(LibCriteriaModel::class, 'libCriteriaId', 'id');
    }

    public function evidence()
    {
        return $this->hasMany(LibEvidenceModel::class, 'itemId', 'id');
    }
}
