<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailController extends Controller
{
    public function getMailsByWord(Request $request){
        $payload = \App\Services\Mail\Utility\MailServiceUtility::validateData($request);
        $response = \App\Services\Mail\MailService::getMailsByWord($payload);
        return response()->json($response['content'], $response['status'])->header('Content-Type', 'application/json');
    }   

    public function get(){
        $response = \App\Services\Mail\MailService::get();
        return response()->json($response['content'], $response['status'])->header('Content-Type', 'application/json');
    }
}
