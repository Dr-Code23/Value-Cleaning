<?php

namespace Modules\Worker\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Worker\Entities\Worker;
use Modules\Worker\Http\Requests\CreateRequest;
use Modules\Worker\Http\Requests\UpdateRequest;
use Modules\Worker\Repositories\Interfaces\WorkerRepositoryInterface;

class WorkerController extends Controller
{
    private $WorkerRepository;

    public function __construct(WorkerRepositoryInterface $WorkerRepository)
    {
        $this->WorkerRepository = $WorkerRepository;
        $this->middleware('permission:worker-list|worker-create|worker-edit|worker-delete', ['only' => ['index', 'store', 'update', 'destroy', 'show']]);
        $this->middleware('permission:worker-create', ['only' => ['store']]);
        $this->middleware('permission:worker-edit', ['only' => ['update']]);
        $this->middleware('permission:worker-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */


    public function activate($id)
    {
        //sleep(3);
        $item = Worker::find($id);
        if ($item) {
            $item->active = !$item->active;
            $item->save();
            return response()->json(['status' => $item->active, 'msg' => 'updated successfully']);
        }
        return response()->json(['status' => 0, 'msg' => 'invalid id']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return $this->WorkerRepository->index($request);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateRequest $request)
    {
        $worker = $this->WorkerRepository->store($request->all());

        return $worker;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return $this->WorkerRepository->show($id);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */

    public function update(UpdateRequest $request, $id)
    {

        return $this->WorkerRepository->update($request, $id);

    }

    /**
     * @param $id
     * @return mixed
     */
    public function tasks($id)
    {
        return $this->WorkerRepository->tasks($id);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        return $this->WorkerRepository->destory($id);
    }
}
