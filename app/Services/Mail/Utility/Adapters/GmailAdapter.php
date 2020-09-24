<?php
namespace App\Services\Mail\Utility\Adapters;

class GmailAdapter implements \App\Services\Mail\Utility\Adapters\MailAdapterInterface{
    private $command_counter;

    private $fp;
    private $host;
    private $port;

    public $error;

    public $last_response = array();
    public $last_endline = "";

    function __construct(){
        $this->command_counter = "00000001";
        $this->host = "ssl://imap.gmail.com";
        $this->port = 993;
    } 

    public function init()
    {
        try{
            if (!($this->fp = fsockopen($this->host, $this->port, $errno, $errstr, 15))) {
                $this->error = "Could not connect to host ($errno) $errstr";
                $response = ['success'=>false, 'data'=>$this->error];
                \App\Utilities\GeneralUtility::returnError($response,404);
            }

            if (!stream_set_timeout($this->fp, 15)) {
                $this->error = "Could not set timeout";
                $response = ['success'=>false, 'data'=>$this->error];
                \App\Utilities\GeneralUtility::returnError($response,500);
            }

            $response = ['success'=>true, 'data'=>'OK'];
            return [
                'content'=>$response,
                'status' => 200
            ];
        }catch (\Exception $e) {
            $response = ['success'=>false, 'data'=>$e->getMessage()];
            \App\Utilities\GeneralUtility::returnError($response,500);
        } 
    }

    public function login($user, $password)
    {
        $this->command("LOGIN $user $password");

        if (preg_match('~^OK~', $this->last_endline)) {
            $response = ['success'=>true, 'data'=>'OK'];
            return [
                'content'=>$response,
                'status' => 200
            ];
        } else {
            $this->error = join(', ', $this->last_response);
            $this->close();
            $response = ['success'=>false, 'data'=>$this->error];
            \App\Utilities\GeneralUtility::returnError($response,401);
        }
    }

    public function selectFolder($folder)
    {
        $this->command("SELECT $folder");
        if (preg_match('~^OK~', $this->last_endline)) {
            $response = ['success'=>true, 'data'=>'OK'];
            return [
                'content'=>$response,
                'status' => 200
            ];
        } else {
            $this->error = join(', ', $this->last_response);
            $this->close();
            $response = ['success'=>false, 'data'=>$this->error];
            \App\Utilities\GeneralUtility::returnError($response,404);
        }
    }

    public function getUidsBySearch($criteria)
    {
        $this->command("SEARCH $criteria");
        if (preg_match('~^OK~', $this->last_endline) && is_array($this->last_response) && count($this->last_response) == 1) {
            $splitted_response = explode(' ', $this->last_response[0]);
            $uids              = array();

            foreach ($splitted_response as $item) {
                if (preg_match('~^\d+$~', $item)) {
                    $uids[] = $item;
                }
            }
            $response = ['success'=>true, 'data'=>$uids];
            return [
                'content'=>$response,
                'status' => 200
            ];
        } else {
            $this->error = join(', ', $this->last_response);
            $this->close();
            $response = ['success'=>false, 'data'=>$this->last_endline];
            \App\Utilities\GeneralUtility::returnError($response,500);
        }
    }

    public function getHeadersFromUid($uid)
    {
        $this->command("FETCH $uid BODY.PEEK[HEADER]");

        if (preg_match('~^OK~', $this->last_endline)) {
            array_shift($this->last_response);

            $headers    = array();
            $prev_match = '';
            foreach ($this->last_response as $item) {

                if (preg_match('~^([a-z][a-z0-9-_]+):~is', $item, $match)) {
                    $header_name           = strtolower($match[1]);
                    $prev_match            = $header_name;
                    $headers[$header_name] = trim(substr($item, strlen($header_name) + 1));
                } else {
                    $headers[$prev_match] .= " " . $item;
                }
            }
            $response = ['success'=>true, 'data'=>$headers];
            return [
                'content'=>$response,
                'status' => 200
            ];
        } else {
            $this->error = join(', ', $this->last_response);
            $this->close();
            $response = ['success'=>false, 'data'=>$this->error];
            \App\Utilities\GeneralUtility::returnError($response,500);
        }
    }

    private function command($command)
    {
        $this->last_response = array();
        $this->last_endline  = "";

        fwrite($this->fp, "$this->command_counter $command\r\n");

        while ($line = fgets($this->fp)) {
            $line = trim($line);

            $line_arr = preg_split('/\s+/', $line, 0, PREG_SPLIT_NO_EMPTY);
            if (count($line_arr) > 0) {
                $code = array_shift($line_arr);
                if (strtoupper($code) == $this->command_counter) {
                    $this->last_endline = join(' ', $line_arr);
                    break;
                } else {
                    $this->last_response[] = $line;
                }
            } else {
                $this->last_response[] = $line;
            }
        }

        $this->incrementCounter();
    }

    private function incrementCounter()
    {
        $this->command_counter = sprintf('%08d', intval($this->command_counter) + 1);
    }

    public function close()
    {
        fclose($this->fp);
    }

    
}