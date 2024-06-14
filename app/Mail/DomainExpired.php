<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DomainExpired extends Mailable
{
    use Queueable, SerializesModels;

    public $domain;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.domainExpired')
                    ->with(['domain' => $this->domain]);
    }
}
