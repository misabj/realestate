<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data) {}

    public function build()
    {
        return $this->subject(__('Inquiry from website'))
        ->replyTo($this->data['email'] ?? config('mail.from.address'), $this->data['name'] ?? null)
        ->view('emails.contact')
        ->with([
            'name'        => $this->data['name'] ?? null,
            'emailFrom'   => $this->data['email'] ?? null,
            'phone'       => $this->data['phone'] ?? null,
            'userMessage' => $this->data['message'] ?? null,
        ]);
    }
}
