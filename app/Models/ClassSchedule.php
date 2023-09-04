<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'classroom_id',
        'day_of_week',
        'start_time',
        'end_time',
        'lecturer_id',
        'start',
        'attendance'
    ];

public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

public function course()
{
    return $this->belongsTo(Course::class);
}

/**
 * Get the attendance that owns the ClassSchedule
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
public function attendance()
{
    return $this->hasMany(Attendance::class);
}

/**
 * Get all of the lecturer for the ClassSchedule
 *
 * @return \Illuminate\Database\Eloquent\Relations\HasMany
 */
public function lecturer()
{
    return $this->belongsTo(Lecturer::class);
}

}
