<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyAdminEmail extends Model
{
    use HasFactory;
    protected $fillable = ['admin_uniqueid', 'token'];
}
