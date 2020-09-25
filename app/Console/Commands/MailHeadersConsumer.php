<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MailHeadersConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer:headers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Initialization headers consumer.');
        $brokerUtillity = new \App\Services\Broker\BrokerService();
        $brokerUtillity->consumeQueue('get_headers_mails_by_word','get_headers_mails_by_word',[$this, 'callback'], 'consumer:headers');
    }

    public function callback(\PhpAmqpLib\Message\AMQPMessage $msg){
        try{
            $this->info(\Carbon\Carbon::now().": initialization process");
            $msg = json_decode($msg->body, true);
            $response = \App\Services\Mail\MailService::getHeaders($msg);
            if($response['success'] != true){
                $this->error(\Carbon\Carbon::now().": data:".json_encode($response['data']));
            }else{
                $this->info(\Carbon\Carbon::now().": data: ".json_encode($response['data']));
                return;
            }
        }catch(\Exception $e){
            $this->error(\Carbon\Carbon::now().": data:".json_encode($e));
            return;
        }
    }
}
