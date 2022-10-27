<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketController extends Controller implements MessageComponentInterface
{

    protected $clients;

    public function __construct()
    {
        $this->clients =  [];
    }

    public  function onOpen(ConnectionInterface $conn)
    {
        $this->clients[$conn->resourceId] = $conn;
        $this->sendHistory($conn->resourceId);
    }

    public function onClose(ConnectionInterface $conn)
    {
        $disconnectedId = $conn->resourceId;
        unset($this->clients[$disconnectedId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $userId = $this->clients[$conn->resourceId]->resourceId;
        echo "An error has occurred with user $userId: {$e->getMessage()}\n";
        unset($this->clients[$conn->resourceId]);
        $conn->close();
    }

    public function onMessage(ConnectionInterface $conn, $msg)
    {

        $data = json_decode($msg);

        $messageModel = new Messages();
        $created = $messageModel->store(['username' => ucfirst($data->username), 'message' =>  $data->message, 'user_uuid' => $data->uuid, 'user_id' => $data->user_id, 'connection_id' => $conn->resourceId]);
        if (!empty($created)) {

            $data = json_encode(['type' => 'message', 'message' =>  $created->message, 'username' => $created->username, 'user_id' => $data->user_id, 'created' => $created->created_at->format('d/m/Y H:i:s')]);
            foreach ($this->clients as $client) {
                $client->send($data);
            }
        }
    }

    private function sendHistory($id)
    {
        $messageModel = new Messages();
        $history = $messageModel->getAll()->toArray();
        $msg = json_encode([
            'type' => 'history',
            'history' => $history,
        ]);

        return $this->msgToUser($msg, $id);
    }

    private function msgToUser($msg, $id)
    {

        $this->clients[$id]->send($msg);
    }
}
