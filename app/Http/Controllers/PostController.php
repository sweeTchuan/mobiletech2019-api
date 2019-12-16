<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use \Illuminate\Database\QueryException;
// use Exception;

class PostController extends Controller
{
    public function index()
    {
        $posts = \App\Post::all();

        return response()->json($posts);
    }

    // load all post table entry
    public function fn_getPosts()
    {
        // $posts = \App\Post::all();
        $posts = \App\Post::with('user')->orderBy('created_at','desc')->get();

        $a_data = $this->fn_responseMsg("Get all posts",1,$posts);

        return response()->json($a_data);
    }

    // creating a new entry in post table
    public function fn_newPost(Request $request){

        if($request->hasFile('image') && $request->has('caption') && $request->has('user_id')){
            $path = $request->file('image')->store('Images' , 'public');

            $a_json = array(

                "user_id"=>$request['user_id'],
                "caption"=> $request['caption'],
                "picture_url" => "$path" ,
            );

            try {

                $a_data = Post::create($a_json);
                $message = 'Add New Post Success';

                return $this->fn_responseMsg($message,1,$a_data);

            } catch (QueryException $exception) {
                // $message = $exception->errorInfo;
                $message = $exception->getMessage();
                return $this->fn_responseMsg($message);
            }

        }else if($request->has('caption') && $request->has('user_id')){
            $a_json = array(

                "user_id"=>$request['user_id'],
                "caption"=> $request['caption'],
                "picture_url" => "defaultpic" ,
            );

            try {

                $a_data = Post::create($a_json);
                $message = 'Add New Post no pic Success';

                return $this->fn_responseMsg($message,1,$a_data);

            } catch (QueryException $exception) {

                // $message = $exception->errorInfo;
                $message = $exception->getMessage();
                return $this->fn_responseMsg($message);
            }

        }else{

            return $this->fn_responseMsg("fn_newPost: Required Field incorrect");
        }
    }

    // custom http response msg
    function fn_responseMsg( $msg, $status = 0,$data=NULL){

        $json_res_msg = array(
            "status"=> $status,
            "messsage" => $msg ,
            "data" => $data,
        );

        return $json_res_msg;
    }

    // public function fn_showAlltPostsWithUser()
    // {
    //     // $posts = \App\Post::all();
    //     // $posts = \App\Post::join('users', 'posts.user_id', '=', 'users.id')->get();
    //     $posts = \App\Post::with('user')->get();

    //     $a_data = $this->fn_responseMsg("Get all posts users",1,$posts);

    //     return response()->json($a_data);
    // }
}
