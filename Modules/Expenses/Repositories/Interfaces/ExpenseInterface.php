<?php

namespace Modules\Expenses\Repositories\Interfaces;

interface ExpenseInterface
{
    // edit Expense
    public function editExpense($request);

    // get Expense
    public function getExpense();

    // store Expense
    public function storeExpense($request);

    //Delete Expense
    public function destroy($request);

    // update Expense
    public function updateExpense($request);

    // search
    public function search($request);
}






