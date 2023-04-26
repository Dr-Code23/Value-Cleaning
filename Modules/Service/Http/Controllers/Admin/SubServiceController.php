<?php

namespace Modules\Service\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
        $this->middleware('permission:subService-list|subService-create|subService-edit|subService-delete');
        $this->middleware('permission:subService-create', ['only' => ['store']]);
        $this->middleware('permission:subService-edit', ['only' => ['update']]);
        $this->middleware('permission:subService-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $SubServices = $this->subserviceModel->latest()->get();
        return SubServiceResource::collection($SubServices);

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return array
     */
    public function store(CreateSubServiceRequest $request)
    {
        $SubServices = $this->subserviceModel->create([
            'title' => [
                'en' => $request['title_en'],
                'sv' => $request['title_sv'],
            ],
            'price' => $request['price'],
            'service_id' => $request['service_id']
        ]);
        return ['statusCode' => 200, 'status' => true, 'message' => 'Created successfully', 'data' => new SubServiceResource($SubServices)];

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return array
     */
    public function show($id)
    {
        $SubService = $this->subserviceModel->findOrFail($id);
        return ['statusCode' => 200,
            'status' => true,
            'data' => $SubService];
    }

    public function showWith($id)
    {
        $SubService = $this->subserviceModel->where('service_id', $id)->get();
        return ['statusCode' => 200,
            'status' => true,
            'data' => $SubService];
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
        $SubServices = json_decode($SubService) ;
        $SubService->update([
            'title' => [
                'en' => $request['title_en'] ?? $SubServices->title->en,
                'sv' => $request['title_sv'] ?? $SubServices->title->sv,
            ],
            'price' => $request['price'] ?? $SubService->price,
            'service_id' => $request['service_id'] ?? $SubService->service_id
        ]);
        return ['statusCode' => 200,
            'status' => true,
            'message' => 'Subservice updated successfully ',
            'data' => new SubServiceResource($SubService)];
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $SubService = $this->subserviceModel->find($id);
        $SubService->delete();
        $msg = 'Deleted';
        return response()->json(['statusCode' => 200, 'status' => true, 'message' => $msg]);

    }
}
