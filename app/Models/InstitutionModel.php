<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionModel extends Model
{
    use HasFactory;

    protected $table = 'institution';

    protected $fillable = [
        'code',
        'name',
        'address',
        'cityOrMunicipality',
        'cityOrProvince',
        'zipCode',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function institutionProgram()
    {
        return $this->hasMany(InstitutionProgramModel::class, 'institutionId', 'id');
    }
}
