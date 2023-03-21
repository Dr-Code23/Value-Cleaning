<?php


namespace Modules\Chat\Http\Controllers;


trait   MessageResponseTrait
{
    public function messageResponse($data= null,$message = null,$status = null)
    {
        $array = [
            'data' => $data,
            'message' => $message,
            'status' => $status,
        ];
        return response($array, $status);
    }
}
