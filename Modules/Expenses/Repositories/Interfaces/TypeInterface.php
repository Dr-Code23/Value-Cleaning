<?php
namespace Modules\Expenses\Repositories\Interfaces;


interface TypeInterface
{

    // edit Type
    public function editType($request);

    // store Type
    public function storeType($request);

    //Delete Type
    public function destroy($request);

    //get Type
    public function gettype();

    // update Type
    public function updateType($request);
}
