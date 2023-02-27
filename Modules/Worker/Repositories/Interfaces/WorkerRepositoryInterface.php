<?php

namespace Modules\Worker\Repositories\Interfaces;

interface WorkerRepositoryInterface
{

    public function index();

    public function store($data);

    public function tasks($id);
    public function show($id);

    public function Update($data,$id);

    public function destory($id);


}






