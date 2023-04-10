<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendMailRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $mailInfo;
    public $mailTo = [];
    public $mailBcc = [];
    public $mailCc = [];
    public $mailAttachment = [];
    public $subject;
    public $view;

    /**
     * Create a new message instance.
     */
    public function __construct(array $mailInfo = [])
    {
        $this->mailInfo = $mailInfo;
        $this->subject = $mailInfo['subject'] ?? '';
        $this->view = $mailInfo['view'] ?? '';
        $this->mailTo = $mailInfo['mailTo'] ?? [];
        $this->mailBcc = $mailInfo['mailBcc'] ?? [];
        $this->mailCc = $mailInfo['mailCc'] ?? [];
        $this->mailAttachment = $mailInfo['mailAttachement'] ?? [];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->to($this->mailTo)
            ->cc($this->mailCc)
            ->bcc($this->mailBcc)
            ->view('content_send_mail_request');
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Send Mail Request',
    //     );
    // }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
