<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClassRoomController extends Controller
{
    public function classRooms(){
        $schools = DB::table('schoolsTable')->get();
        return view('users.Admin.Pages.ClassRooms.admin-classrooms', ['schools'=>$schools]);
    }

    public function listClassRooms(){
        $classrooms = ClassRoom::orderBy('class_name', 'asc')->get();
        return DataTables::of($classrooms)
                            ->addIndexColumn()
                            ->addColumn('action', function($row){
                                return '<div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary editClassBtn" data-id="'.$row->id.'">Edit</button>
                                            <button type="button" class="btn btn-outline-danger deleteClassBtn" data-id="'.$row->id.'">Delete</button>
                                        </div>';
                            })
                            ->rawColumns(['action'])
                            ->make(true);
    }


    public function addClassRooms(Request $request){
        $validator = Validator::make($request->all(), [
                    'class_name' => 'required|unique:class_rooms,class_name',
                    'class_location' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['code'=>0, 'error'=>$validator->errors()->toArray()]);
        }else{
            $classroom = new ClassRoom();
            $classroom->class_name = $request->class_name;
            $classroom->class_location = $request->class_location;
            $classroom->created_at = Carbon::now();

            if($classroom->save()){
                return response()->json(['code'=>1, 'msg'=>"Class Room Added!"]);
            }else{
                return false;
            }
        }
    }











}
