<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailController extends Controller
{
    public function getMailsByWord(Request $request){
        //$payload = CampaniaMensajeUtility::getMailsByWord($request);
        $response = \App\Services\Mail\MailService::getMailsByWord($request);
        return response()->json($response['content'], $response['status'])->header('Content-Type', 'application/json');
    }   
}
