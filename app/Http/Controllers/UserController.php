<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use \Illuminate\Database\QueryException;
// use Exception;

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

        $req_action = $request->has("action") ? $request["action"] : null ;

        if($req_action == null){
            return response()->json($this->fn_responseMsg('action key not found'));
        }

        if ($req_action == "create_user"){

            $a_data = $this->fn_createUser($request);

            return response()->json($a_data);


        }else if ($req_action == "login_user"){

            $a_data = $this->fn_loginUser($request);

            return response()->json($a_data);

        }else if ($req_action == "update_user"){

            $a_data =  $this->fn_updateUser($request);

            return response()->json($a_data);

        }else{
            return response()->json($this->fn_responseMsg('no available functiony'));
        }

    }

    function fn_createUser(Request $request) {

        if($request->has("username") && $request->has("email") && $request->has("password") ){
            $a_json = $request->all();
            try {
                $a_data = User::create($a_json);

                $message = "Create User Success";

                return $this->fn_responseMsg($message,1,$a_data);

            } catch (QueryException $exception) {

                // $message = $exception->errorInfo;
                $message = $exception->getMessage();

                return $this->fn_responseMsg($message);

            }

        }else{
            return $this->fn_responseMsg("fn_createUser: Required Field incorrect");
        }

    }

    function fn_loginUser(Request $request){
        if($request->has("username") && $request->has("password") ){
            $a_json = $request->all();
            $username = $a_json["username"];
            // $email = $a_json["email"];
            $password = $a_json["password"];

            try {
                $a_data = User::where('username', $username)
                ->where('password', $password)
                ->get();

                $status = (sizeof($a_data)== 1)? 1:0;
                $message = $status ? "Login User Success" : "Login User Failed ";
                return $this->fn_responseMsg($message,$status,$a_data);

            } catch (QueryException $exception) {

                $message = $exception->getMessage();
                return $this->fn_responseMsg($message);
            }

        }else{
            return $this->fn_responseMsg("fn_loginUser: Required Field incorrect");
        }

    }

    function fn_updateUser(Request $request){

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

    function fn_responseMsg( $msg, $status = 0,$data=NULL){

        $json_res_msg = array(
            "status"=> $status,
            "messsage" => $msg ,
            "data" => $data,
        );

        return $json_res_msg;
    }

    function fn_testGetAPI(){

        // dd("hello");
        return response()->json(['message'=> 'testing Get API Success']);
    }

    function fn_testPostAPI(Request $request){

        $timeStamp = now();
        return response()->json($this->fn_responseMsg('testing POST API success',1));
    }


}
