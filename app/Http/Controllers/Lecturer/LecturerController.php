<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\EmailHelper;
use App\Models\Lecturer;
use App\Models\LockOutcounter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\VerifyLecturerEmail;
use Illuminate\Support\Str;

class LecturerController extends Controller
{

    public function loginPage()
    {
        return view('users.Lecturer.Auth.lecturer-login');
    }
    public function processLecturerLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lecturer_email' => 'required|exists:lecturers,lecturer_email',
            'password' => 'required'
        ], [
            'lecturer_email.exists' => 'Lecturer not Found!'
        ]);

        if (!$validator->passes()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $credentials = $request->only('lecturer_email', 'password');
            if (Auth::guard('lecturer')->attempt($credentials)) {
                $lecturer = auth('lecturer')->user();
                if ($lecturer->email_verified == 0) {
                    session()->has('registered_lecturer_email') ?? session()->forget('registered_lecturer_email');
                    if ($lecturer) {
                        session()->put('registered_lecturer_email', $lecturer->lecturer_email);
                    }
                    Auth::guard('lecturer')->logout();
                    return redirect()->route('lecturer.lect.invalid.token')->with('fail', 'Your email address is not verified! please request a new verification link!');
                }
                if ($lecturer->locked_out == 1) {
                    Auth::guard('lecturer')->logout();
                    return redirect()->route('locked.out')->with('fail', 'You were locked out of the system! on ' . $lecturer->date_locked_out);
                }

                Lecturer::where('id', $lecturer->id)->update(['lecturer_last_login' => Carbon::now()]);
                return redirect()->intended(route('lecturer.lect.dashboard'))->with('success', 'You have successfully logged in!');
            } else {
                $lecturer = Lecturer::where('lecturer_email', $request->lecturer_email)->first();
                $lockout =  LockOutcounter::where(['user_id' => $lecturer->id, 'created_at' => Carbon::today()])->get();
                if (count($lockout) >= 3) {
                    Lecturer::where('id', $lecturer->id)->update(
                        [
                            'locked_out' => 1,
                            'date_locked_out' => Carbon::today()
                        ]
                    );
                    return redirect()->route('locked.out')->with('fail', 'Locked Out');
                } else {
                    LockOutcounter::create(['user_id' => $lecturer->id]);
                }
                return redirect()->back()->with('fail', "Error Logging in! please check your password!")->withInput();
            }
        }
    }

    public function lecturerInvalidToken()
    {
        return view('users.Lecturer.Auth.invalid-token');
    }

    public function VerifyLecturerEmail(Request $request)
    {
        $token = $request->token;
        $lecturer = $request->hash;
        $current_user = Lecturer::where('lecturer_uniqueid', $lecturer)->first();
        $storedToken =  VerifyLecturerEmail::where(['token' => $token, 'lecturer_uniqueid' => $lecturer])->first();
        if (!is_null($storedToken)) {
            session()->has('registered_lecturer_email') ?? session()->forget('registered_lecturer_email');

            if ($current_user) {
                session()->put('registered_lecturer_email', $current_user->lecturer_email);
            }
            if ($storedToken->created_at < Carbon::today()) {
                return redirect()->route('lecturer.lect.invalid.token')->with('fail', 'Token has expired request for another!');
            }
            if ($current_user->email_verified == 1) {
                return redirect()->route('lecturer.lect.login')->with('info', 'Your email address is already verified! please login');
            }
            $current_user->update([
                'email_verified' => 1,
                'status' => 'active'
            ]);
            VerifyLecturerEmail::where(['token' => $token, 'lecturer_uniqueid' => $lecturer])->delete();
            return redirect()->route('lecturer.lect.login')->with('success', 'Your email address have been verified! please login');
        } else {
            // if(session()->has('registered_admin_email')){
            //     session()->forget('registered_admin_email');
            // }
            session()->has('registered_lecturer_email') ?? session()->forget('registered_lecturer_email');

            if ($current_user) {
                session()->put('registered_lecturer_email', $current_user->lecturer_email);
            }

            $lockout =  LockOutcounter::where(['user_id' => $current_user->id, 'created_at' => Carbon::today()])->get();
            if (count($lockout) >= 3) {
                Lecturer::where('id', $current_user->id)->update(
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

                return redirect()->route('lecturer.lect.invalid.token')->with('fail', 'Invalid Token');
            }
        }
    }


    public function resendLecturerToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lecturer_email' => 'required|email|exists:lecturers,lecturer_email',
        ], [
            'lecturer_email.exists' => "This email is not registered!"
        ]);

        if (!$validator->passes()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $lecturer = Lecturer::where('lecturer_email', $request->lecturer_email)->first();
            $uniqueid =  $lecturer->lecturer_uniqueid;
            $token = hash('sha256', Str::random(120));
            $link = route('lecturer.lect.verify.email', ['token' => $token, 'hash' => $uniqueid, 'Service' => 'Email-Verification']);
            $check = VerifyLecturerEmail::where('lecturer_uniqueid', $uniqueid)->first();
            if ($check) {
                $check->delete();
            }
            VerifyLecturerEmail::create([
                'lecturer_uniqueid' => $uniqueid,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            $message = "Hi " . $lecturer->lecturer_fullname;
            $message .= "You requested for a new verification link! click on the button below to verify your email!";
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


    public function logout()
    {
        Auth::guard('lecturer')->logout();
        return redirect()->route('lecturer.lect.login')->with('info', 'You have logged out of the system!');
    }
}
