<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    use HasFactory;

    public function task(){
        return $this->belongsTo(TaskCreation::class, 'task_id');
    }

    public function assigneeTask(){
        return $this->belongsTo(User::class, 'assignee');
    }

    public function assignForTask(){
        return $this->belongsTo(User::class, 'assign_for');
    }
}
