<?php

namespace Modules\Worker\Repositories\Interfaces;

interface WorkerRepositoryInterface
{

    public function index();

    public function create($data);


    public function show($id);

    public function Update($data,$id);

    public function delete($id);


}






