<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationFormModel extends Model
{
    use HasFactory;

    protected $table = 'application_form';

    protected $fillable = [
        'institutionId',
        'programId',
        'type',
        'academicYear',
        'status',
        'isApproved',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    

    public function item()
    {
        return $this->hasMany(ApplicationItemModel::class, 'applicationId', 'id');
    }

    public function institution()
    {
        return $this->belongsTo(InstitutionModel::class, 'institutionId', 'id');
    }

    public function program()
    {
        return $this->belongsTo(ProgramModel::class, 'programId', 'id');
    }

}
