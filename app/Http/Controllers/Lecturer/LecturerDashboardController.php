<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\GeneralHelper;
use App\Models\ClassSchedule;
use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LecturerDashboardController extends Controller
{
    
        
    public function lecturerDashboard(){
        $courses = Course::where('lecturer_id', lecturer()->id)->get();
        $schedules = ClassSchedule::where('lecturer_id', lecturer()->id)->get();
        $countactive = ClassSchedule::where('lecturer_id', lecturer()->id)->whereNot('status', 'closed')->count();
        $closedactive = ClassSchedule::where('lecturer_id', lecturer()->id)->whereNot('status', 'open')->count();

        $running = ClassSchedule::where(['lecturer_id'=>lecturer()->id, 'start'=>1])->first();
        
        return view('users.Lecturer.Pages.Dashboard.lecturer-dashboard',
                    [
                        'courses' => $courses,
                        'schedules' => $schedules,
                        'countactive' => $countactive,
                        'closedactive' => $closedactive,
                        'running' => $running
                        
                    ]);
    }

    public function lecturerProfile(){
        return view('users.Lecturer.Pages.Profile.lecturer-profile');
    }

    public function updateProfilePhoto(Request $request){
            $validator = Validator::make($request->all(), [
                            'lecturer_photo' => 'required|mimes:png,jpg,jpeg'
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                if($request->hasFile('lecturer_photo')){
                    $file = $request->lecturer_photo;
                    $filenewname = lecturer()->lecturer_uniqueid . '.' . $file->extension();
                    $folder = "uploads/lecturerProfile";
                   if($file->storeAs($folder, $filenewname))
                        Lecturer::where('id', lecturer()->id)->update(['lecturer_photo'=>$filenewname]);
                        return redirect()->route('lecturer.lect.profile')->with('success', "Photo updated successfully");
                }
            }
    }

    public function updateLecturerDetails(Request $request){
        $validator = Validator::make($request->all(),[
                    'lecturer_fullname' => 'required',
                    'lecturer_tel' => 'required|unique:lecturers,lecturer_tel,'.lecturer()->id
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            Lecturer::where('id', lecturer()->id)->update([
                    'lecturer_fullname' => $request->lecturer_fullname,
                    'lecturer_tel' => $request->lecturer_tel
            ]);
            return redirect()->route('lecturer.lect.profile')->with('info', 'Details updated successfully!');
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
            $hashed_password = lecturer()->password;
            if(!Hash::check($current_password, $hashed_password)){
                return redirect()->back()->with("fail", "Current password is wrong!")->withInput();
            }
            if(Hash::check($request->new_password,$hashed_password)){
                return redirect()->back()->with("fail", "The new password can not be the same with the current password!")->withInput();
            }
            Lecturer::where('id', lecturer()->id)->update([
                    'password' => Hash::make($request->new_password),
                    'updated_at' => Carbon::now()
            ]);
            Auth::guard('lecturer')->logout();
            return redirect()->route('lecturer.lect.login')->with('info', 'Your password was updated! please login with your new password!');
         }
    }

    
    
    //update last login

    public function updateLecturerLastLogin(){
        $id = lecturer()->id;
        GeneralHelper::updateLastLogin(Lecturer::class,$id, "lecturer_last_login");
        return true;
    }

    public function getSchedulesforCalendar(Request $request){
        // $events = ClassSchedule::where(['status'=>'open'])->where('start_time','>=', $request->start)->where('end_time', '<=', $request->end)->get(['id', 'start_time', 'end_time']);
        $schedules = ClassSchedule::where('status', 'open')->where('lecturer_id', lecturer()->id)->get();
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


    public function closePreviousSchedule(Request $request){
        $schedules = ClassSchedule::where('day_of_week', '<', Carbon::today())->where('status','open')->where('lecturer_id', lecturer()->id)->get();
       
        if(count($schedules)>0){
            foreach($schedules as $sch){
                ClassSchedule::where('id', $sch->id)->update([
                            'status' => 'closed'
                ]);
              
                return response()->json("Schedule less than today have been closed!");
            }
        }else{
           return response()->json("All Schedule is greater than or equal to today!");
        } 
    }


    public function startClass(Request $request){
        ClassSchedule::where('id', $request->scheduleid)->update([
                    'start' => 1
        ]);
        return response()->json( "Class have started and will end automatically once its end time!");


    }
    public function closeSchedule(Request $request){
        ClassSchedule::where('id', $request->scheduleid)->update([
                    'status' => 'closed',
                    'start' => 0
        ]);
        return response()->json( "Schedule have been closed!");


    }


    public function autoEndClass(Request $request){
        if($request->data == "autoCloseClass")
            $schedule = ClassSchedule::where(['day_of_week'=>Carbon::today(), 'start'=>1])->where('lecturer_id', lecturer()->id)->first();
            if($schedule){
                if($schedule->end_time == formatTime(Carbon::now()))
                    ClassSchedule::where('id', $schedule->id)->update([
                                'status' => 'closed',
                                'start' => 0
                    ]);
                    return response()->json("Class has ended! and closed");
                }
            return false;
        
    }


}//end of class
