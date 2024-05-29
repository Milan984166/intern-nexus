<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobApplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jobApplyMessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($jobApplyMessage)
    {
        $this->jobApplyMessage = $jobApplyMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        if (isset($this->jobApplyMessage['send_message_to']) && $this->jobApplyMessage['send_message_to'] == 'jobseeker') {
            return $this->from(\App\Setting::where('id', 1)->first()->siteemail, \App\Setting::where('id', 1)->first()->sitetitle)
                ->subject('Job Apply Email | Work Nepali')
                ->view('emails/job_apply_mail_to_jobseeker');
        }else{

            return $this->from(\App\Setting::where('id', 1)->first()->siteemail, \App\Setting::where('id', 1)->first()->sitetitle)
                ->subject($this->jobApplyMessage['job_title'].' - Job Apply Email | Work Nepali')
                ->view('emails/job_apply_mail_to_employer');
        }
        

        // return $this->view('view.name');
    }
}
