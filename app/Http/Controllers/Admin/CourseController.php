<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function Courses(){
        $lecturers = Lecturer::all();
        $semesters = ['first semester', 'second semester'];
        $levels = ['ND1', 'ND2', "HND1", "HND2"];
        return view('users.Admin.Pages.Courses.admin-courses', [
            'lecturers'=>$lecturers, 
            'semesters'=>$semesters,
            'levels' => $levels
        ]);
    }

    public function listCourses(){
        $courses = Course::orderBy('course_title', 'asc')->get();
        return DataTables::of($courses)
                            ->addIndexColumn()
                            ->addColumn('action', function($row){
                                return '<div class="btn-group">
                                            <a href="'.route('admin.ad.course.edit','edit-'. $row->id).'" class="btn btn-outline-secondary editCourseBtn" data-id="'.$row->id.'" >Edit</a>
                                            <button type="button" class="btn btn-outline-danger deleteCourseBtn" data-id="'.$row->id.'" data-url="'.route('admin.ad.course.delete').'">Delete</button>
                                        </div>';
                            })
                            ->addColumn('lecturer', function($row){
                                    return $row->lecturer->lecturer_fullname;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
    }


    public function addCourse(Request $request){
        $validator = Validator::make($request->all(), [
                    'course_title' => 'required',
                    'course_code' => 'required|unique:courses,course_code',
                    'credit_unit' => 'required|numeric',
                    'lecturer' => 'required',
                    'semester' => 'required',
                    'level' => 'required'
        ], [
            'course_code.unique' => "Course already added!"
        ]);

        if($validator->fails()){
            return response()->json(['code'=>0, 'error'=>$validator->errors()->toArray()]);
        }else{
            $course = new Course();
            $course->course_title = ucfirst($request->course_title);
            $course->course_code = Str::upper($request->course_code);
            $course->credit = $request->credit_unit;
            $course->lecturer_id = ucfirst($request->lecturer);
            $course->semester = $request->semester;
            $course->level = $request->level;

            $course->created_at = Carbon::now();
        
            
            if($course->save()){
                return response()->json(['code'=>1, 'msg'=>"Course Added!"]);
            }else{
                return false; 
            }
        }
    }


    public function deleteCourse(Request $request){
        $id = $request->id;
        Course::where('id', $id)->delete();
        return "Course deleted!";
        
    }

    public function editCourse(Request $request){
        $id = $request->id;
       $id = explode('-', $id);
        $course = Course::where('id', $id[1])->first();
        if($course){
            $lecturers = Lecturer::all();
            $semesters = ['first semester', 'second semester'];
            $levels = ['ND1', 'ND2', "HND1", "HND2"];
            return view('users.Admin.Pages.Courses.admin-editcourse', [
                    'course' => $course,
                    'lecturers' => $lecturers,
                    'semesters' => $semesters,
                    'levels' => $levels
            ]);
        }else{
             return redirect()->route('admin.ad.courses')->with('fail', "Course not found!");
        }
    }


    public function updateCourse(Request $request){
        $validator = Validator::make($request->all(), [
                    'course_title' => 'required',
                    'course_code' => 'required|unique:courses,course_code,'.$request->course_id,
                    'credit_unit' => 'required|numeric',
                    'lecturer' => 'required',
                    'semester' => 'required',
                    'level' => 'required'
        ], [
            'course_code.unique' => "Course already added!"
        ]);

        if($validator->fails()){
            return response()->json(['code'=>0, 'error'=>$validator->errors()->toArray()]);
        }else{
            $save =  Course::where('id', $request->course_id)->update([
                        'course_title' => $request->course_title,
                        'course_code' => $request->course_code,
                        'semester' => $request->semester,
                        'level' => $request->level,
                        'credit' => $request->credit_unit,
                        'lecturer_id' => $request->lecturer,
                        'updated_at' => Carbon::now()
             ]);
             if($save){
                session()->put('success', "Course Updated successfully!");
                // session()->forget('success');
              return response()->json(['code'=>1, 'msg'=>"Course Updated successfully!"]);
             }
             
            
             
        }
    }

    

}
