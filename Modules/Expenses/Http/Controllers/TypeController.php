<?php

namespace Modules\Expenses\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
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
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return $typeExpenses = $this->typeExpenses->getType();
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
    public function store(TypeRequest $request)
    {
         $typeExpenses = $this->typeExpenses->storeType($request);
        if ($typeExpenses) {
            return $this->messageResponse(($typeExpenses), 'type Expenses Saved', 201);
        }
        return $this->messageResponse(null, 'type Expenses Not Save', 400);
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
         $typeExpenses = $this->typeExpenses->editType($request);
        if ($typeExpenses) {
            return $this->messageResponse(($typeExpenses), 'type Expenses found', 201);
        }
        return $this->messageResponse(null, 'type Expenses Not found', 400);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(TypeRequest $request)
    {
         $typeExpenses = $this->typeExpenses->updateType($request);
        if ($typeExpenses) {
            return $this->messageResponse(($typeExpenses), 'type Expenses update', 201);
        }
        return $this->messageResponse(null, 'type Expenses Not update', 400);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
         $typeExpenses = $this->typeExpenses->destroy($request);
        if ($typeExpenses) {
            return $this->messageResponse(($typeExpenses), 'type Expenses delete', 201);
        }
        return $this->messageResponse(null, 'type Expenses Not delete', 400);
    }
}
