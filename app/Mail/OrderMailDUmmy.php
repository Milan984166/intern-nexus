<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderMessage;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderMessage)
    {
        $this->orderMessage = $orderMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(\App\Setting::where('id', 1)->first()->siteemail, \App\Setting::where('id', 1)->first()->sitetitle)->subject($this->orderMessage['subject'])->view('emails/order_mail');

        // return $this->view('view.name');
    }
}
