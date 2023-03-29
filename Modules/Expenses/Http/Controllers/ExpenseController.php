<?php

namespace Modules\Expenses\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Expenses\Http\Requests\ExpenseRequest;
use Modules\Expenses\Repositories\Interfaces\ExpenseInterface;

class ExpenseController extends Controller
{

    use ExpenseResponseTrait;
    protected $expense;

    public function __construct(ExpenseInterface $expense)
    {
        $this->expense = $expense;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
       return $expense = $this->expense->getExpense();
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('expenses::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ExpenseRequest $request)
    {
         $expense = $this->expense->storeExpense($request);
        if ($expense) {
            return $this->messageResponse(($expense), 'expense Saved', 201);
        }
        return $this->messageResponse(null, 'expense Not Saved', 400);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('expenses::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request)
    {
         $expense = $this->expense->editExpense($request);
        if ($expense) {
            return $this->messageResponse(($expense), 'expense found', 201);
        }
        return $this->messageResponse(null, 'expense Not found', 400);
    }



    public function update(ExpenseRequest $request)
    {
         $expense = $this->expense->updateExpense($request);
        if ($expense) {
            return $this->messageResponse(($expense), 'expense Update', 201);
        }
        return $this->messageResponse(null, 'expense Not Update', 400);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
         $expense = $this->expense->destroy($request);
        if ($expense) {
            return $this->messageResponse(($expense), 'expense Deleted', 201);
        }
        return $this->messageResponse(null, 'expense Not Deleted', 400);
    }
}
