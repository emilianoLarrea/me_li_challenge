<?php
namespace App\Utilities;

class GeneralUtility{
    public static function returnError($errorResponse, $statusCode){
        if($errorResponse != []){
            echo json_encode($errorResponse);
            header("HTTP/1.1 ".$statusCode);
            header('Content-type: application/json');
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Origin, Content-Type, Content-Range, Content-Disposition, Content-Description, X-Auth-Token, sAccept, Authorization, X-Request-With');
            header('Access-Control-Allow-Origin: *');
            exit;
        }
    }
}