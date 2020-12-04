<?php

class Server
{
    private $serv;

    public function __construct()
    {
        $this->serv = new swoole_server("127.0.0.1", 9501);
        $this->serv->set(array(
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mode' => 1,
            'task_worker_num' => 8
        ));
        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));
// bind callback
        $this->serv->on('Task', array($this, 'onTask'));
        $this->serv->on('Finish', array($this, 'onFinish'));

        $this->serv->on('WorkerStart',function($server,$fd){
            echo "workerstart...\n";
            require_once 'boot.php';
        });
        $this->serv->start();
    }

    public function onStart($serv)
    {
        echo "Start\n";

    }


    public function onConnect($serv, $fd, $from_id)
    {
        echo "Client {$fd} connect\n";
    }

    public function onReceive(swoole_server $serv, $fd, $from_id, $data)
    {
        echo "Get Message From Client {$fd}:{$data}\n";
// send a task to task worker.
        $param = array(
            'fd' => $fd,
            'param'=>$data
        );
        $serv->task($param);
        echo "Continue Handle Worker\n";
    }

    public function onClose($serv, $fd, $from_id)
    {
        echo "Client {$fd} close connection\n";
    }

    public function onTask($serv, $task_id, $from_id, $data)
    {
        echo "This Task {$task_id} from Worker {$from_id}\n";

        print_r($data);
        $fd = $data['fd'];
       // $serv->send($fd, "Data in Task {$task_id}");
        return "Task {$task_id}'s result";
    }

    public function onFinish($serv, $task_id, $data)
    {
        echo "Task {$task_id} finish\n";
        echo "Result: {$data}\n";
    }
}

$server = new Server();