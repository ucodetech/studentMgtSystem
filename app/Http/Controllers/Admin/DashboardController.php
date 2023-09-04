<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EmailHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Helpers\GeneralHelper;
use App\Models\ClassSchedule;
use App\Models\Lecturer;
use App\Models\User;
use App\Models\VerifyLecturerEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class DashboardController extends Controller
{
    
        
    public function adminDashboard(){
        return view('users.Admin.Pages.Dashboard.admin-dashboard');
    }

    public function adminProfile(){
        return view('users.Admin.Pages.Profile.admin-profile');
    }

    public function updateProfilePhoto(Request $request){
            $validator = Validator::make($request->all(), [
                            'admin_photo' => 'required|mimes:png,jpg,jpeg'
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                if($request->hasFile('admin_photo')){
                    $file = $request->admin_photo;
                    $filenewname = admin()->admin_uniqueid . '.' . $file->extension();
                    $folder = "uploads/adminProfile";
                   if($file->storeAs($folder, $filenewname))
                        Admin::where('id', admin()->id)->update(['admin_photo'=>$filenewname]);
                        return redirect()->route('admin.ad.profile')->with('success', "Photo updated successfully");
                }
            }
    }

    public function updateAdminDetails(Request $request){
        $validator = Validator::make($request->all(),[
                    'admin_fullname' => 'required',
                    'admin_tel' => 'required|unique:admins,admin_tel,'.admin()->id
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            Admin::where('id', admin()->id)->update([
                    'admin_fullname' => $request->admin_fullname,
                    'admin_tel' => $request->admin_tel
            ]);
            return redirect()->route('admin.ad.profile')->with('info', 'Details updated successfully!');
        }
    }

    public function updateAdminPassword(Request $request){
        $validator = Validator::make($request->all(),[
            'current_password' => 'required',
            'new_password' => 'required|min:10',
            'verify_new_password' => 'required|same:new_password'
            ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
         else{
            $current_password = $request->current_password;
            $hashed_password = admin()->password;
            if(!Hash::check($current_password, $hashed_password)){
                return redirect()->back()->with("fail", "Current password is wrong!")->withInput();
            }
            if(Hash::check($request->new_password,$hashed_password)){
                return redirect()->back()->with("fail", "The new password can not be the same with the current password!")->withInput();
            }
            Admin::where('id', admin()->id)->update([
                    'password' => Hash::make($request->new_password),
                    'updated_at' => Carbon::now()
            ]);
            Auth::guard('admin')->logout();
            return redirect()->route('admin.ad.login')->with('info', 'Your password was updated! please login with your new password!');
         }
    }


    public function Superusers(){
        $superusers = Admin::all();
        return view('users.Admin.Pages.Superusers.admin-superusers', ['superusers'=>$superusers]);
    }

    public function toggleStatus(Request $request){
        $mode = $request->mode;
        $id = $request->id;

        if($mode == 'inactive'){
            $locked = 1;
            $datelocked = Carbon::now();
        }else{
            $locked = 0;
            $datelocked = null;
        }

        Admin::where('id', $id)->update([
            'status'=> $mode,
            'locked_out' => $locked,
            'date_locked_out' =>  $datelocked
        ]);
        return true;
       
    }

    public function adminDetails(Request $request){
        $id = $request->id;
        $admin = Admin::getAdminById($id);
        $data = "";

        $data.='<div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0"> '. userFirstName($admin->admin_fullname) ."'s" .' Profile Details</h5>
                </div>
                <div class="card-body text-center">
                    <div id="previewPhoto">
                        <label for="admin_photo" class="cursor-pointer" title="Click to select new photo">
                        <img src="'. asset('storage/uploads/adminProfile/'. $admin->admin_photo) .'" alt="'.userFirstName($admin->admin_fullname) .'" class="img-fluid rounded-circle mb-2" width="128" height="128" />
                        </label>
                    </div>
                      
                    <h5 class="card-title mb-0">'. $admin->admin_fullname .'</h5>
                    <div class="text-muted mb-2">'. ucfirst($admin->admin_permission) .'</div>
                  
                </div>
               
                <hr class="my-0" />
                <div class="card-body">
                    <h5 class="h6 card-title">Social Handles</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1"><a href="#">staciehall.co</a></li>
                        <li class="mb-1"><a href="#">Twitter</a></li>
                        <li class="mb-1"><a href="#">Facebook</a></li>
                        <li class="mb-1"><a href="#">Instagram</a></li>
                        <li class="mb-1"><a href="#">LinkedIn</a></li>
                    </ul>
                </div>
            </div>
        </div>
    
        <div class="col-md-8 col-xl-9">
            <div class="card">
                <div class="card-header">
    
                    <h5 class="card-title mb-0">Details  <span class="badge text-bg-info badge-btn">'.$admin->admin_uniqueid.'</span></h5>
                </div>
                <div class="card-body h-100">
                        <div class="container mt-3">
                            
                                <div class="mb-3 row">
                                    <label for="admin_fullname" class="col-4 col-form-label">Name</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control" name="admin_fullname" id="admin_fullname" placeholder="fullname" value="'.$admin->admin_fullname.'">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="admin_email" class="col-4 col-form-label">Email</label>
                                    <div class="col-8">
                                        <input type="email" class="form-control" name="admin_email" id="admin_email" placeholder="email" value="'.$admin->admin_email.'" disabled>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="admin_tel" class="col-4 col-form-label">Phone Number</label>
                                    <div class="col-8">
                                        <input type="tel" class="form-control" name="admin_tel" id="admin_tel" placeholder="tel phone" value="'.$admin->admin_tel.'">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                <label for="admin_permission" class="col-4 col-form-label">Permission</label>
                                <div class="col-8">
                                    <input type="tel" class="form-control" name="admin_permission" id="admin_permission" placeholder="tel phone" value="'.$admin->admin_permission.'">
                                </div>
                            </div>  
                            <div class="mb-3 row">
                            <label for="status" class="col-4 col-form-label">Status</label>
                            <div class="col-8">
                                <input type="tel" class="form-control" name="status" id="status" placeholder="tel phone" value="'.$admin->status.'">
                            </div>
                        </div>                    
                        </div>
                        Date Created: '.pretty_dates($admin->created_at).' <br/>
                        
                        Last Login: '.timeAgo($admin->admin_last_login).'
                </div>
            </div>
        </div>
    </div>';

    return $data;
    }


    //update last login

    public function updateAdminLastLogin(){
        $id = admin()->id;
        GeneralHelper::updateLastLogin(Admin::class,$id, "admin_last_login");
        return true;
    }

    public function deleteAdmin(Request $request){
        $id = $request->id;
        Admin::where('id', $id)->delete();
        return "Admin Deleted!";
    }

    //lecturers

    public function generatePassword(){
        return Str::random(10);
    }

    public function Lecturers(){
        $lecturers = Lecturer::all();
        $departments = DB::table('department')->orderBy('department_name', 'asc')->get();
        return view('users.Admin.Pages.Lecturers.admin-lecturers', ['lecturers'=>$lecturers, 'departments'=>$departments]);
    }

    public function processLecturerRegister(Request $request){
        $validator = Validator::make($request->all(), [
                    'lecturer_fullname' => 'required',
                    'lecturer_email' => 'required|email|unique:lecturers,lecturer_email',
                    'lecturer_tel' => 'required|unique:lecturers,lecturer_tel',
                    'lecturer_department' => 'required',
                    'password' => 'required|min:10',
                   
        ]);

        if(!$validator->passes()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $uniqueid = Str::random(10);
            $lecturer = new Lecturer();
            $lecturer->lecturer_fullname = $request->lecturer_fullname;
            $lecturer->lecturer_email = $request->lecturer_email;
            $lecturer->lecturer_tel = $request->lecturer_tel;
            $lecturer->lecturer_department = $request->lecturer_department;
            $lecturer->status = "inactive";
            $lecturer->password = Hash::make($request->password);
            $lecturer->created_at = Carbon::now();
            $lecturer->lecturer_uniqueid = $uniqueid;
            
            if($lecturer->save()){
                $uniqueid =  $lecturer->lecturer_uniqueid;
                $token = hash('sha256', Str::random(120));
                $link = route('lecturer.lect.verify.email', ['token'=>$token, 'hash'=>$uniqueid,'Service'=>'Email-Verification']);

                VerifyLecturerEmail::create([
                    'lecturer_uniqueid' => $uniqueid,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
                $message = "Hi " . $lecturer->lecturer_fullname;
                $message .="You have been added to CSAS Lecturers panel, please fellow the intsruction below to verify your email address!";
                $message .="<p>Your username is your email address: ".$lecturer->lecturer_email." <br> and your password is: ".$request->password."</p>";
                $message .="<p>Please change your password immediately you get access to your dashboard!</p>";
                $mail_data = [
                        'from' => 'gtechnoproject22@gmail.com',
                        'to' => $lecturer->lecturer_email,
                        'toName' => $lecturer->lecturer_fullname,
                        'subject' => 'Email Verification',
                        'body' => $message,
                        'actionLink' => $link,
                        'actionLinkText' => "Verify Email"
                ];
                EmailHelper::GsendMail($mail_data);
                return redirect()->route('admin.ad.lecturers')->with("info", "Lecturer Added Successfully!")->withInput();
            }
        }
    }

    public function toggleLecturerStatus(Request $request){
        $mode = $request->mode;
        $id = $request->id;

        if($mode == 'inactive'){
            $locked = 1;
            $datelocked = Carbon::now();
        }else{
            $locked = 0;
            $datelocked = null;
        }

        Lecturer::where('id', $id)->update([
            'status'=> $mode,
            'locked_out' => $locked,
            'date_locked_out' =>  $datelocked
        ]);
        return true;
       
    }

    public function lecturerDetails(Request $request){
        $id = $request->id;
        $lecturer = Lecturer::getLecturerById($id);
        $data = "";

        $data.='<div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0"> '. userFirstName($lecturer->lecturer_fullname) ."'s" .' Profile Details</h5>
                </div>
                <div class="card-body text-center">
                    <div id="previewPhoto">
                        <label for="lecturer_photo" class="cursor-pointer">
                        <img src="'. asset('storage/uploads/lecturerProfile/'. $lecturer->lecturer_photo) .'" alt="'.userFirstName($lecturer->lecturer_fullname) .'" class="img-fluid rounded-circle mb-2" width="128" height="128" />
                        </label>
                    </div>
                      
                    <h5 class="card-title mb-0">'. $lecturer->lecturer_fullname .'</h5>
                    <div class="text-muted mb-2">Lecturer</div>
                  
                </div>
               
                <hr class="my-0" />
                <div class="card-body">
                    <h5 class="h6 card-title">Social Handles</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1"><a href="#">staciehall.co</a></li>
                        <li class="mb-1"><a href="#">Twitter</a></li>
                        <li class="mb-1"><a href="#">Facebook</a></li>
                        <li class="mb-1"><a href="#">Instagram</a></li>
                        <li class="mb-1"><a href="#">LinkedIn</a></li>
                    </ul>
                </div>
            </div>
        </div>
    
        <div class="col-md-8 col-xl-9">
            <div class="card">
                <div class="card-header">
    
                    <h5 class="card-title mb-0">Details  <span class="badge text-bg-info badge-btn">'.$lecturer->lecturer_uniqueid.'</span></h5>
                </div>
                <div class="card-body h-100">
                        <div class="container mt-3">
                            
                                <div class="mb-3 row">
                                    <label for="lecturer_fullname" class="col-4 col-form-label">Name</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control" name="lecturer_fullname" id="lecturer_fullname" placeholder="fullname" value="'.$lecturer->lecturer_fullname.'">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="lecturer_email" class="col-4 col-form-label">Email</label>
                                    <div class="col-8">
                                        <input type="email" class="form-control" name="lecturer_email" id="lecturer_email" placeholder="email" value="'.$lecturer->lecturer_email.'" disabled>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="lecturer_tel" class="col-4 col-form-label">Phone Number</label>
                                    <div class="col-8">
                                        <input type="tel" class="form-control" name="lecturer_tel" id="lecturer_tel" placeholder="tel phone" value="'.$lecturer->lecturer_tel.'">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                <label for="lecturer_department" class="col-4 col-form-label">Department</label>
                                <div class="col-8">
                                    <input type="tel" class="form-control" name="lecturer_department" id="lecturer_department" placeholder="tel phone" value="'.$lecturer->lecturer_department.'">
                                </div>
                            </div>  
                            <div class="mb-3 row">
                            <label for="status" class="col-4 col-form-label">Status</label>
                            <div class="col-8">
                                <input type="tel" class="form-control" name="status" id="status" placeholder="tel phone" value="'.$lecturer->status.'">
                            </div>
                        </div>                    
                        </div>
                        Date Created: '.pretty_dates($lecturer->created_at).' <br/>
                        
                        Last Login: '.timeAgo($lecturer->lecturer_last_login).'
                </div>
            </div>
        </div>
        </div>';

        return $data;
    }


 
    public function deleteLecturer(Request $request){
        $id = $request->id;
        Lecturer::where('id', $id)->delete();
        return "Lecturer Deleted!";
    }


    public function getRealTimeData(){
        $schedule_count = ClassSchedule::all()->count();
        $schedule_active_count = ClassSchedule::where('status', 'open')->count();
        $schedule_closed_count = ClassSchedule::where('status', 'closed')->count();
        $students_count = User::all()->count();

        $response['schedule_count'] = $schedule_count;
        $response['schedule_active_count'] = $schedule_active_count;
        $response['schedule_closed_count'] = $schedule_closed_count;
        $response['students_count'] = $students_count;

        return response()->json($response);


    }
//students
    public function Students(){
        $students = User::all();
        $departments = DB::table('department')->orderBy('department_name', 'asc')->get();
        return view('users.Admin.Pages.Students.admin-students', ['students'=>$students, 'departments'=>$departments]);
    }

    public function toggleStudentStatus(Request $request){
        $mode = $request->mode;
        $id = $request->id;

        if($mode == 'inactive'){
            $locked = 1;
            $datelocked = Carbon::now();
            $email_verified = 0;
        }else{
            $locked = 0;
            $datelocked = null;
            $email_verified = 1;
        }

        User::where('id', $id)->update([
            'status'=> $mode,
            'locked_out' => $locked,
            'date_locked_out' =>  $datelocked,
            'email_verified' => $email_verified
        ]);
        return true;
       
    }

    public function studentDetails(Request $request){
        $id = $request->id;
        $student = User::getUserById($id);
        $data = "";

        $data.='<div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0"> '. userFirstName($student->name) ."'s" .' Profile Details</h5>
                </div>
                <div class="card-body text-center">
                    <div id="previewPhoto">
                        <label for="photo" class="cursor-pointer">
                        <img src="'. asset('storage/uploads/userProfile/'. $student->photo) .'" alt="'.userFirstName($student->name) .'" class="img-fluid rounded-circle mb-2" width="128" height="128" />
                        </label>
                    </div>
                      
                    <h5 class="card-title mb-0">'. $student->name .'</h5>
                    <div class="text-muted mb-2">Student</div>
                  
                </div>
               
                <hr class="my-0" />
                <div class="card-body">
                    <h5 class="h6 card-title">Social Handles</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1"><a href="#">staciehall.co</a></li>
                        <li class="mb-1"><a href="#">Twitter</a></li>
                        <li class="mb-1"><a href="#">Facebook</a></li>
                        <li class="mb-1"><a href="#">Instagram</a></li>
                        <li class="mb-1"><a href="#">LinkedIn</a></li>
                    </ul>
                </div>
            </div>
        </div>
    
        <div class="col-md-8 col-xl-9">
            <div class="card">
                <div class="card-header">
    
                    <h5 class="card-title mb-0">Details  <span class="badge text-bg-info badge-btn">'.$student->uniqueid.'</span></h5>
                </div>
                <div class="card-body h-100">
                        <div class="container mt-3">
                            
                                <div class="mb-3 row">
                                    <label for="fullname" class="col-4 col-form-label">Name</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control" name="fullname" id="fullname" placeholder="fullname" value="'.$student->name.'">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="email" class="col-4 col-form-label">Email</label>
                                    <div class="col-8">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="email" value="'.$student->email.'" disabled>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="tel" class="col-4 col-form-label">Phone Number</label>
                                    <div class="col-8">
                                        <input type="tel" class="form-control" name="tel" id="tel" placeholder="tel phone" value="'.$student->phone_no.'">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                <label for="department" class="col-4 col-form-label">Department</label>
                                <div class="col-8">
                                    <input type="tel" class="form-control" name="department" id="department" placeholder="tel phone" value="'.$student->department.'">
                                </div>
                            </div>  
                            <div class="mb-3 row">
                            <label for="department" class="col-4 col-form-label">Level</label>
                            <div class="col-8">
                                <input type="tel" class="form-control" name="department" id="department" placeholder="tel phone" value="'.$student->level.'">
                            </div>
                        </div>  
                        <div class="mb-3 row">
                        <label for="department" class="col-4 col-form-label">Matric No</label>
                            <div class="col-8">
                                <input type="tel" class="form-control" name="department" id="department" placeholder="tel phone" value="'.$student->matric_no.'">
                            </div>
                         </div>  
                            <div class="mb-3 row">
                            <label for="status" class="col-4 col-form-label">Status</label>
                            <div class="col-8">
                                <input type="tel" class="form-control" name="status" id="status" placeholder="tel phone" value="'.$student->status.'">
                            </div>
                        </div>                    
                        </div>
                        Date Created: '.pretty_dates($student->created_at).' <br/>
                        
                        Last Login: '.timeAgo($student->last_login).'
                </div>
            </div>
        </div>
        </div>';

        return $data;
    }


 
    public function deleteStudent(Request $request){
        $id = $request->id;
        Lecturer::where('id', $id)->delete();
        return "Lecturer Deleted!";
    }







}
