<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;
    public $table = "admins";
    protected $guard = "admin";

    protected $fillable = [
                        'admin_uniqueid',
                        'admin_fullname',
                        'admin_email',
                        'admin_tel',
                        'email_verified',
                        'admin_permission',
                        'password',
                        'admin_photo',
                        'status',
                        'admin_last_login',
                        'locked_out',
                        'date_locked_out'
                 ];
}
