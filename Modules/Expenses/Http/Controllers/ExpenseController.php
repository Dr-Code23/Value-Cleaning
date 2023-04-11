<?php

namespace Modules\Expenses\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Expenses\Http\Requests\ExpenseRequest;
use Modules\Expenses\Repositories\Interfaces\ExpenseInterface;

class ExpenseController extends Controller
{

    use ExpenseResponseTrait;

    protected $expense;

    public function __construct(ExpenseInterface $expense)
    {
        $this->middleware('permission:expense-list|expense-create|expense-edit|expense-delete');
        $this->middleware('permission:expense-create', ['only' => ['store']]);
        $this->middleware('permission:expense-edit', ['only' => ['update']]);
        $this->middleware('permission:expense-delete', ['only' => ['destroy']]);
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
     * @return Application|ResponseFactory|Response
     */
    public function store(ExpenseRequest $request)
    {
        $expense = $this->expense->storeExpense($request);
        if ($expense) {
            return $this->expenseResponse(($expense), 'expense Saved', 200);
        }
        return $this->expenseResponse(null, 'expense Not Saved', 400);
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
     * @return Application|ResponseFactory|Response
     */
    public function edit(Request $request)
    {
        $expense = $this->expense->editExpense($request);
        if ($expense) {
            return $this->expenseResponse(($expense), 'expense found', 200);
        }
        return $this->expenseResponse(null, 'expense Not found', 400);
    }


    public function update(ExpenseRequest $request)
    {
        $expense = $this->expense->updateExpense($request);
        if ($expense) {


            return $this->expenseResponse(($expense), 'expense Update', 200);
        }
        return $this->expenseResponse(null, 'expense Not Update', 400);
    }

    public function search(Request $request)
    {
        return $expense = $this->expense->search($request);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Application|ResponseFactory|Response
     */
    public function destroy(Request $request)
    {
        $expense = $this->expense->destroy($request);
        if ($expense) {
            return $this->expenseResponse(($expense), 'expense Deleted', 200);
        }
        return $this->expenseResponse(null, 'expense Not Deleted', 400);
    }
}
