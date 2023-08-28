<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;
    protected $fillable = ['class_name', 'class_location'];

    public static function getClassById($id){
        return self::where('id', $id)->first();
    }
}
