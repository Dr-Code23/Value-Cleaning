<?php

namespace Modules\Favorite\Repositories\Interfaces;

interface FavoriteRepositoryInterface
{

    public function index();

    public function store($data);


    public function show($id);


    public function destroy($id);


}






