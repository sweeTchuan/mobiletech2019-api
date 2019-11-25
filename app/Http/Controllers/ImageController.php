<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    //
    function fn_uploadImage(Request $request){

        $filename = $request['filename'];
        // public_path
        // resource_path
        // $path = $request->file('image')->move(storage_path("/haha2/") , $filename);
        $path = $request->file('image')->store('haha3' ,'public');
        //$photoURL = url('/'.$filename);

        return response()->json($path);
    }
}
