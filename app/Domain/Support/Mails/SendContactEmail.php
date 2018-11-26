<?php

namespace Smartville\Domain\Support\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendContactEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The name of the sender.
     *
     * @var $name
     */
    public $name;

    /**
     * The address of the sender.
     *
     * @var $email
     */
    public $email;

    /**
     * The email's message.
     *
     * @var $message
     */
    public $message;


    /**
     * Create a new message instance.
     *
     * @param $name
     * @param $email
     * @param $subject
     * @param $message
     */
    public function __construct($name, $email, $subject, $message)
    {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.support.contact');
    }
}
