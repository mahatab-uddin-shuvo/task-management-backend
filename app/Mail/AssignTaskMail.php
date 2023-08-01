<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssignTaskMail extends Mailable
{
    use Queueable, SerializesModels;

    public $taskName;
    public $taskStatus;
    public $taskDuration;
    public $taskDescription;
    public $assigneeUserName;
    public $assigneeUserEmail;
    public $assignForUserName;
    public $assignForUserEmail;

    /**
     * Create a new message instance.
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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $assignUser['taskName'] = $this->taskName;
        $assignUser['taskStatus'] = $this->taskStatus;
        $assignUser['taskDuration'] = $this->taskDuration;
        $assignUser['taskDescription'] = $this->taskDescription;
        $assignUser['assigneeUserName'] = $this->assigneeUserName;
        $assignUser['assigneeUserEmail'] = $this->assigneeUserEmail;
        $assignUser['name'] = $this->assignForUserName;
        $assignUser['email'] = $this->assignForUserEmail;

        return $this->subject('Task Assignment')
            ->view('assignUserForTask',['user' => $assignUser]);
    }
}
