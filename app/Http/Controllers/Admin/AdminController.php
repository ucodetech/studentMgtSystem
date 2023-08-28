<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EmailHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\LockOutcounter;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\VerifyAdminEmail;
use Illuminate\Support\Str;



class AdminController extends Controller
{
    public function loginPage(){
        return view('users.Admin.Auth.admin-login');
    }
    public function registerPage(){
        $permssions = Permission::all();
        return view('users.Admin.Auth.admin-register', ['permissions'=>$permssions]);
    }


    public function registrationSuccess(){
        return view('users.Admin.Auth.admin-success');
    }

    public function adminInvalidToken(){
        return view('users.Admin.Auth.invalid-token');
    }

    //process admin register
    public function processAdminRegister(Request $request){
        $validator = Validator::make($request->all(), [
                    'admin_fullname' => 'required',
                    'admin_email' => 'required|email|unique:admins,admin_email',
                    'admin_tel' => 'required|unique:admins,admin_tel',
                    'admin_permission' => 'required',
                    'password' => 'required|min:10',
                    'comfirm_password' => 'required|same:password'
        ]);

        if(!$validator->passes()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $uniqueid = Str::random(10);
            $admin = new Admin();
            $admin->admin_fullname = $request->admin_fullname;
            $admin->admin_email = $request->admin_email;
            $admin->admin_tel = $request->admin_tel;
            $admin->admin_permission = $request->admin_permission;
            $admin->status = "inactive";
            $admin->password = Hash::make($request->password);
            $admin->created_at = Carbon::now();
            $admin->admin_uniqueid = $uniqueid;
            
            if($admin->save()){
                $uniqueid =  $admin->admin_uniqueid;
                $token = hash('sha256', Str::random(120));
                $link = route('admin.ad.verify.email', ['token'=>$token, 'hash'=>$uniqueid,'Service'=>'Email-Verification']);

                VerifyAdminEmail::create([
                    'admin_uniqueid' => $uniqueid,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
                $message = "Hi " . $admin->admin_fullname;
                $message .="You have been added to CSAS Admin panel, please follow the intsruction below to verify your email address!";
                $mail_data = [
                        'from' => 'gtechnoproject22@gmail.com',
                        'to' => $admin->admin_email,
                        'toName' => $admin->admin_fullname,
                        'subject' => 'Email Verification',
                        'body' => $message,
                        'actionLink' => $link,
                        'actionLinkText' => "Verify Email"
                ];
                EmailHelper::GsendMail($mail_data);
                // Mail::send('inc.email-template', $mail_data, function ($message) use ($mail_data) {
                //     $message->from($mail_data['from']);
                //     $message->sender($mail_data['from']);
                //     $message->to($mail_data['to'],  $mail_data['toName']);
                //     $message->subject($mail_data['subject']);
                //     $message->priority(3);
                //     // $message->attach('pathToFile');
                // });

                return redirect()->route('admin.ad.registration.success');
            }
        }
    }

    public function processAdminLogin(Request $request){
            $validator = Validator::make($request->all(), [
                        'admin_email' =>'required|exists:admins,admin_email',
                        'password' => 'required'
            ],[
                'admin_email.exists' => 'Admin not Found!'
            ]);

            if(!$validator->passes()){
                return redirect()->back()->withErrors($validator)->withInput();
            }else{

                $credentials = $request->only('admin_email', 'password');
                if(Auth::guard('admin')->attempt($credentials)){
                    $admin = auth('admin')->user();
                    if($admin->email_verified == 0){
                        session()->has('registered_admin_email') ?? session()->forget('registered_admin_email');
                        if($admin){
                            session()->put('registered_admin_email', $admin->admin_email);
                        }
                        Auth::guard('admin')->logout();
                        return redirect()->route('admin.ad.invalid.token')->with('fail', 'Your email address is not verified! please request a new verification link!');
                    }
                    if($admin->locked_out == 1){
                        Auth::guard('admin')->logout();
                        return redirect()->route('locked.out')->with('fail', 'You were locked out of the system! on ' . $admin->date_locked_out);
                    }
                   
                    Admin::where('id', $admin->id)->update(['admin_last_login'=>Carbon::now()]);
                    return redirect()->intended(route('admin.ad.dashboard'))->with('success', 'You have successfully logged in!');
                    
                }else{
                    $admin = Admin::where('admin_email', $request->admin_email)->first();
                    $lockout =  LockOutcounter::where(['user_id'=>$admin->id, 'created_at'=>Carbon::today()])->get();  
                    if(count($lockout) >= 3) {
                        Admin::where('id', $admin->id)->update(
                            [
                                'locked_out'=> 1, 
                                'date_locked_out'=>Carbon::today()
                        ]);
                        return redirect()->route('locked.out')->with('fail', 'Locked Out');
                    }else{
                        LockOutcounter::create([ 'user_id'=> $admin->id ]);
                    }
                    return redirect()->back()->with('fail',"Error Logging in! please check your password!")->withInput();
                }

            }




    }

    public function VerifyAdminEmail(Request $request){
        $token = $request->token;
        $admin = $request->hash;
        $current_user = Admin::where('admin_uniqueid', $admin)->first();
        $storedToken =  VerifyAdminEmail::where(['token'=>$token, 'admin_uniqueid'=>$admin])->first();
        if(!is_null($storedToken)){
            if($current_user->email_verified == 1){
                return redirect()->route('admin.ad.login')->with('info', 'Your email address is already verified! please login');
            }
            $current_user->update([
                'email_verified' => 1,
                'status' => 'active'
            ]);
            VerifyAdminEmail::where(['token'=>$token, 'admin_uniqueid'=>$admin])->delete();
            return redirect()->route('admin.ad.login')->with('success', 'Your email address have been verified! please login');
        }else{
            // if(session()->has('registered_admin_email')){
            //     session()->forget('registered_admin_email');
            // }
                session()->has('registered_admin_email') ?? session()->forget('registered_admin_email');

                if($current_user){
                    session()->put('registered_admin_email', $current_user->admin_email);
                }

                $lockout =  LockOutcounter::where(['user_id'=>$current_user->id, 'created_at'=>Carbon::today()])->get();  
                if(count($lockout) >= 3) {
                    Admin::where('id', $current_user->id)->update(
                        [
                            'locked_out'=> 1, 
                            'date_locked_out'=>Carbon::today()
                    ]);
                    return redirect()->route('locked.out')->with('fail', 'Locked Out');
                }else{
                
                    LockOutcounter::create(
                        [
                            'user_id'=> $current_user->id 
                            
                        ]);

                    return redirect()->route('admin.ad.invalid.token')->with('fail', 'Invalid Token');
                 }
               
        }
    }

    public function resendAdminToken(Request $request){
        $validator = Validator::make($request->all(), [
            'admin_email' => 'required|email|exists:admins,admin_email',
            ],[
                'admin_email.exists' => "This email is not registered!"
            ]);

            if(!$validator->passes()){
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                    $admin = Admin::where('admin_email', $request->admin_email)->first();
                    $uniqueid =  $admin->admin_uniqueid;
                    $token = hash('sha256', Str::random(120));
                    $link = route('admin.ad.verify.email', ['token'=>$token, 'hash'=>$uniqueid,'Service'=>'Email-Verification']);
                    $check = VerifyAdminEmail::where('admin_uniqueid', $uniqueid)->first();
                    if($check){
                        $check->delete();
                    }
                    VerifyAdminEmail::create([
                        'admin_uniqueid' => $uniqueid,
                        'token' => $token,
                        'created_at' => Carbon::now()
                    ]);
                    $message = "Hi " . $admin->admin_fullname;
                    $message .="You requested for a new verification link! click on the button below to verify your email!";
                    $mail_data = [
                            'from' => 'gtechnoproject22@gmail.com',
                            'to' => $admin->admin_email,
                            'toName' => $admin->admin_fullname,
                            'subject' => 'Email Verification',
                            'body' => $message,
                            'actionLink' => $link,
                            'actionLinkText' => "Verify Email"
                    ];
                    EmailHelper::GsendMail($mail_data);
                    // Mail::send('inc.email-template', $mail_data, function ($message) use ($mail_data) {
                    //     $message->from($mail_data['from']);
                    //     $message->sender($mail_data['from']);
                    //     $message->to($mail_data['to'],  $mail_data['toName']);
                    //     $message->subject($mail_data['subject']);
                    //     $message->priority(3);
                    //     // $message->attach('pathToFile');
                    // });

                    return redirect()->route('resent.token');
                }
            }
    

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.ad.login')->with('info', 'You have logged out of the system!');
    }
            

}
