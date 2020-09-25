<?php
namespace App\Services\Mail;

use Illuminate\Http\Request;


class MailService{

    public function __construct()
    {
    }

    public static function getMailsByWord($payload){
        try{
            $mailAdapterFactory = \App\Services\Mail\Utility\Factories\MailAdapterFactory::getInstance();
            $mailAdapter = $mailAdapterFactory->getMailAdapter();
            $mailAdapter->init();
            $mailAdapter->login($payload['email'], $payload['password']);
            $mailAdapter->selectFolder($payload['search_folder']);
            $mailsUidsResponse = $mailAdapter->getUidsBySearch('OR SUBJECT "'.$payload['search_criteria'].'" BODY "'.$payload['search_criteria'].'"');
            $mailAdapter->close();
            $mailsUids = $mailsUidsResponse['content']['data'];
            $mailsUidsCount = count($mailsUids);
            if($mailsUidsCount > 0){
                $brokerMessage = [
                    'email' => $payload['email'],
                    'password' => $payload['password'],
                    'search_folder' => $payload['search_folder'],
                    'search_criteria' => $payload['search_criteria'],
                    'mails_uids' => $mailsUids
                ];
                $brokerConnection = new \App\Services\Broker\BrokerService();
                $brokerConnection->publishMessage('get_headers_mails_by_word','get_headers_mails_by_word', $brokerMessage);                    
            }
            $response = ['success'=>true, 'data'=>'Getting and saving '.$mailsUidsCount.' mails.'];
            return [
                'content'=>$response,
                'status' => 200
            ];
        }catch (\Exception $e) {
            $response = ['success'=>false, 'data'=>$e->getMessage()];
            return [
                'content'=>$response,
                'status' => 500
            ];
        } 
    }

    public static function getHeaders($data){
        //echo json_encode($data);die;
        $brokerConnection = new \App\Services\Broker\BrokerService();
        $brokerMessage = [
            'email' => $data['email'],
            'password' => $data['password'],
            'search_folder' => $data['search_folder'],
            'search_criteria' => $data['search_criteria']
        ];
        foreach($data['mails_uids'] as $uid){
            $brokerMessage['mail_uid'] = $uid;
            $brokerConnection->publishMessage('get_mails_by_word','get_mails_by_word', $brokerMessage); 
            echo "uid processed: $uid \n";                   
        }
        return ['success'=>true, 'data'=>['redy to save' => count($data['mails_uids']).' mails', 'email' => $data['email'], 'search_criteria' => $data['search_criteria']]];;
    }

    public static function saveMails($data){
        try{
            $mailAdapterFactory = \App\Services\Mail\Utility\Factories\MailAdapterFactory::getInstance();
            $mailAdapter = $mailAdapterFactory->getMailAdapter();
            $mailAdapter->init();
            $mailAdapter->login($data['email'], $data['password']);
            $mailAdapter->selectFolder($data['search_folder']);
            $mails = [];
            
            $getHeadersFromUidResponse = $mailAdapter->getHeadersFromUid($data['mail_uid']);
            if($getHeadersFromUidResponse['success'] != true){
                $response = ['success'=>false, 'data'=>['message' => $data['mail_uid'].' not found.','from' => count($data['mails_uids']), 'search_criteria' => $data['search_criteria']]];
                return $response;
            }
            $headers = $getHeadersFromUidResponse['data'];
            $payload = [];
            $payload['uid'] = $data['mail_uid'];
            $payload['subject'] = imap_utf8($headers['subject']);
            $payload['from'] = imap_utf8($headers['from']);
            $payload['fecha'] = new \Carbon\Carbon(strtotime($headers['date']));
            
            $mailAdapter->close();
            if(!empty(\App\Email::create($payload))){
                $response = ['success'=>true, 'data'=>['saved' => $data['mail_uid'],'from' => $payload['from'], 'search_criteria' => $data['search_criteria']]];
            }else{
                $response = ['success'=>false, 'data'=>['message' => 'Cant save '.$data['mail_uid'],'from' => count($data['mails_uids']), 'search_criteria' => $data['search_criteria']]];
            }
            return $response;
        }catch (\Exception $e) {
            $response = ['success'=>false, 'data'=>$e->getMessage()];
            return [
                'content'=>$response,
                'status' => 500
            ];
        }
    }

    public static function get(){
        $data = \App\Email::get(); 
        $response = ['success'=>true, 'data'=>$data]; 
        return [
            'content'=>$response,
            'status' => 200
        ];
    }
    
}