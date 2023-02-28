<?php

namespace Modules\Service\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Service\Entities\SubService;
use Modules\Service\Http\Requests\CreateSubServiceRequest;
use Modules\Service\Http\Requests\UpdateSubServiceRequest;
use Modules\Service\Transformers\SubServiceResource;

class SubServiceController extends Controller
{
    private $subserviceModel;

    public function __construct(SubService $subservice)
    {
        $this->subserviceModel = $subservice;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $SubServices= $this->subserviceModel->latest()->get();
        return SubServiceResource::collection($SubServices);

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateSubServiceRequest $request)
    {
        $SubServices= $this->subserviceModel->create($request->all());
        return ['statusCode' => 200,'status' => true , 'message'=>'Created successfully','data'=> new SubServiceResource($SubServices)];

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return array
     */
    public function show($id)
    {
        $SubService=$this->subserviceModel->findOrFail($id);
        return ['statusCode' => 200,
            'status' => true ,
            'data' => new SubServiceResource($SubService) ];
    }

    public function showWith($id)
    {
        $SubService=$this->subserviceModel->where('service_id',$id)->get();
        return ['statusCode' => 200,
            'status' => true ,
            'data' => $SubService ];
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function update(UpdateSubServiceRequest $request, $id)
    {
        $SubService = $this->subserviceModel->where('id', $id)->first();
        $SubService->update($request->all());
        return ['statusCode' => 200,
            'status' => true ,
            'message' => 'Subservice updated successfully ',
            'data' => new SubServiceResource($SubService) ];
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $SubService = $this->subserviceModel->find($id);
        $SubService->delete();
        $msg='Deleted';
        return response()->json(['statusCode' => 200,'status' => true , 'message' =>  $msg ]);

    }
}
