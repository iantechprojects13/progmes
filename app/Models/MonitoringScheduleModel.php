<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringScheduleModel extends Model
{
    use HasFactory;

    protected $table = 'monitoring_schedule';

    protected $fillable = [
        'institutionId',
        'academicYear',
        'monitoringDate',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function institution()
    {
        return $this->belongsTo(InstitutionModel::class, 'institutionId');
    }

}
