<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($verificationCode, $subject)
    {
        $this->mailData = [
            'title' => $subject,
            'body' => "Your verification code is: $verificationCode\n\nThis code will expire in 15 minutes. If you did not request this change, please ignore this email or contact our support team immediately."
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->mailData['title'])
            ->view('mails.sendGmail')
            ->with('mailData', $this->mailData);
    }
}