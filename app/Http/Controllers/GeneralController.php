<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralController extends Controller
{
    //
    public function lockedOut(){
        return view('users.General.locked-out');
    }

    public function resentToken(){
        return view('users.General.resent-token');
    }
}
