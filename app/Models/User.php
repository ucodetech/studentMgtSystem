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
                'uniqueid',
                'photo',
                'name',
                'email',
                'phone_no',
                'department',
                'level',
                'matric_no',
                'email_verified',
                'last_login',
                'status',
                'password',
                'locked_out',
                'date_locked_out'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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


    /**
     * Get the ongoingclass that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ongoingclass()
    {
        return $this->hasOne(OngingClass::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public static function getUserById($id){
        return self::where('id', $id)->first();
    }

}
