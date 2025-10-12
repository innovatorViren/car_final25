<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\{User};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
use Exception;
use Auth;

class UserApiController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // get login user profile data
    public function getUserProfile($id)
    {

        $fields = [
                'U.id as user_id',
                'U.email as email',
                'U.first_name as first_name',
                'U.middle_name as middle_name',
                'U.last_name as last_name',
                'U.mobile as mobile',
            ];


        $users = DB::table('users as U')->where('U.id', $id)
            ->select($fields)
            ->first();

        $toReturn = $users;
        $this->data = $toReturn;

        return $this->responseSuccess();
    }

    // change password of login user profile
    public function changeProfilePassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $login_user = $this->currentuser();

            $validator = Validator::make($request->all(), [
                'old_password' => 'required|min:6',
                'new_password' => 'required|min:6|same:password_confirmation',
                'password_confirmation' => 'required|min:6'
            ]);

            if ($validator->fails()) {
                $this->response_json['message'] = $validator->messages()->first();
                return $this->responseError();
            }

            $old_password = $request->old_password;
            $new_password = $request->new_password;
            $current_password = $login_user->password;

            $user = User::findOrFail($login_user->id);

            if (Hash::check($old_password, $current_password)) {
                $user->update(['password' => Hash::make($new_password)]);

                $this->response_json['message'] = 'Password Updated Successfully.';
            } else {
                $this->response_json['message'] = 'Please enter correct old password.';
                return $this->responseError();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            info($e);

            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

        return $this->responseSuccess();
    }

    // add firebase token after user login
    public function add_firebase_token(Request $request)
    {
        try {
            $user = $this->currentuser();

            if ($user) {
                $user->firebase_token = $request->fcm_token; // logged in fcm token for android
                // $user->platform = $request->platform; // logged in platform for android => 0 = android, 1 = ios
                $user->save();

                $employee_id = $user->emp_id;
                if($employee_id > 0){
                    Employee::where('id', $employee_id)->update(['device_token'=>$request->fcm_token]);
                }
            } else {
                return $this->tokenresponseError();
            }
        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

        return $this->responseSuccess();
    }

    // Add Notification Remainder To User Profile
    public function add_remainder(Request $request)
    {
        try {
            $user = $this->currentuser();

            if ($user) {
                $user->remainder_time = $request->remainder_time; // logged in fcm token for android
                // $user->platform = $request->platform; // logged in platform for android => 0 = android, 1 = ios
                $user->save();
            } else {
                return $this->tokenresponseError();
            }
        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

        return $this->responseSuccess();
    }
}
