<?php
namespace App\Services\Broker;

class BrokerService{

    private $url;
    private $port;
    private $user;
    private $password;    

    function __construct()
    {
        $this->url = env('BROKER_URL');
        $this->port = env('BROKER_PORT');
        $this->user = env('BROKER_USER');
        $this->vhost = env('BROKER_VHOOST');
        $this->password = env('BROKER_PASSWORD');
    }

    public function publishMessage($queue,$exchange,$msg){
        $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection($this->url,$this->port,$this->user,$this->password,$this->vhost);
        $channel = $connection->channel();

        $channel->queue_declare($queue,false,true,false,false);
        $channel->exchange_declare($exchange,'direct',false,true,false);
        $channel->queue_bind($queue,$exchange);
        $msg = new \PhpAmqpLib\Message\AMQPMessage(json_encode($msg),['content_type' => 'application/json', 'delivery_mode' => \PhpAmqpLib\Message\AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $channel->basic_publish($msg,$exchange);
        $channel->close();
        $connection->close();
        $response = ['success'=>true, 'data'=>'OK']; 
        return [
            'content'=>$response,
            'status' => 200
        ];

    }

    public function consumeQueue($queue,$exchange,$callback,$consumer_name){
        $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection($this->url,$this->port,$this->user,$this->password,$this->vhost);
        $channel = $connection->channel();
        $channel->queue_declare($queue,false,true,false,false);
        $channel->exchange_declare($exchange,'direct',false,true,false);
        $channel->queue_bind($queue,$exchange);
        $channel->basic_consume($queue, $consumer_name, false, true, false, false, $callback);
        while ($channel->is_consuming()) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();

    }
}

?>