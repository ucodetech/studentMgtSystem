<?php 
namespace App\Helpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class EmailHelper {
    
    public static function GsendMail($mail_data){
       try {
            Mail::send('inc.email-template', $mail_data, function ($message) use ($mail_data) {
                $message->from($mail_data['from']);
                $message->sender($mail_data['from']);
                $message->to($mail_data['to'],  $mail_data['toName']);
                $message->subject($mail_data['subject']);
                $message->priority(3);
                // $message->attach('pathToFile');
            });
       } catch (\Throwable $th) {
           return "Error Sending mail " .$th->getMessage() ; 
       }
       
    }
}










