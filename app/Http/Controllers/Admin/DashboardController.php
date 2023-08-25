<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
        
    public function adminDashboard(){
        return view('users.Admin.Pages.Dashboard.admin-dashboard');
    }

    public function adminProfile(){
        return view('users.Admin.Pages.Profile.admin-profile');
    }

    public function updateProfilePhoto(Request $request){
        
    }
}
