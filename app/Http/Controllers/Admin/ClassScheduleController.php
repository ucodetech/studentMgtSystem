<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\ClassSchedule;
use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use App\Helpers\EmailHelper;;;

class ClassScheduleController extends Controller
{
    public function Schedules(){
        $courses = Course::orderBy('level', 'asc')->get();
        $days = ['mon','tue','wed','thu','fri'];
        $classrooms = ClassRoom::orderBy('class_name', 'asc')->get();
        $lecturers = Lecturer::orderBy('lecturer_fullname', 'asc')->get();
        return view('users.Admin.Pages.Schedules.admin-schedules', [
            'courses'=>$courses,
            'days' => $days,
            'classrooms' => $classrooms,
            'lecturers' => $lecturers 
          
        ]);
    }

    public function listSchedules(){
        $schedules = ClassSchedule::orderBy('start_time', 'desc')->get();
        return DataTables::of($schedules)
                            ->addIndexColumn()
                            ->addColumn('action', function($row){
                                $button = '<div class="btn-group">
                                            <a href="'.route('admin.ad.schedule.edit','edit-'. $row->id).'" class="btn btn-outline-secondary " data-id="'.$row->id.'" >Edit</a>
                                            <button type="button" class="btn btn-outline-danger deleteScheduleBtn" data-id="'.$row->id.'" data-url="'.route('admin.ad.schedule.delete').'">Delete</button>';
                                        if($row->status == 'open'){
                                          $button.='<button type="button" title="End Schedule" class="btn btn-outline-warning closeScheduleBtn" data-id="'.$row->id.'" >&times;</button>';
                                        }

                                       $button.='</div>';
                                return $button;
                            })
                            ->addColumn('lecturer', function($row){
                                    return Course::getCourseById($row->course_id)->lecturer->lecturer_fullname;
                            })
                            ->addColumn('course_code', function($row){
                                return Course::getCourseById($row->course_id)->course_code;
                            })
                            ->addColumn('level', function($row){
                                return Course::getCourseById($row->course_id)->level;;
                            })
                            ->addColumn('classroom_name', function($row){
                                return ClassRoom::getClassById($row->classroom_id)->class_name;
                            })
                            ->addColumn('start_time', function($row){
                                return formatTime($row->start_time);
                                
                            })
                            ->addColumn('end_time', function($row){
                                return formatTime($row->end_time);
                            })
                            ->addColumn('created_at', function($row){
                                return pretty_dates($row->day_of_week);
                            })
                            ->addColumn('day_of_week', function($row){
                                $day = Carbon::createFromDate($row->day_of_week);
                                return $day->shortDayName;
                            })
                            ->addColumn('status', function($row){
                                if($row->status == "open"){
                                    return '<span class="badge badge-btn text-bg-success">Open</span>';
                                }else{
                                    return '<span class="badge badge-btn text-bg-danger">Closed</span>';
                                }
                            })
                            ->rawColumns(['action','status'])
                            ->make(true);

                          

    }


    public function addSchedule(Request $request){
        $validator = Validator::make($request->all(), [
                    'course' => 'required',
                    'classroom' => 'required',
                    'dayOfWeek' => 'required',
                    'start_time' => 'required',
                    'end_time' => 'required',
                    
        ]);

        if($validator->fails()){
            return response()->json(['code'=>0, 'error'=>$validator->errors()->toArray()]);
        }else{
            //check if schedule day is saturday or sunday
            $day = Carbon::createFromDate($request->dayOfWeek);
            if($day->shortDayName == "Sun" || $day->shortDayName == "Sat"){
                return response()->json(['code'=>2, 'err'=>"You can not schedule class for  (". $day->dayName.")"]);
            }
            //check if date is previous day
            if($day < Carbon::today()){
                return response()->json(['code'=>2, 'err'=>"You can not back date schedule"]);
            }
            //check if start time and end time is less than 8am or greater than 4pm
            // return response()->json(['code'=>2, 'err'=>formatTime($request->start_time) . '   ' . formatTime($request->end_time)]);
            
            // if(formatTime($request->start_time) < "8:00am" || formatTime($request->end_time) > "4:00pm"){
            //     return response()->json(['code'=>2, 'err'=>"You can not schedule class below 8:00am and above 4:00pm!"]);
            // }

            if($request->dayOfWeek != pretty_dated($request->start_time) || $request->dayOfWeek != pretty_dated($request->end_time)){
                return response()->json(['code'=>2, 'err'=>"Day of week, start time and end time date should be the same!"]);
            }
            //check if course is been added twice same day in different time
            $checkcourse = ClassSchedule::where(['course_id'=>$request->course, 'day_of_week'=>$request->dayOfWeek])->first();
            if($checkcourse){
                return response()->json(['code'=>2, 'err'=>"You have already scheduled this course for (". $checkcourse->day_of_week.") Start time: " . $checkcourse->start_time . " End time: " . $checkcourse->end_time]);
            }

            //check if classroom is occupied for the time frame today
            $checkclassroom = ClassSchedule::where(
                                            ['classroom_id'=>$request->classroom,'day_of_week'=>$request->dayOfWeek])
                                            ->whereBetween('end_time',
                                            [$request->start_time, $request->end_time])
                                            ->first();
            if($checkclassroom){
                return response()->json(['code'=>2, 'err'=>"Class room is occupied for (". $checkclassroom->day_of_week.") Between: " . $checkclassroom->start_time . " To: " . $checkclassroom->end_time . " Course: " . Course::getCourseById($checkclassroom->course_id)->course_title]);
            }
           
            //check if time is already scheduled for another course today
            $checktime = ClassSchedule::whereBetween('end_time',
                                        [$request->start_time, $request->end_time])
                                        ->where('day_of_week',$request->dayOfWeek)
                                        ->first();
            if($checktime){
                return response()->json(['code'=>2, 'err'=>"The selected time is occupied (". $checktime->day_of_week.") From: " . $checktime->start_time . " To: " . $checktime->end_time]);
            }
            $course = explode(',', $request->course);
            $course_id = $course[0];
            $lecturer_id = $course[1];
            $level = $course[2];


            $schedule = new ClassSchedule();
            $schedule->course_id = $course_id;
            $schedule->classroom_id = $request->classroom;
            $schedule->day_of_week = $request->dayOfWeek;
            $schedule->start_time = $request->start_time;
            $schedule->end_time = $request->end_time;
            $schedule->lecturer_id = $lecturer_id;
            $schedule->level = $level;
            $schedule->created_at = Carbon::now();

            if($schedule->save()){
                $students = User::where('level', $schedule->level)->get();
                foreach ($students as $user){
                    $link = route('user.user.login');
                    $message = "Hi " . $user->name;
                    $message .=" A new schedule have been made for your class! Kindly login to your portal to check out the schedule details";
                    $mail_data = [
                            'from' => 'gtechnoproject22@gmail.com',
                            'to' => $user->email,
                            'toName' => $user->name,
                            'subject' => 'Class Scheduled',
                            'body' => $message,
                            'actionLink' => $link,
                            'actionLinkText' => "Login"
                    ];
                    EmailHelper::GsendMail($mail_data);
            }
                return response()->json(['code'=>1, 'msg'=>"Class Scheduled!"]);
            }else{
                return false; 
            }
        }
    }


    public function deleteSchedule(Request $request){
        $id = $request->id;
        ClassSchedule::where('id', $id)->delete();
        return "Schedule deleted!";
        
    }

    public function editSchedule(Request $request){
        $id = $request->id;
       $id = explode('-', $id);
        $schedule = ClassSchedule::where('id', $id[1])->first();
        $lecturers = Lecturer::orderBy('lecturer_fullname', 'asc')->get();
        if($schedule){
            $courses = Course::orderBy('level', 'asc')->get();
            $days = ['mon','tue','wed','thu','fri'];
            $classrooms = ClassRoom::orderBy('class_name', 'asc')->get();
            return view('users.Admin.Pages.Schedules.admin-editschedule', [
                'courses'=>$courses,
                'days' => $days,
                'classrooms' => $classrooms,
                'schedule' => $schedule,
                'lecturers' => $lecturers 
            
            ]);
        }else{
             return redirect()->route('admin.ad.schedule')->with('fail', "Schedule not found!");
        }
    }


public function updateSchedule(Request $request){
   
        $validator = Validator::make($request->all(), [
            'course' => 'required',
            'classroom' => 'required',
            'dayOfWeek' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
                
    ]);

    if($validator->fails()){
        return response()->json(['code'=>0, 'error'=>$validator->errors()->toArray()]);
    }else{
        //check if schedule day is saturday or sunday
        $day = Carbon::createFromDate($request->dayOfWeek);
        if($day->shortDayName == "Sun" || $day->shortDayName == "Sat"){
            return response()->json(['code'=>2, 'err'=>"You can not schedule class for  (". $day->dayName.")"]);
        }
        //check if date is previous day
        if($day < Carbon::today()){
            return response()->json(['code'=>2, 'err'=>"You can not back date schedule"]);
        }
        //check if start time and end time is less than 8am or greater than 4pm
        // return response()->json(['code'=>2, 'err'=>formatTime($request->start_time) . '   ' . formatTime($request->end_time)]);
        
        if(formatTime($request->start_time) < "8:00am" || formatTime($request->end_time) > "4:00pm"){
            return response()->json(['code'=>2, 'err'=>"You can not schedule class below 8:00am and above 4:00pm!"]);
        }
        
        if($request->dayOfWeek != pretty_dated($request->start_time) || $request->dayOfWeek != pretty_dated($request->end_time)){
            return response()->json(['code'=>2, 'err'=>"Day of week, start time and end time date should be the same!"]);
        }

        //check if course is been added twice same day in different time
        $checkcourse = ClassSchedule::where(['course_id'=>$request->course, 'day_of_week'=>$request->dayOfWeek])->whereNot('course_id', $request->schedule_course_id)->first();
        if($checkcourse){
            return response()->json(['code'=>2, 'err'=>"You have already scheduled this course for (". $checkcourse->day_of_week.") Start time: " . $checkcourse->start_time . " End time: " . $checkcourse->end_time]);
        }

        //check if classroom is occupied for the time frame today
        $checkclassroom = ClassSchedule::where(
                                        ['classroom_id'=>$request->classroom,'day_of_week'=>$request->dayOfWeek])->whereNot('classroom_id', $request->scheduled_classroom_id)
                                        ->whereBetween('end_time',
                                        [$request->start_time, $request->end_time])
                                        ->first();
        if($checkclassroom){
            return response()->json(['code'=>2, 'err'=>"Class room is occupied for (". $checkclassroom->day_of_week.") Between: " . $checkclassroom->start_time . " To: " . $checkclassroom->end_time . " Course: " . Course::getCourseById($checkclassroom->course_id)->course_title]);
        }
    
        //check if time is already scheduled for another course today
        $checktime = ClassSchedule::whereBetween('end_time',
                                    [$request->start_time, $request->end_time])->whereNotBetween('end_time',[$request->scheduled_starttime, $request->scheduled_endtime])
                                    ->where('day_of_week',$request->dayOfWeek)
                                    ->first();
        if($checktime){
            return response()->json(['code'=>2, 'err'=>"The selected time is occupied (". $checktime->day_of_week.") From: " . $checktime->start_time . " To: " . $checktime->end_time]);
        }
                $course = explode(',', $request->course);
               
                $course_id = $course[0];
                $lecturer_id = $course[1];
                $level = $course[2];
                
                ClassSchedule::where('id',$request->schedule_id)->update([
                        'course_id' => $course_id,
                        'classroom_id' => $request->classroom,
                        'day_of_week' => $request->dayOfWeek,
                        'start_time' => $request->start_time,
                        'end_time' => $request->end_time,
                        'lecturer_id' => $lecturer_id,
                        'level' => $level,
                        'updated_at' => Carbon::now(),
                ]);
                return response()->json(['code'=>1, 'msg'=>"Schedule Updated!"]);
               
        }
    }

public function closeSchedule(Request $request){
            ClassSchedule::where('id', $request->scheduleid)->update([
                        'status' => 'closed'
            ]);
            return response()->json( "Schedule have been closed!");

    
}




}//end of class
