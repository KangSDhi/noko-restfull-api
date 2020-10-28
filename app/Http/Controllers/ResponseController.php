<?php

namespace App\Http\Controllers;

class ResponseController extends Controller
{
    public static function successResponse($code, $status, $message){
        $resp = ["code" => $code, "status" => $status, "message" => $message];
        return $resp;
    }

    public static function successResponseWithData($data, $code, $status, $message)
    {
        $resp = ["code" => $code, "status" => $status,  "message" => $message, "data" => $data];
        return $resp;
    }

    public static function errorResponseWithData($data, $code, $status, $message){
        $resp = ["code" => $code, "status" => $status, "message" => $message, "data" => $data];
        return $resp;
    }

    public static function errorResponse($code, $status, $message){
        $resp = ["code" => $code, "status" => $status, "message" => $message];
        return $resp;
    }
}