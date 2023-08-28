<?php

namespace App\Helpers;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class GeneralHelper {
    
    public static function updateLastLogin($model, $id, $field){
       return $model::where('id', $id)->update([$field=>Carbon::now()]);
    }


  
}