<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $subject,$maildata=[],$siteSettings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$maildata,$siteSettings)
    {
        //
        $this->subject = $subject;
        $this->maildata = $maildata;
        $this->siteSettings = $siteSettings;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view('email.contactus');
    }
}
