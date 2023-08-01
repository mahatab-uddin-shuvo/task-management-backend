<?php

namespace App\Jobs;

use App\Mail\AssignTaskMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MailSenderJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $taskName;
    public $taskStatus;
    public $taskDuration;
    public $taskDescription;
    public $assigneeUserName;
    public $assigneeUserEmail;
    public $assignForUserName;
    public $assignForUserEmail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($taskName,$taskStatus,$taskDuration,$taskDescription,$assigneeUserName,$assigneeUserEmail,$assignForUserName,$assignForUserEmail)
    {
        $this->taskName  = $taskName;
        $this->taskStatus  = $taskStatus;
        $this->taskDuration  = $taskDuration;
        $this->taskDescription  = $taskDescription;
        $this->assigneeUserName = $assigneeUserName;
        $this->assigneeUserEmail = $assigneeUserEmail;
        $this->assignForUserName = $assignForUserName;
        $this->assignForUserEmail = $assignForUserEmail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //mail call
        Mail::to($this->assignForUserEmail)->send(new AssignTaskMail($this->taskName,$this->taskStatus, $this->taskDuration,$this->taskDescription,$this->assigneeUserName,
            $this->assigneeUserEmail,$this->assignForUserName,$this->assignForUserEmail));

    }
}
