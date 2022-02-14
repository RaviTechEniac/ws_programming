<?php

namespace App;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class WeOneWebSocketHandler implements MessageComponentInterface
{
     public function onOpen(ConnectionInterface $connection)
    {
        Config::set(['global.SOCKET_CONNECTION'=>'OPEN']);
        // TODO: Implement onOpen() method.
    }
    
    public function onClose(ConnectionInterface $connection)
    {
        Config::set(['global.SOCKET_CONNECTION'=>'CLOSE']);
        // TODO: Implement onClose() method.
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        // TODO: Implement onError() method.
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $msg)
    {
        // TODO: Implement onMessage() method.
    }
}