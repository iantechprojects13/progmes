<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'password',
        'role',
        'isVerified',
        'isActive',
        'type',
        'avatar',
    ];
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function userRole()
    {
        return $this->hasMany(RoleModel::class, 'userId', 'id');
    }

    public function activeRole()
    {
        return $this->hasMany(RoleModel::class, 'userId', 'id')->where('isActive', 1);
    }
    
    public function roleRequest()
    {
        return $this->hasMany(RoleModel::class, 'userId', 'id')->where('isActive', null);
    }

    public function discipline()
    {
        return $this->hasManyThrough(DisciplineModel::class, RoleModel::class, 'disciplineId', 'id', 'disciplineId', 'id');
    }

    public function program()
    {
        return $this->hasManyThrough(ProgramModel::class, ProgramAssignmentModel::class, 'userId', 'id', 'id', 'programId');
    }

    public static function requestCount($role, $userId)
    {
        $query = self::query()
            ->whereNull('isVerified')
            ->whereNull('isActive')
            ->whereNot('role', 'Super Admin');
        // dd('sasdasd');
        if ($role === 'Education Supervisor') {
            $disciplineIds = RoleModel::where('userId', $userId)
                ->where('isActive', 1)
                ->pluck('disciplineId')
                ->toArray();
            $programIds = ProgramAssignmentModel::where('userId', $userId)
                ->pluck('programId')
                ->toArray();
            
            $query->where(function ($query) use ($disciplineIds, $programIds) {
                $query->whereHas('userRole', function ($q) use ($disciplineIds, $programIds) {
                    if (!empty($programIds)) {
                        $q->whereHas('program', function ($subQ) use ($programIds) {
                            $subQ->whereIn('programId', $programIds);
                        });
                    }
                    
                    if (!empty($disciplineIds)) {
                        $q->orWhereHas('discipline', function ($subQ) use ($disciplineIds) {
                            $subQ->whereIn('disciplineId', $disciplineIds);
                        });
                    }
                });
            });

            $query->with('userRole');
        }
        
        return $query->count();
    }

}
