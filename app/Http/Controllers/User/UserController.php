<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Helpers\EmailHelper;
use App\Models\LockOutcounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\VerifyStudentEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Login method
    public function loginPage(){
        return view('users.Student.Auth.student-login');
    }
   
    public function registerPage(){
        $departments = DB::table("department")->get();
        $levels = ['ND1', 'ND2', 'HND1', 'HND2'];
        return view('users.Student.Auth.student-register', ['departments'=>$departments, 'levels'=>$levels]);
    }
   public function registrationSuccess(){
        return view('users.Student.Auth.student-success');
    }
   //process student register
    public function processStudentRegister(Request $request){
        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'phone_no' => 'required|unique:users,phone_no',
                    'department' => 'required',
                    'level' => 'required',
                    'matric_no' => 'required|unique:users,matric_no',
                    'password' => 'required|min:10',
                    'comfirm_password' => 'required|same:password'
        ]);

        if(!$validator->passes()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            if(!str_starts_with($request->matric_no, strtolower("fpi/"))){
                return redirect()->back()->with('fail', "Please enter correct matric no!")->withInput();
            }
           $ip = $request->ip();
           $mac = "342ceSample";
           $uniqueid = Str::random(10);
           $user = new User();
           $user->name = $request->name;
           $user->email = $request->email;
           $user->phone_no = $request->phone_no;
           $user->department = $request->department;
           $user->matric_no = $request->matric_no;
           $user->level = $request->level;
           $user->status = "inactive";
           $user->password = Hash::make($request->password);
           $user->created_at = Carbon::now();
           $user->uniqueid = $uniqueid;
           $user->ip_address = $ip;
           $user->mac_address = $mac;
           
            if($user->save()){
                $uniqueid =  $user->uniqueid;
                $token = hash('sha256', Str::random(120));
                $link = route('user.user.verify.email', ['token'=>$token, 'hash'=>$uniqueid,'Service'=>'Email-Verification']);

                VerifyStudentEmail::create([
                    'user_uniqueid' => $uniqueid,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
                $message = "Hi " . $user->name;
                $message .="You have successfully registered!, please follow the intsruction below to verify your email address!";
                $mail_data = [
                        'from' => 'gtechnoproject22@gmail.com',
                        'to' => $user->email,
                        'toName' => $user->name,
                        'subject' => 'Email Verification',
                        'body' => $message,
                        'actionLink' => $link,
                        'actionLinkText' => "Verify Email"
                ];
                EmailHelper::GsendMail($mail_data);

                return redirect()->route('user.user.registration.success');
            }
        }
    }

   
    public function processStudentLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'matric_no' => 'required|string|exists:users,matric_no',
            'password' => 'required'
        ], [
            'matric_no.exists' => 'Student not Found!'
        ]);

        if (!$validator->passes()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $credentials = $request->only('matric_no', 'password');
            if (Auth::guard()->attempt($credentials)) {
                $student = auth()->user();
                if ($student->email_verified == 0) {
                    session()->has('registered_email') ?? session()->forget('registered_email');
                    if ($student) {
                        session()->put('registered_email', $student->email);
                    }
                    Auth::guard()->logout();
                    return redirect()->route('user.user.invalid.token')->with('fail', 'Your email address is not verified! please request a new verification link!');
                }
                if ($student->locked_out == 1) {
                    Auth::guard()->logout();
                    return redirect()->route('locked.out')->with('fail', 'You were locked out of the system! on ' . $student->date_locked_out);
                }

                User::where('id', $student->id)->update(['last_login' => Carbon::now()]);
                return redirect()->intended(route('user.user.dashboard'))->with('success', 'You have successfully logged in!');
            } else {
                $student = User::where('email', $request->email)->first();
                $lockout =  LockOutcounter::where(['user_id' => $student->id, 'created_at' => Carbon::today()])->get();
                if (count($lockout) >= 3) {
                    User::where('id', $student->id)->update(
                        [
                            'locked_out' => 1,
                            'date_locked_out' => Carbon::today()
                        ]
                    );
                    return redirect()->route('locked.out')->with('fail', 'Locked Out');
                } else {
                    LockOutcounter::create(['user_id' => $student->id]);
                }
                return redirect()->back()->with('fail', "Error Logging in! please check your password!")->withInput();
            }
        }
    }

    public function studentInvalidToken()
    {
        return view('users.Student.Auth.invalid-token');
    }

    public function VerifyStudentEmail(Request $request)
    {
        $token = $request->token;
        $student = $request->hash;
        $current_user = User::where('uniqueid', $student)->first();
        $storedToken =  VerifyStudentEmail::where(['token' => $token, 'user_uniqueid' => $student])->first();
        if (!is_null($storedToken)) {
            session()->has('registered_email') ?? session()->forget('registered_email');

            if ($current_user) {
                session()->put('registered_email', $current_user->email);
            }
            if ($storedToken->created_at < Carbon::today()) {
                return redirect()->route('user.user.invalid.token')->with('fail', 'Token has expired request for another!');
            }
            if ($current_user->email_verified == 1) {
                return redirect()->route('user.user.login')->with('info', 'Your email address is already verified! please login');
            }
            $current_user->update([
                'email_verified' => 1,
                'status' => 'active'
            ]);
            VerifyStudentEmail::where(['token' => $token, 'user_uniqueid' => $student])->delete();
            return redirect()->route('user.user.login')->with('success', 'Your email address have been verified! please login');
        } else {
            // if(session()->has('registered_email')){
            //     session()->forget('registered_email');
            // }
            session()->has('registered_email') ?? session()->forget('registered_email');

            if ($current_user) {
                session()->put('registered_email', $current_user->email);
            }

            $lockout =  LockOutcounter::where(['user_id' => $current_user->id, 'created_at' => Carbon::today()])->get();
            if (count($lockout) >= 3) {
                User::where('id', $current_user->id)->update(
                    [
                        'locked_out' => 1,
                        'date_locked_out' => Carbon::today()
                    ]
                );
                return redirect()->route('locked.out')->with('fail', 'Locked Out');
            } else {

                LockOutcounter::create(
                    [
                        'user_id' => $current_user->id

                    ]
                );

                return redirect()->route('user.user.invalid.token')->with('fail', 'Invalid Token');
            }
        }
    }


    public function resendStudentToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => "This email is not registered!"
        ]);

        if (!$validator->passes()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $student = User::where('email', $request->email)->first();
            $uniqueid =  $student->uniqueid;
            $token = hash('sha256', Str::random(120));
            $link = route('users.user.verify.email', ['token' => $token, 'hash' => $uniqueid, 'Service' => 'Email-Verification']);
            $check = VerifyStudentEmail::where('user_uniqueid', $uniqueid)->first();
            if ($check) {
                $check->delete();
            }
            VerifyStudentEmail::create([
                'user_uniqueid' => $uniqueid,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            $message = "Hi " . $student->fullname;
            $message .= "You requested for a new verification link! click on the button below to verify your email!";
            $mail_data = [
                'from' => 'gtechnoproject22@gmail.com',
                'to' => $student->email,
                'toName' => $student->fullname,
                'subject' => 'Email Verification',
                'body' => $message,
                'actionLink' => $link,
                'actionLinkText' => "Verify Email"
            ];
            EmailHelper::GsendMail($mail_data);

            return redirect()->route('resent.token');
        }
    }


    public function logout()
    {
        Auth::guard()->logout();
        return redirect()->route('user.user.login')->with('info', 'You have logged out of the system!');
    }

  
}
