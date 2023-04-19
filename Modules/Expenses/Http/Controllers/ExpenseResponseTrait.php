<?php


namespace Modules\Expenses\Http\Controllers;


trait ExpenseResponseTrait
{
    public function expenseResponse($data= null,$message = null,$status = null)
    {
        $array = [
            'data' => $data,
            'message' => $message,
            'status' => $status,
        ];
        return response($array, $status);
    }
}
