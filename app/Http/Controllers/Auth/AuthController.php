<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{


    public function register(Request $request){

        DB::beginTransaction();
        try {
            if ($request->isJson()) {
                $input = $request->json()->all();
            } else {
                $input = $request->all();
            }

            //validation check
            $validator = Validator::make($input, [
                'email' => 'required|email|unique:users,email',
                'name' => 'required|string',
                'password' => 'required|string',
                'confirm_password' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return $this->sendError('Validation Error.', $errors);
            }

            //model call then data save
            $user = new User();
            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->password = Hash::make($input['password']);
            $user->save();

            DB::commit();

            return $this->sendResponse($user, 'User Registered SuccessFully.');

        }catch (\Exception $e) {
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


    public function login(Request $request){

        if ($request->isJson()) {
            $input = $request->json()->all();
        } else {
            $input = $request->all();
        }

        //validation check
        $validator = Validator::make($input, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->sendError('Validation Error.', $errors);
        }

        if (Auth::attempt(["email" => $input['email'], "password" => $input['password']])) {
            $user = Auth::user();
            $user["token"] = $user->createToken('accessToken')->accessToken;

            return $this->sendResponse($user, 'Login successfully.');
        }
        else {
            //Credential check
            $error['message'] = "You Credential is incorrect.";
            $error['code'] = "AUTHENTICATION_ERROR";
            return $this->sendError('Logical Error.', $error);
        }

    }

    public function logout()
    {
        $token = Auth::user()->token()->revoke();
        return $this->sendResponse('', 'Logged out.');
    }

    //user search
    public function search(Request $request)
    {
        $term = $request->route('term');
        $limit = $request->get('limit') ?  $request->get('limit') : 10;

        $searchResults = User::where('name', 'LIKE', '%' . $term . '%');

        $searchResults = $searchResults->paginate($limit);

        return $this->sendResponse($searchResults, 'User search read successfully.');
    }

    //user details
    public function details(Request $request)
    {
        $id = $request->route('id');
        $taskCreation = User::where('id', $id)->firstOrFail();

        return $this->sendResponse($taskCreation, 'User Data Read Successfully.');
    }
}
