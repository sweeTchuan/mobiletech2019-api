<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use \Illuminate\Database\QueryException;

class ImageController extends Controller
{
    //
    function fn_uploadImage(Request $request){
        // $fileExt = $request['image']->getClientOriginalExtension();
        // $filename = chop($request->file('image')->getClientOriginalName(),".".$fileExt);
        // $timeStamp = getdate()['0'];
        // $filename = $filename . "-" . $timeStamp . "." . $fileExt;
        // public_path
        // resource_path
        // $path = $request->file('image')->move(storage_path("/haha2/") , $filename);
        $path = $request->file('image')->store('Images' ,'public');
        //$photoURL = url('/'.$filename);

        return response()->json($path);
    }
}
