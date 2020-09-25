<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MailSaverConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer:saver';

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
        $this->info('Initialization saver consumer.');
        $brokerUtillity = new \App\Services\Broker\BrokerService();
        $brokerUtillity->consumeQueue('get_mails_by_word','get_mails_by_word',[$this, 'callback'], 'consumer:saver');
    }

    public function callback(\PhpAmqpLib\Message\AMQPMessage $msg){
        try{
            $this->info(\Carbon\Carbon::now().": initialization process");
            $msg = json_decode($msg->body, true);
            $response = \App\Services\Mail\MailService::saveMails($msg);
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
