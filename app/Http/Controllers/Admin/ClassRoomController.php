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
                                            <button type="button" class="btn btn-outline-secondary editClassBtn" data-id="'.$row->id.'"  data-url="'.route('admin.ad.class.room.edit').'">Edit</button>
                                            <button type="button" class="btn btn-outline-danger deleteClassBtn" data-id="'.$row->id.'" data-url="'.route('admin.ad.class.room.delete').'">Delete</button>
                                        </div>';
                            })
                            ->rawColumns(['action'])
                            ->make(true);
    }


    public function addClassRooms(Request $request){
        $validator = Validator::make($request->all(), [
                    'class_name' => 'required|unique:class_rooms,class_name',
                    'class_location' => 'required'
        ], [
            'class_name.unique' => "class room already added!"
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


    public function deleteClassRoom(Request $request){
        $id = $request->id;
        ClassRoom::where('id', $id)->delete();
        return "Class Room deleted!";
        
    }

    public function editClassRoom(Request $request){
        $id = $request->id;
        $room = ClassRoom::where('id', $id)->first();
        $schools = DB::table('schoolsTable')->get();
        $data = "";
        if($room)
                $data.='
                <input type="hidden" id="class_room_id" name="class_room_id" value="'.$room->id.'">
                <div class="mb-3">
                    <label class="form-label">Class Room</label>
                    <input class="form-control form-control-lg" type="text" id="class_name" name="class_name" placeholder="" value="'.$room->class_name.'"/>
                    <span class="text-error text-danger class_name_error"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <textarea class="form-control form-control-lg" id="class_location" name="class_location" placeholder="" >'.$room->class_location.'</textarea>
                    <span class="text-error text-danger class_location_error"></span>
                </div>
            
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-lg btn-primary">Edit Classroom</button>
                
                </div>
           ';
            return $data;

        
    }


    public function updateClassRoom(Request $request){
        $validator = Validator::make($request->all(), [
                    'class_name' => 'required|unique:class_rooms,class_name,'.$request->class_room_id,
                    'class_location' => 'required'
        ], [
            'class_name.unique' => "class room already added!"
        ]);

        if($validator->fails()){
            return response()->json(['code'=>0, 'error'=>$validator->errors()->toArray()]);
        }else{
             ClassRoom::where('id', $request->class_room_id)->update([
                        'class_name' => $request->class_name,
                        'class_location' => $request->class_location
             ]);
             return response()->json(['code'=>1, 'msg'=>"Class Room updated!"]);
            
             
        }
    }

    

}
