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
}
