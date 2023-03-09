<?php

namespace Modules\Worker\Repositories\Interfaces;

interface WorkerRepositoryInterface
{

    public function index($data);

    public function store($data);

    public function tasks($id);
    public function show($id);

    public function update($data,$id);

    public function destory($id);


}






