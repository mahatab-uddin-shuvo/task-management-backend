<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\BaseController;
use App\Models\TaskAssignment;
use App\Models\TaskCreation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class TaskCreationController extends BaseController
{
    //data created
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->isJson()) {
                $input = $request->json()->all();
            } else {
                $input = $request->all();
            }

            //validation check
            $validator = Validator::make($input, [
                'title' => 'required|string',
                'status' => 'required|in:Open,In-progress,Done',
                'description' => 'required',
                'duration' => 'required|date_format:Y-m-d H:i:s',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return $this->sendError('Validation Error.', $errors);

            } else {

                //Model Call and data save
                $taskCreation = new TaskCreation();
                $taskCreation->title = $input['title'];
                $taskCreation->status = $input['status'];
                $taskCreation->description = $input['description'];
                $taskCreation->duration = $input['duration'];
                $taskCreation->created_by = Auth::id();
                $taskCreation->save();

                DB::commit();

                return $this->sendResponse($taskCreation, 'Task Creation Created SuccessFully.');

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
        $taskCreation = TaskCreation::paginate($limit);
        return $this->sendResponse($taskCreation, 'All Task Creation List');
    }

    //id wise data get
    public function details(Request $request)
    {
        $id = $request->route('id');
        $taskCreation = TaskCreation::where('id', $id)->firstOrFail();
        return $this->sendResponse($taskCreation, 'Task Creation Data Read Successfully.');
    }

    //specific key list search
    public function searchAll(Request $request)
    {
        $term = $request->route('term');

        $limit = $request->get('limit') ?  $request->get('limit') : 10;

        $searchResults = TaskCreation::where('title', 'LIKE', '%' . $term . '%')
            ->orwhere('status', 'LIKE', '%' . $term . '%')
            ->orwhere('description', 'LIKE', '%' . $term . '%');

        $searchResults = $searchResults->paginate($limit);

        return $this->sendResponse($searchResults, 'Task Creation search read successfully.');
    }

    //data updated
    public function update(Request $request)
    {
        DB::beginTransaction();
        try{

            if ($request->isJson()) {
                $input = $request->json()->all();
            } else {
                $input = $request->all();
            }

            $validator = Validator::make($input, [
                'title' => 'required|string',
                'status' => 'required|in:Open,In-progress,Done',
                'description' => 'required',
                'duration' => 'required|date_format:Y-m-d H:i:s',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return $this->sendError('Validation Error.', $errors);

            } else {
                $id = $request->route('id');

                $taskCreation = TaskCreation::find($id);

                $taskCreation->title = $input['title'];
                $taskCreation->status = $input['status'];
                $taskCreation->description = $input['description'];
                $taskCreation->duration = $input['duration'];
                $taskCreation->created_by = Auth::id();

                $taskCreation->update();

                DB::commit();

                return $this->sendResponse($taskCreation, 'Task Creation Updated SuccessFully.');
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

    // task creation title search
    public function search(Request $request)
    {
        $term = $request->route('term');
        $limit = $request->get('limit') ?  $request->get('limit') : 10;

        $searchResults = TaskCreation::where('title', 'LIKE', '%' . $term . '%');

        $searchResults = $searchResults->paginate($limit);

        return $this->sendResponse($searchResults, 'Task Creation search read successfully.');
    }

    //task creation status change
    public function status(Request $request)
    {
        if($request->isJson()) {
            $input = $request->json()->all();
        } else {
            $input = $request->all();
        }
        $validator = Validator::make($input, [
            'status' => 'required|in:Open,In-progress,Done',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->sendError('Validation Error.', $errors);

        }
        $id = $request->route('id');

        TaskCreation::where('id', $id)->update([
            'status' => $input['status'],
        ]);

        return $this->sendResponse('', 'Task Creation status update Successfully.');
    }

}
