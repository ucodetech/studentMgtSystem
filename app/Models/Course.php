<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
                    'course_title',
                    'course_code',
                    'level',
                    'credit',
                    'semester',
                    'lecturer_id',
    ];

    /**
     * Get the lecturer that owns the Course
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_id');
    }

   public static function getCourseById($id){
    return self::where('id', $id)->first();
   }
   public function classschedules()
   {
       return $this->hasMany(ClassSchedule::class);
   }
   
}
