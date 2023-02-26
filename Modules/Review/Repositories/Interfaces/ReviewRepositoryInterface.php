<?php

namespace Modules\Review\Repositories\Interfaces;

interface ReviewRepositoryInterface
{

    public function index();

    public function reviewstore($data);

    public function reviewupdate($data,$id);
    public function show($id);



    public function delete($id);


}






