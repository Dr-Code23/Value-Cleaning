<?php

namespace Modules\Expenses\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Expenses\Http\Requests\TypeRequest;
use Modules\Expenses\Repositories\Interfaces\TypeInterface;

class TypeController extends Controller
{

    use ExpenseResponseTrait;

    protected $typeExpenses;

    public function __construct(TypeInterface $typeExpenses)
    {
        $this->typeExpenses = $typeExpenses;
        $this->middleware('permission:type-list|type-create|type-edit|type-delete');
        $this->middleware('permission:type-create', ['only' => ['store']]);
        $this->middleware('permission:type-edit', ['only' => ['update']]);
        $this->middleware('permission:type-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return $typeExpense = $this->typeExpenses->getType();
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
    public function store(Request $request)
    {
        $typeExpenses = $this->typeExpenses->storeType($request);
        if ($typeExpenses) {
            return $this->expenseResponse(($typeExpenses), 'type Expenses Saved', 200);
        }
        return $this->expenseResponse(null, 'type Expenses Not Save', 400);
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
        $typeExpenses = $this->typeExpenses->editType($request);
        if ($typeExpenses) {
            return $this->expenseResponse(($typeExpenses), 'type Expenses found', 200);
        }
        return $this->expenseResponse(null, 'type Expenses Not found', 400);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Application|ResponseFactory|Response
     */
    public function update(Request $request)
    {
        $typeExpenses = $this->typeExpenses->updateType($request);
        if ($typeExpenses) {
            return $this->expenseResponse(($typeExpenses), 'type Expenses update', 200);
        }
        return $this->expenseResponse(null, 'type Expenses Not update', 400);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Application|Response|ResponseFactory
     */
    public function destroy(Request $request)
    {
        $typeExpenses = $this->typeExpenses->destroy($request);
        if ($typeExpenses) {
            return $this->expenseResponse(($typeExpenses), 'type Expenses delete', 200);
        }
        return $this->expenseResponse(null, 'type Expenses Not delete', 400);
    }
}
