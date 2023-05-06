<?php

namespace App\Jobs;

use App\Mail\SendMailToUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $emails;
    public $template;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emails, $template)
    {
        $this->emails = $emails;
        $this->template = $template;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // foreach ($this->emails as $email) {
            Mail::to($this->emails)->send(new SendMailToUser($this->template));
            sleep(10);
        // }
    }
}
