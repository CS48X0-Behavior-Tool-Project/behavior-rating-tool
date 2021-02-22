<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $options)
    {
        $this->user = $user;
        $this->welcome_mail_data = $options;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to BRT!')->markdown('emails.welcome', ['mail_data' => $this->welcome_mail_data])
          ->with(['link' => $this->buildLink()]);
    }

    /**
    * Create the link holding the user's login token.
    */
    protected function buildLink() {
      return url('/confirmation/'. $this->user->token->token . '?' . http_build_query($this->welcome_mail_data));
    }
}
