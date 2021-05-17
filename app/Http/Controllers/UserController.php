<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        try{
            $getAllUser = User::paginate(10);
            return $this->sendResponse($getAllUser, "All user");
        } catch (\Exception $e) {
            return $this->sendBadRequest(null, "Something went to be wrong. Please try again later",400);
        }
    }

    public function store(Request $request)
    {
        try{
            $input = $request->all();
            $rule = array(
                'firstname' => 'required',
                'lastname' => 'required',
                'adhar_card' => 'required|size:12|unique:users,adhar_card',
                'dob' => 'required|before:today'
            );
            $messages = array();
            $validation = Validator::make($input, $rule, $messages);
            if ($validation->fails()) {
                return $this->sendBadRequest(NULL, $validation->errors(), 422);
            }
            $user = new User();
            $user->firstname = trim($request->firstname);
            $user->lastname = trim($request->lastname);
            $user->adhar_card = $request->adhar_card;
            $user->dob = $request->dob;
            $user->gender = $request->gender == "male" ? 1 : 0;
            $user->father_id = $request->father_id;
            $user->mother_id = $request->mother_id;
            $user->save();
            return $this->sendResponse($user, "User added successfully done");
        } catch (\Exception $e) {
            return $e->getMessage();
            return $this->sendBadRequest(null, "Something went to be wrong. Please try again later",400);
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $input = $request->all();
            $rule = array(
                'firstname' => 'required',
                'lastname' => 'required',
                'adhar_card' => 'required|size:12|unique:users,adhar_card,' .$id,
                'dob' => 'required|date_format:Y-m-d|before:today'
            );
            $messages = array();
            $validation = Validator::make($input, $rule, $messages);
            if ($validation->fails()) {
                return $this->sendBadRequest(NULL, $validation->errors()->first(), 422);
            }

            $userData = User::where('id',$id);
            $user['firstname'] = trim($request->firstname);
            $user['lastname'] = trim($request->lastname);
            $user['adhar_card'] = $request->adhar_card;
            $user['dob'] = $request->dob;
            $user['gender'] = $request->gender == "male" ? 1 : 0 ;
            $user['father_id'] = $request->father_id;
            $user['mother_id'] = $request->mother_id;
            $userData->update($user);
            return $this->sendResponse($userData, "User update");
        } catch (\Exception $e) {
            //dd($e->getMessage());
            return $this->sendBadRequest(null, "Something went to be wrong. Please try again later",400);
        } 
    }

    public function userDetails($id)
    {
        try{
            $user = User::where('id',$id)->first();
            if($user){
                if($user->gender == 0) {
                    $user['children'] = User::where('mother_id', $user->id)->get();
                } else {
                    $user['children'] = User::where('father_id', $user->id)->get();
                }
                return $this->sendResponse($user, "User update");
            }else{
                return $this->sendBadRequest(NULL, "User  not found", 400);
            }
        } catch (\Exception $e) {
            return $this->sendBadRequest(null, "Something went to be wrong. Please try again later",400);
        }
    }

    public function parentDetails()
    {
        try{
            $data = array();
            $data['father'] = User::where('gender','1')->get();
            $data['mother'] = User::where('gender','0')->get();
            return $this->sendResponse($data, "User update");
        } catch (\Exception $e) {
            return $this->sendBadRequest(null, "Something went to be wrong. Please try again later",400);
        }
    }
}
