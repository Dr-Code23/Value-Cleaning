<?php

namespace Modules\Chat\Repositories\Interfaces;

interface MessageInterface
{
    //send message
    public function sendMessage($request);

    //Delete message
    public function destroy($id);

    // All Rooms Message
    public function allRoom();

    // get Room User
    public function getRoomUser();

     // check user or admin
    public  function checkUser();
}






