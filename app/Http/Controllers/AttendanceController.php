<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassSchedule;
use App\Models\OngoingClass;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function toggleAttendance(Request $request){
        $mode = $request->mode;
        $id = $request->id;

        switch ($mode) {
            case 1:
                # open attendance for student to mark themselves
                ClassSchedule::where('id', $id)->update([
                            'attendance' => 1
                ]);
                return response()->json('You have opened attendance for this lecture, students mark themselves');
                break;
            case 2:
                # open attendance and mark students urself
                ClassSchedule::where('id', $id)->update([
                    'attendance' => 2
                ]);
                return response()->json('You have opened attendance for this lecture but will mark students yourself');
                break;
            default:
                # close attendance
                ClassSchedule::where('id', $id)->update([
                    'attendance' => 0
                 ]);
                 return response()->json('You have closed attendance for this lecture');
                break;
        }
    }


    public function loggedInStudent(Request $request){
        $para = explode('-', $request->schedule_id);
        $id = $para[1];
        $ongoing = OngoingClass::where('schedule_id', $id)->get();
        return view('users.Lecturer.Pages.Dashboard.loggedin-students', ['ongoing'=>$ongoing]);
    }

    public function markStudent(Request $request){
        $mode = $request->mode;
        $student_id = $request->student_id;
        $schedule_id = $request->schedule_id;

        $attended = Attendance::where('schedule_id', $schedule_id)->where('student_id', $student_id)->first();
        if($attended){
            $markedas = $attended->is_present == 1 ? " present" : " absent";
            return response()->json("You have already marked the student! ".$markedas);
        }
        Attendance::create([
                    'student_id' => $student_id,
                    'schedule_id' => $schedule_id,
                    'attendance_date' => Carbon::now(),
                    'is_present' => $mode,
                    'status' => 'opened',
                    'created_at' => Carbon::now()

        ]);
        $markedas = $mode == 1 ? " present" : " absent";
        return response()->json("Attendance marked! ".$markedas);
    }


    public function Attendance(){
        $schedules = ClassSchedule::where('lecturer_id', lecturer()->id)->orderBy('day_of_week', 'desc')->get();
        return view('users.Lecturer.Pages.Dashboard.lecturer-attendance', ['schedules'=>$schedules]);
    }

    public function FetchAttendance(Request $request){
       
        if(empty($request->schedule_id)){
            return response()->json(['code'=>0, 'error'=>"Please select schedule for report!"]);
        }
        $attendances = Attendance::where('schedule_id', $request->schedule_id)->orderBy('id', 'desc')->get();
        $data = "";
        if(count($attendances) > 0){
                $x = 0;
                foreach($attendances as $attendance){
                    $x = $x+1;
                    if($attendance->is_present == 1){
                        $status = "<span class='text-info'>Present</span>";
                    }else{
                        $status = "<span class='text-danger'>Absent</span>";
                    }
                    $data .='<tr>
                            <td>'.$x.'</td>
                            <td>'.$attendance->schedule->course->course_code.'</td>
                            <td>'.$attendance->user->name.'</td>
                            <td>'.$attendance->user->matric_no.'</td>
                            <td>'.$attendance->user->level.'</td>
                            <td>'.$status.'</td>
                            </tr>';
                }
        }else{
            $data .="<h6 class='text-center'>No record found!</h6>";
        }
        
        return response()->json(['code'=>1, 'data'=>$data]);
    }
}
