<?php

namespace App\Http\Controllers\User;

use App\Helpers\GeneralHelper;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassSchedule;
use App\Models\Course;
use App\Models\OngoingClass;
use App\Models\User;
use Carbon\Carbon;
use Google\Service\Classroom\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserDashboardController extends Controller
{
    public function studentDashboard(){
        $courses = Course::where('level', student()->level)->get();
        $schedules = ClassSchedule::where('level', student()->level)->orderBy('day_of_week', 'desc')->get();

        $running = ClassSchedule::where(['level'=>student()->level,'start'=>1])->first();
        if($running){
            $onging = OngoingClass::where('user_id', student()->id)->where('schedule_id', $running->id)->first();
            $attended = Attendance::where('schedule_id', $running->id)->where('student_id', student()->id)->first();
        }else{
            $onging = null;
            $attended = null;
        }
        

       
        return view('users.Student.Pages.Dashboard.student-dashboard',
                    [
                        'courses' => $courses,
                        'schedules' => $schedules,
                        'running' => $running,
                        'onging' => $onging,
                        'attended' => $attended
                        
                    ]);
    }

    public function studentProfile(){
        return view('users.Student.Pages.Profile.student-profile');
    }

    public function updateProfilePhoto(Request $request){
            $validator = Validator::make($request->all(), [
                            'photo' => 'required|mimes:png,jpg,jpeg'
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                if($request->hasFile('photo')){
                    $file = $request->photo;
                    $filenewname = student()->uniqueid . '.' . $file->extension();
                    $folder = "uploads/userProfile";
                   if($file->storeAs($folder, $filenewname))
                        User::where('id', student()->id)->update(['photo'=>$filenewname]);
                        return redirect()->route('user.user.profile')->with('success', "Photo updated successfully");
                }
            }
    }

    public function updateStudentDetails(Request $request){
        $validator = Validator::make($request->all(),[
                    'name' => 'required',
                    'phone_no' => 'required|unique:users,phone_no,'.student()->id
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            User::where('id', Student()->id)->update([
                    'name' => $request->name,
                    'phone_no' => $request->phone_no
            ]);
            return redirect()->route('user.user.profile')->with('info', 'Details updated successfully!');
        }
    }

    public function updateLecturerPassword(Request $request){
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
            $hashed_password = student()->password;
            if(!Hash::check($current_password, $hashed_password)){
                return redirect()->back()->with("fail", "Current password is wrong!")->withInput();
            }
            if(Hash::check($request->new_password,$hashed_password)){
                return redirect()->back()->with("fail", "The new password can not be the same with the current password!")->withInput();
            }
            User::where('id', student()->id)->update([
                    'password' => Hash::make($request->new_password),
                    'updated_at' => Carbon::now()
            ]);
            Auth::guard()->logout();
            return redirect()->route('user.user.login')->with('info', 'Your password was updated! please login with your new password!');
         }
    }

    
    
    //update last login

    public function updateStudentLastLogin(){
        $id = student()->id;
        GeneralHelper::updateLastLogin(User::class,$id, "last_login");
        return true;
    }

    public function getSchedulesforCalendar(Request $request){
        // $events = ClassSchedule::where(['status'=>'open'])->where('start_time','>=', $request->start)->where('end_time', '<=', $request->end)->get(['id', 'start_time', 'end_time']);
        $schedules = ClassSchedule::where('status', 'open')->where('level', student()->level)->get();
        $events = [];
        foreach($schedules as $schedule){
            $event = [
                    'title' => $schedule->course->course_code,
                    'start' => $schedule->start_time,
                    'end' => $schedule->end_time
            ];
            $events[] = $event;
        }
        return response()->json($events);
    }



    public function joinClass(Request $request){
        if(student()->photo == "default.png"){
            return response()->json("Please update your photo before you can join the class!");
        }
        $ongoing = new OngoingClass();
        $ongoing->user_id = $request->userid;
        $ongoing->schedule_id = $request->scheduleid;
        $ongoing->created_at = Carbon::now();
        $ongoing->save();
        return response()->json("You have join the class!");

    }
   
   
}
