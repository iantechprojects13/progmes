<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationItemModel extends Model
{
    use HasFactory;

    protected $table = 'application_item';

    protected $fillable = [
        'applicationId',
        'gprId',
        'isExisting',
        'remarks',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function gpr()
    {
        return $this->belongsTo(ApplicationGPRModel::class, 'gprId', 'id');
    }

    public function application()
    {
        return $this->belongsTo(EvaluationFormModel::class, 'applicationId', 'id');
    }

    public function support_document()
    {
        return $this->hasMany(SupportDocumentModel::class, 'itemId', 'id');
    }

}
