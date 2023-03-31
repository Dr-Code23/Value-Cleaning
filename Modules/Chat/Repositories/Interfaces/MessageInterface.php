<?php

namespace Modules\Chat\Repositories\Interfaces;

interface MessageInterface
{
    //send message
    public function sendMessage($request);

    //Delete message
    public function destroy($id);

    // get room messages
    public function room($request);

    // read Message
    public function readMessage($id);

    // create Room
    public function createRoom($request);

    // soft Delete
    public function getSoft();

    // get Room
    public function getRoom($request);

    // delete room
    public function deleteMessage($request);

    // latest message
    public function latest($request);
}






