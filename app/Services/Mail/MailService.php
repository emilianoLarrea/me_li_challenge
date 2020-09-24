<?php
namespace App\Services\Mail;

use Illuminate\Http\Request;


class MailService{

    public function __construct()
    {
    }

    public static function getMailsByWord(Request $request){
        try{
            $mailAdapterFactory = \App\Services\Mail\Utility\Factories\MailAdapterFactory::getInstance();
            $mailAdapter = $mailAdapterFactory->getMailAdapter();
            $mailAdapter->init();
            $mailAdapter->login('emiliano18796@gmail.com', 'guitarra_18796@');
            $mailAdapter->selectFolder("INBOX");
            $getUidsBySearchResponse = $mailAdapter->getUidsBySearch('OR SUBJECT "prueba" BODY "prueba"');
            $ids = $getUidsBySearchResponse['content']['data'];
            $mails = [];
            foreach($ids as $id){
                $getHeadersFromUidResponse = $mailAdapter->getHeadersFromUid($id);
                $headers = $getHeadersFromUidResponse['content']['data'];
                $headers['subject'] = imap_utf8($headers['subject']);
                array_push($mails, $headers);
            }
            $mailAdapter->close();
            $response = ['success'=>true, 'data'=>$mails];
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
}