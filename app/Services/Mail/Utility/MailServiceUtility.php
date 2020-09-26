<?php
namespace App\Services\Mail\Utility;

//use Carbon\Carbon;
use App\Contacto;


class MailServiceUtility{
    
    public static function validateData($data){
        $errors = [];
        if(!filter_var($data->email, FILTER_VALIDATE_EMAIL)){ 
            array_push($errors, ["path" => 'email', 'message'=> 'The email is not in valid format.']);
        }
        if(strlen($data->password) < 8){ 
            array_push($errors, ["path" => 'password', 'message'=> 'The password should not be empty.']);
        }
        if(!$data['search_criteria'] OR strlen($data['search_criteria']) <= 0){
            $data['search_criteria'] = "DevOps";
        }elseif(strlen($data['search_criteria']) > 1024){
            array_push($errors, ["path" => 'search_criteria', 'message'=> 'Search criteria allows up to 1024 characters.']);
        }  
        if(!$data['search_folder'] OR strlen($data['search_folder']) <= 0){
            $data['search_folder'] = "INBOX";
        }elseif(strlen($data['search_folder']) > 1024){
            array_push($errors, ["path" => 'search_folder', 'message'=> 'Search folder allows up to 1024 characters.']);
        }  
        if($errors != []){
            $response = ['success'=>false, 'data'=>$errors];
            \App\Utilities\GeneralUtility::returnError($response,400);
        }
        return [
            'email' => $data->email,
            'password' => $data->password,
            'search_criteria' => $data->search_criteria,
            'search_folder' => $data->search_folder
        ];
    }
}