<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Domain;

class DomainExpired extends Mailable
{
    use Queueable, SerializesModels;

    public $domain;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Domain $domain)
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
        return $this->view('emails.domain_expired')
                    ->subject('Alerte d\'expiration de domaine');
    }
}
