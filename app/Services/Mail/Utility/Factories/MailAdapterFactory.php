<?php
namespace App\Services\Mail\Utility\Factories;

class MailAdapterFactory
{   
    
    private static $instance;

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    

    private function __construct()
    {
    }

    public function getMailAdapter(){
        //por el momento solo soporta gmail
        return new \App\Services\Mail\Utility\Adapters\GmailAdapter();
    }

    private function __clone()
    {
    }
    private function __wakeup()
    {
    }

    
}