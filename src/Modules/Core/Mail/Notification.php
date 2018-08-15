<?php

namespace RefinedDigital\CMS\Modules\Core\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    protected $settings;
    public $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
        $this->body = $settings->body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this
                ->markdown('core::emails.notification')
                ->subject($this->settings->subject)
        ;

        if(isset($this->settings->cc)) {
            $data->cc($this->settings->cc);
        }
        if(isset($this->settings->bcc)) {
            $data->bcc($this->settings->bcc);
        }
        if(isset($this->settings->replyTo)) {
            $data->replyTo($this->settings->replyTo);
        }

        if (isset($this->settings->files) && is_array($this->settings->files)) {
            foreach ($this->settings->files as $file) {
                $data->attach($file->getRealPath(), [
                    'as'    => $file->getClientOriginalName(),
                    'mime'  => $file->getMimeType(),
                ]);
            }
        }

        return $data;
    }
}
