<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMOModel extends Model
{
    use HasFactory;

    protected $table = 'cmo';

    protected $fillable = [
        'disciplineId',
        'programId',
        'number',
        'series',
        'version',
        'status',
        'isActive',
    ];
    
    // protected $hidden = [
    //     'created_at',
    //     'updated_at',
    // ];

    public function discipline()
    {
        return $this->belongsTo(DisciplineModel::class, 'disciplineId', 'id');
    }

    public function program()
    {
        return $this->belongsTo(ProgramModel::class, 'programId', 'id');
    }

    public function criteria()
    {
        return $this->hasMany(CriteriaModel::class, 'cmoId', 'id');
    }
}
