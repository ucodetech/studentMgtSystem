<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Lecturer extends Authenticatable
{
    use HasFactory;
    public $table = "lecturers";
    protected $guard = "lecturer";

    protected $fillable = [
                        'lecturer_uniqueid',
                        'lecturer_fullname',
                        'lecturer_email',
                        'lecturer_tel',
                        'lecturer_department',
                        'email_verified',
                        'password',
                        'lecturer_photo',
                        'status',
                        'lecturer_last_login',
                        'locked_out',
                        'date_locked_out'
                 ];

    public static function getLecturerById($id){
        return self::where('id', $id)->first();
    }


    /**
     * Get all of the courses for the Lecturer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function course()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get all of the schedule for the Lecturer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedule()
    {
        return $this->hasMany(ClassSchedule::class);
    }
}
