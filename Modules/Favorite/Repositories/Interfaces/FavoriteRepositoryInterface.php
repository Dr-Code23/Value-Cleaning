<?php

namespace Modules\Favorite\Repositories\Interfaces;

interface FavoriteRepositoryInterface
{

    public function index();

    public function create($data);


    public function show($id);



    public function delete($id);


}






