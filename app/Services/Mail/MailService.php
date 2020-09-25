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
                $brokerConnection->publishMessage('get_mails_by_word','meli_challenge', $brokerMessage);
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
    public static function saveMails($data){
        try{
            $mailAdapterFactory = \App\Services\Mail\Utility\Factories\MailAdapterFactory::getInstance();
            $mailAdapter = $mailAdapterFactory->getMailAdapter();
            $mailAdapter->init();
            $mailAdapter->login($data['email'], $data['password']);
            $mailAdapter->selectFolder($data['search_folder']);
            
            foreach($data['mails_uids'] as $uid){
                $getHeadersFromUidResponse = $mailAdapter->getHeadersFromUid($uid);
                if($getHeadersFromUidResponse['success'] != true){
                    continue;
                }
                $headers = $getHeadersFromUidResponse['data'];
                $payload = [];
                $payload['uid'] = $uid;
                $payload['subject'] = imap_utf8($headers['subject']);
                $payload['from'] = imap_utf8($headers['from']);
                $payload['fecha'] = new \Carbon\Carbon(strtotime($headers['date']));
                $mail = new \App\Email($payload);
                $mail->save();
                echo "UID: $uid saved \n";
            }
            $mailAdapter->close();
            
            $response = ['success'=>true, 'data'=>['mail_count' => count($data['mails_uids']), 'search_criteria' => $data['search_criteria']]];
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

    public static function get(){
        $data = \App\Email::get(); 
        $response = ['success'=>true, 'data'=>$data]; 
        return [
            'content'=>$response,
            'status' => 200
        ];
    }
    
}