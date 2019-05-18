<?php

namespace Modules\Report\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Event\Models\Event;

class DiaryReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \Modules\Event\Models\Event
     */
    public $event;

    /**
     * @var array
     */
    public $url = [
        'link' => 'https://tikket.com.br',
        'text' => 'Relatório diário',
    ];

    /**
     * @var array
     */
    public $image = [
        'source' => 'https://d35c048n9fix3e.cloudfront.net/images/undraw/png/undraw_super_thank_you.png',
        'text'   => 'Relatório diário',
    ];

    /**
     * @var string
     */
    public $subject = 'Relatório diário';

    /**
     * @var string
     */
    public $description = 'Relatório diário.';


    /**
     * Create a new message instance.
     *
     * @param  \Modules\Event\Models\Event  $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.report.diary-report');
    }
}
