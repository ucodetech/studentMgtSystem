<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OngoingClass extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
                'user_id',
                'schedule_id',
                'created_at',
    ];

    /**
     * Get all of the users for the OngoingClass
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    /**
     * Get all of the classschedules for the OngoingClass
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classschedules()
    {
        return $this->hasMany(ClassSchedules::class);
    }

}
