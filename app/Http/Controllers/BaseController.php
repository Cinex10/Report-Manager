<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use validator;


class BaseController extends Controller
{




    public function sendResponse($request , $message)
    {
        $response = [

            "success" => true,
            "data" => $request,
            "message" => $message,

        ];

        return response()->json($response,200);
    }




    public function sendError($error , $errorMessage = [] )
    {
        $response = [

            "success" => false,
            "message" => $error

        ];

        if (!empty($errorMessage))
        {

            $response["data"]=$errorMessage;

        }

        return response()->json($response,404);
    }
}
