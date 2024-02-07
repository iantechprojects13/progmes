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
        'cmoId',
        'effectivity',
        'evaluationDate',
        'programHead',
        'evaluator',
        'conforme',
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

    public function cmo()
    {
        return $this->belongsTo(CMOModel::class, 'cmoId', 'id');
    }

    public function item()
    {
        return $this->hasMany(EvaluationItemModel::class, 'evaluationFormId', 'id');
    }

    

}
