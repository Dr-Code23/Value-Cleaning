<?php

namespace Modules\Service\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Service\Http\Requests\CreateServicesRequest;
use Modules\Service\Http\Requests\UpdateServicesRequest;
use Modules\Service\Repositories\Interfaces\ServiceRepositoryInterface;
use Modules\Service\Transformers\ServiceResource;

class ServiceController extends Controller
{
    private $serviceRepository;

    public function __construct(ServiceRepositoryInterface $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
        $this->middleware('permission:service-list|service-create|service-edit|service-delete');
        $this->middleware('permission:service-create', ['only' => ['store']]);
        $this->middleware('permission:service-edit', ['only' => ['update']]);
        $this->middleware('permission:service-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */

    public function activate($id)
    {
        //sleep(3);
        $item = Service::find($id);
        if ($item) {
            $item->active = !$item->active;
            $item->save();
            return response()->json(['status' => $item->active, 'msg' => 'updated successfully']);
        }
        return response()->json(['status' => 0, 'msg' => 'invalid id']);
    }

    public function index(Request $request)
    {
        return $this->serviceRepository->allServices($request);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(CreateServicesRequest $request)
    {
        return $this->serviceRepository->storeService($request);
    }

    public function addServiceWorker(Request $request, $id)
    {
        $service = $this->serviceRepository->addServiceWorker()($request, $id);
        return ['statusCode' => 200, 'status' => true, 'data' => $service];
    }

    public function deleteWorkerFromService(Request $request, $id)
    {
        return $this->serviceRepository->deleteWorkerFromService($request, $id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return array
     */
    public function show($id)
    {
        return $this->serviceRepository->findService($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return array|Response
     */
    public function update(UpdateServicesRequest $request, $id)
    {
        return $this->serviceRepository->updateService($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        return $this->serviceRepository->destroyService($id);
    }
}
