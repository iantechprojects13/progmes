<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriteriaModel extends Model
{
    use HasFactory;

    protected $table = 'criteria';

    protected $fillable = [
        'cmoId',
        'itemNo',
        'area',
        'minimumRequirement',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function cmo()
    {
        return $this->belongsTo(CMOModel::class, 'cmoId');
    }
}
