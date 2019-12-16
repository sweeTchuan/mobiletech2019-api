<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use \Illuminate\Database\QueryException;
// use Exception;

class UserController extends Controller
{
    // sample
    function fn_getUsers(){
        $a_data = User::all();

     //    echo '<pre>'; print_r($a_data); echo '</pre>';
     //    exit;

        return response()->json($a_data);
    }

    // api call to this single function to perform selected action
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

        }else if ($req_action == "update_user_profile_pic"){

            $a_data =  $this->fn_updateProfilePic($request);

            return response()->json($a_data);

        }else if ($req_action == "update_user_profile_all"){

            $a_data =  $this->fn_updateProfileAll($request);

            return response()->json($a_data);

        }else{
            return response()->json($this->fn_responseMsg('no available function'));
        }

    }

    // insert new entry to user table
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

    // match input with user table for login
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

    // update user field without profile picture path
    function fn_updateUser(Request $request){
        if($request->has('id')){
            $a_json = $request->all();
            try {
                $a_data = User::where('id', $a_json['id'])
                ->update([
                    'username' => $a_json['username'],
                    'email' => $a_json['email'],
                    'name' => $a_json['name']
                    ]);

                $status = $a_data;
                $message = $a_data ? "Update User Success" : "Update User Fail";
                return $this->fn_responseMsg($message,$status);

            } catch (QueryException $exception) {

                $message = $exception->getMessage();
                return $this->fn_responseMsg($message);

            }
        }else{

            return $this->fn_responseMsg("fn_updateUser: Required Field incorrect");
        }
    }

    // update profile image and save image to storage
    public function fn_updateProfilePic(Request $request){

        if($request->hasFile('image_profile') && $request->has('username')){
            $path = $request->file('image_profile')->store('ProfileImages' , 'public');
            $a_json = $request->all();

            try {
                $a_data = User::where('username', $a_json['username'])
                ->update(['pic_path_name' => "$path"]);

                $status = $a_data;
                $message = $a_data ? "Update User Profile Pic Success" : "Update Profile Pic User Fail";

                return $this->fn_responseMsg($message,$status,$path);

            } catch (QueryException $exception) {
                // $message = $exception->errorInfo;
                $message = $exception->getMessage();
                return $this->fn_responseMsg($message);
            }

        }else{

            return $this->fn_responseMsg("fn_updateProfilePic: Required Field incorrect");
        }
    }

    // update user table for all field
    function fn_updateProfileAll(Request $request){


        $a_data2 =  $this->fn_updateUser($request);

        $check = $a_data2["status"];

        if($check == 1){
            $a_data =  $this->fn_updateProfilePic($request);
            $res = array(
                "op_profile_pic"=> $a_data,
                "op_profile" => $a_data2 ,
            );
            return $this->fn_responseMsg("Update All Operation done",1,$res);
        }else{
            return $this->fn_responseMsg("Update All Operation Fail",0);
        }


    }

    // future work
    function fn_changePassword($a_json){}

    // future work
    function fn_checkUsernameExist($a_json){}

    // future work
    function fn_checkEmailExist($a_json){}

    // custom http response msg
    function fn_responseMsg( $msg, $status = 0,$data=NULL){

        $json_res_msg = array(
            "status"=> $status,
            "messsage" => $msg ,
            "data" => $data,
        );

        return $json_res_msg;
    }


    // testing purposes
    function fn_testGetAPI(){

        // dd("hello");
        return response()->json(['message'=> 'testing Get API Success']);
    }

    // testing purposes
    function fn_testPostAPI(Request $request){

        $timeStamp = now();
        return response()->json($this->fn_responseMsg('testing POST API success',1));
    }


}
