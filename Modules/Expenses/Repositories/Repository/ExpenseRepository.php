<?php
namespace Modules\Expenses\Repositories\Repository;
use Modules\Expenses\Entities\Expense;
use Modules\Expenses\Repositories\Interfaces\ExpenseInterface;

class ExpenseRepository implements ExpenseInterface
{


    public function editExpense($request)
    {
        $expenses = Expense::where('id', $request->id)->first();
        return $expenses;
    }

    public function storeExpense($request)
    {
        $expenses = new Expense();
        $expenses->name = $request->name;
        $expenses->money = $request->money;
        $expenses->date = $request->date;
        $expenses->notes = $request->notes;
        $expenses->type_id = $request->type_id;
        $expenses->save();
    }

    public function updateExpense($request)
    {
        $expenses = Expense::where('id', $request->id)->first();
        $expenses->name = $request->name;
        $expenses->money = $request->money;
        $expenses->date = $request->date;
        $expenses->notes = $request->notes;
        $expenses->type_id = $request->type_id;
        $expenses->save();
    }

    public function destroy($request)
    {
        $expenses = Expense::where('id', $request->id)->first();
        $expenses->delete();
    }

    public function getExpense()
    {
       return $expenses = Expense::all();
    }
}

