<?php

namespace Modules\Review\Repositories\Interfaces;

interface WorkerReviewRepositoryInterface
{

    public function index();

    public function reviewStore($data);

    public function reviewUpdate($data,$id);

    public function show($id);

    public function destroy($id);


}






