<?php

namespace Modules\Review\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Review\Http\Requests\CreateReviewRequest;
use Modules\Review\Http\Requests\UpdateReviewRequest;
use Modules\Review\Repositories\Interfaces\WorkerReviewRepositoryInterface;

class WorkerReviewController extends Controller
{
    private $ReviewRepository;

    public function __construct(WorkerReviewRepositoryInterface $ReviewRepository)
    {
        $this->ReviewRepository = $ReviewRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return $this->ReviewRepository->index();
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateReviewRequest $request)
    {
        return $this->ReviewRepository->reviewStore($request);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return $this->ReviewRepository->show($id);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateReviewRequest $request,$id)
    {
        return $this->ReviewRepository->reviewupdate($request,$id);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        return $this->ReviewRepository->destroy($id);
    }
}
