<?php


namespace Modules\Chat\Action;


use Illuminate\Http\Request;
use Modules\Chat\Entities\Message;
use Modules\Chat\Events\NewMessage;

class MessageSend
{

class ChatController extends Controller
{
    public function __construct (protected MessageSend $messagesend)
    {

    }

    public function sendmessage(Request $request)
    {
        $message = new Message();
        $message->message = $request->message;
        $message->sender_id = auth('api')->user();
        $message->type_sender =  $request->type_sender;
        $message->type_receiver = $request->type_receiver;
        $message->receiver_id = $request->receiver_id;
        $message->save();

    }

    public function get_all()
    {
        return $messages = Message::get();
    }
}
