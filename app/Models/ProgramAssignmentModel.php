<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramAssignmentModel extends Model
{
    use HasFactory;

    protected $table = 'program_assignment';

    protected $fillable = [
        'userId',
        'programId',
    ];
    
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function program()
    {
        return $this->belongsTo(ProgramModel::class, 'programId', 'id');
    }
}
