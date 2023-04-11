<?php
namespace Modules\Expenses\Repositories\Repository;
use Modules\Expenses\Entities\TypeExpense;
use Illuminate\Support\Facades\Validator;
use Modules\Expenses\Repositories\Interfaces\TypeInterface;

class TypeRepository implements TypeInterface
{
    public function gettype()
    {
        return $typeExpense = TypeExpense::all();
    }

    public function editType($request)
    {
      return  $typeExpense = TypeExpense::where('id', $request->id)->first();
    }

    public function storeType($request)
    {
        $typeExpense = new TypeExpense();
        $typeExpense->name = $request->name;
        $typeExpense->notes = $request->notes;
        $typeExpense->save();
        return $typeExpense;
    }

    public function destroy($request)
    {
        $typeExpense = TypeExpense::where('id', $request->id)->first();
        $typeExpense->delete();
    }

    public function updateType($request)
    {
        $typeExpense = TypeExpense::where('id', $request->id)->first();
        $typeExpense->name = $request->name;
        $typeExpense->notes = $request->notes;
        $typeExpense->save();
        return $typeExpense;
    }
}
