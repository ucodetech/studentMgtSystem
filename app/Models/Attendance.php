<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'attendance_date',
        'is_present',
        'status',
        'schedule_id'
    ];

    /**
     * Get all of the classschedules for the Attendance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'student_id');
    }
}
