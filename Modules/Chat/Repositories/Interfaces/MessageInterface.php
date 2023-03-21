<?php

namespace Modules\Chat\Repositories\Interfaces;

interface MessageInterface
{
    //send message
    public function sendMessage($request);

    //Delete message
    public function destroy($request);

    // get room messages
    public function room($request);

    // read Message
    public function readMessage($id);

    // create Room
    public function createRoom($request);

     // get Room
     public function getRoom($request);

}






