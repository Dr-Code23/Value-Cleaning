<?php

namespace Modules\Expenses\Repositories\Repository;

use Illuminate\Support\Facades\DB;
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
        return $expenses;
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
        return $expenses;


    }

    public function destroy($request)
    {
        $expenses = Expense::where('id', $request->id)->first();
        $expenses->delete();
        return $expenses;

    }

    public function getExpense()
    {
        return $expenses = Expense::all();
    }

    public function search($request)
    {
//        $from = date('2018-01-01');
//        $to = date('2018-05-02');
        $from = $request->start;
        $to = $request->end;
        return Expense::whereBetween('date', [$from, $to])->get();
        $startDate = $request->start;
        $endDate = $request->end;
        return Expense::whereBetween(DB::raw('DATE(date)'), [$startDate, $endDate])->get();
//      return $expenses = Expense::whereBetween('date', [$from, $to])->get();
//        return $expenses;
    }
}

