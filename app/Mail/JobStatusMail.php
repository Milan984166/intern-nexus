<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jobStatusMessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($jobStatusMessage)
    {
        $this->jobStatusMessage = $jobStatusMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(\App\Setting::where('id', 1)->first()->siteemail, \App\Setting::where('id', 1)->first()->sitetitle)
                ->subject('Job Mail | Work Nepali')
                ->view('emails/job_status_mail_to_jobseeker');
    }
}
