<?php

namespace App\Http\Controllers;

use App\Traits\Api;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketController extends Controller implements MessageComponentInterface
{

    use Api;


    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public  function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onClose(ConnectionInterface $conn)
    {
        $disconnectedId = $conn->resourceId;
        unset($this->connections[$disconnectedId]);
        foreach ($this->connections as &$connection)
            $connection['conn']->send(json_encode([
                'offline_user' => $disconnectedId,
                'from_user_id' => 'server control',
                'from_resource_id' => null
            ]));
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $userId = $this->connections[$conn->resourceId]['user_id'];
        echo "An error has occurred with user $userId: {$e->getMessage()}\n";
        unset($this->connections[$conn->resourceId]);
        $conn->close();
    }

    public function onMessage(ConnectionInterface $conn, $msg)
    {

        $data = json_decode($msg);

        foreach ($this->clients as $client) {
            $client->send(json_encode(['message' =>  $data->message]));
        }
    }
}
