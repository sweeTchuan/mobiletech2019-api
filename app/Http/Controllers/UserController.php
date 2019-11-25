<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use \Illuminate\Database\QueryException;
use Exception;

class UserController extends Controller
{
    //
    function fn_getUsers(){
        $a_data = User::all();

     //    echo '<pre>'; print_r($a_data); echo '</pre>';
     //    exit;

        return response()->json($a_data);
    }

    function fn_userAction(Request $request){
        $a_json = $request->all();

        $req_action = array_key_exists("action",$a_json) ? $a_json["action"] : "";

        if ($req_action == "create_user"){

            $a_data = $this->fn_createUser($a_json);

            return response()->json($a_data);


        }else if ($req_action == "login_user"){

            $a_data = $this->fn_loginUser($a_json);

            return response()->json($a_data);

        }else if ($req_action == "update_user"){

            $a_data =  $this->fn_updateUser($a_json);

            return response()->json($a_data);

        }else{
            return response()->json(['message'=> 'no action key in body']);
        }

    }

    function fn_createUser($a_json) {
        $status = 0;
        $message = "";
        $a_data = NULL;

        try {
            $a_data = User::create($a_json);

            $message = "Create User Success";
            $status = 1;

        } catch (QueryException $exception) {

            $message = $exception->errorInfo;
            $status = 0;

        }

        return $this->fn_responseMsg($status,$message,$a_data);

    }

    function fn_loginUser($a_json){
        $status = 0;
        $message = "";
        $a_data = NULL;

        try{
            $username = $a_json["username"];
            // $email = $a_json["email"];
            $password = $a_json["password"];

            try {
                $a_data = User::where('username', $username)
                ->where('password', $password)
                ->get();

                $status = (sizeof($a_data)== 1)? 1:0;
                $message = $status ? "Login User Success" : "User not found";

            } catch (QueryException $exception) {

                $message = $exception->errorInfo;
                $status = 0;

            }

        } catch (Exception $e) {
            $status = 0;
            $message  = $e->getMessage();
        }

        return $this->fn_responseMsg($status,$message,$a_data);
    }

    function fn_updateUser($a_json){

        $status = 0;
        $message = "";
        $a_data = NULL;

        try {
            $a_data = User::where('username', $a_json['username'])
            ->update(['email' => $a_json['email'], 'password' => $a_json['password']]);

            $status = $a_data;
            $message = $a_data ? "Update User Success" : "Update User Fail";

        } catch (QueryException $exception) {

            $message = $exception->errorInfo;
            $status = 0;

        }

        return $this->fn_responseMsg($status,$message);
    }

    function fn_changeUsername($a_json){}

    function fn_changePassword($a_json){}

    function fn_checkUsernameExist($a_json){}

    function fn_checkEmailExist($a_json){}

    function fn_responseMsg($status, $msg, $data=NULL){

        $json_res_msg = array(
            "status"=> $status,
            "messsage" => $msg ,
            "data" => $data,
        );

        return $json_res_msg;
    }

    function fn_testGetAPI(){


        // dd("hello");
        return response()->json(['message'=> 'testing Get API']);

    }

    function fn_testPostAPI(Request $request){

        return response()->json(['message'=> 'testing Post API']);

    }


}
