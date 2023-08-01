<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\BaseController;
use App\Jobs\MailSenderJobs;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class TaskAssignmentController extends BaseController
{
    //Task assign data created
    public function assignTask(Request $request){

        DB::beginTransaction();
        try {
            if ($request->isJson()) {
                $input = $request->json()->all();
            } else {
                $input = $request->all();
            }

            //Validation Check
            $validator = Validator::make($input, [
                'assign_for' => 'required|exists:users,id',
                'task_id' => 'required|exists:task_creations,id|unique:task_assignments,id',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return $this->sendError('Validation Error.', $errors);

            } else {

                //Model Call and Data Save
                $taskCreation = new TaskAssignment();
                $taskCreation->task_id = $input['task_id'];
                $taskCreation->assignee = Auth::id();
                $taskCreation->assign_for = $input['assign_for'];
                $taskCreation->save();

                DB::commit();

                $taskCreation = TaskAssignment::with(['task','assigneeTask','assignForTask'])->where('id',$taskCreation->id)->first();

                //queue call for mail
                MailSenderJobs::dispatch($taskCreation->task->title,
                    $taskCreation->task->status,date('Y M d, h:i:s a', strtotime($taskCreation->task->duration)),
                    $taskCreation->task->description,
                    $taskCreation->assigneeTask->name,
                    $taskCreation->assigneeTask->email, $taskCreation->assignForTask->name,
                    $taskCreation->assignForTask->email)->onQueue('mail');

                return $this->sendResponse($taskCreation, 'Task Assign SuccessFully.');

            }

        } catch (\Exception $e) {
            $response =
                [
                    'status' => false,
                    'message' => 'Exception error.',
                    'data' => $e,
                    'code' => 400
                ];
            DB::rollback();
        }
        return $this->sendError($response, 'Exception error.');
    }

    //all data get
    public function listAll(Request $request)
    {
        $limit = $request->get('limit') ?  $request->get('limit') : 10; //per page count

        $results = DB::table('task_assignments as t2')
            ->select('t1.title', 'u1.name as assigne', 'u2.name as assignFor','t1.status','t1.duration')
            ->join('task_creations as t1', 't2.task_id', '=', 't1.id')
            ->join('users as u1', 't2.assignee', '=', 'u1.id')
            ->join('users as u2', 't2.assign_for', '=', 'u2.id')
            ->paginate($limit);

        return $this->sendResponse($results, 'All Task Assignment List');
    }

    //all data get by search keyword
    public function searchAll(Request $request){

        $term = $request->route('term');
        $limit = $request->get('limit') ?  $request->get('limit') : 10; //per page count


        $results = DB::table('task_assignments as t2')
            ->select('t1.title', 'u1.name as assigne', 'u2.name as assignFor','t1.status','t1.duration')
            ->join('task_creations as t1', 't2.task_id', '=', 't1.id')
            ->join('users as u1', 't2.assignee', '=', 'u1.id')
            ->join('users as u2', 't2.assign_for', '=', 'u2.id')
            ->where('t1.title', 'LIKE', '%' . $term . '%')
            ->orWhere('t1.status', 'LIKE', '%' . $term . '%')
            ->orWhere('u1.name', 'LIKE', '%' . $term . '%')
            ->orWhere('u2.name', 'LIKE', '%' . $term . '%')
            ->paginate($limit);

        return $this->sendResponse($results, 'All Task Assignment List');

    }

}

