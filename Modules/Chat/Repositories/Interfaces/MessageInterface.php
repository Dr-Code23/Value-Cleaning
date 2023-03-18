<?php

namespace Modules\Chat\Repositories\Interfaces;

interface MessageInterface
{
    //send message
    public function sendMessage($request);

    //Delete message
    public function destroy($request);

    // get all Rooms
    public function getRooms();

    // check Room
    public function checkRoom($request);

    // read Message
    public function readMessage($id);

    // get Room
    public function room($request);

}






