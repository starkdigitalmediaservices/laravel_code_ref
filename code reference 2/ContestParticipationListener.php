<?php

namespace App\Listeners;

use Log;
use Mail;
use Exception;
use App\Events\ContestParticipationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\SendContestParticipationMail;
use App\Mail\SendParticipationThanksMail;
use App\Models\EmailTemplate;

class ContestParticipationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ContestParticipationEvent  $event
     * @return void
     */
    public function handle(ContestParticipationEvent $event)
    {
        try {

            //Send mail to affiliate
            $strSubject = $email_content = '';
            $objEmailContent = EmailTemplate::where('template_type',9)->first();
            $strSubject = (isset($objEmailContent) && !empty($objEmailContent))? $objEmailContent->email_subject: 'Vizi365 | Participation Mail';

            $email_content = str_replace('[first_name]', $event->user->name, @$objEmailContent->email_text);
            $email_content = str_replace('[last_name]', $event->user->last_name, @$email_content);
            $email_content = str_replace('[contest_name]', $event->contest_info->title, @$email_content);

            if(isset($email_content) && $email_content != "")
            {
                $data =     [
                                'subject'         => $strSubject,
                                'email_content'   => @$email_content,
                                'admin_email'   =>  $event->admin_email_id,
                            ];

                if(isset($event->user) && isset($event->user->email))
                    Mail::to($event->user->email)->send(new SendParticipationThanksMail($data));
                else {
                        \Log::channel('daily')->info('--- Contest Participation Mail not send to affiliate - '.$user->name);
                        \Log::channel('daily')->info('--- Email address not found');
                    }
            }
            
            //Send mail to admin
            $strSubject = $email_content = '';
            $objEmailContent = EmailTemplate::where('template_type',8)->first();
            $strSubject = (isset($objEmailContent) && !empty($objEmailContent))? $objEmailContent->email_subject: 'Vizi365 | Participation Mail';

            $email_content = str_replace('[first_name]', $event->user->name, $objEmailContent->email_text);
            $email_content = str_replace('[last_name]', $event->user->last_name, $email_content);
            $email_content = str_replace('[contest_name]', $event->contest_info->title, $email_content);
            $send_to = explode(",",$objEmailContent->additional_email);

            if(!isset($objEmailContent->additional_email))
                $emails = [$event->admin_email_id];
            else
                $emails = array_merge([$event->admin_email_id], $send_to) ;

            ## remove extra white space from values
            $emails = array_map('trim', $emails);
            
            if(isset($email_content) && $email_content != "")
            {
                $data = [
                            'subject'         => $strSubject,
                            'email_content'   => $email_content,
                            'admin_email'   =>  $event->admin_email_id,
                        ];

                // Mail::to($event->admin_email_id)->send(new SendContestParticipationMail($data));
                if(isset($emails) && count($emails) > 0)
                    Mail::to($emails)->send(new SendContestParticipationMail($data));
                else {
                        \Log::channel('daily')->info('--- Contest Creation Mail not send to Admin');
                        \Log::channel('daily')->info('--- Email address not found');
                    }

            }
        } catch (Exception $e) {
            \Log::channel('daily')->info('--- Contest participation - Exception Occured when sending mail to admin and affiliate');
            \Log::channel('daily')->info('--- Error '. $e->getMessage());
        }
    }
}
