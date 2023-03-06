<?php

namespace Modules\Service\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Service\Repositories\Interfaces\ServiceRepositoryInterface;
use Modules\Service\Transformers\ServiceResource;

class ServiceController extends Controller
{
    private $serviceRepository;

    public function __construct(ServiceRepositoryInterface $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function activate($id){
        //sleep(3);
        $item = Service::find($id);
        if($item){
            $item->active = !$item->active;
            $item->save();
            return response()->json(['status' => $item->active, 'msg' => 'updated successfully']);
        }
        return response()->json(['status' => 0, 'msg' => 'invalid id']);
    }

    public function index()
    {
        $Services = $this->serviceRepository->allServices();
        return ServiceResource::collection($Services);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title_en' => 'required|string|max:255',
            'description_en' => 'required|string',
            'title_sv' => 'required|string|max:255',
            'description_sv' => 'required|string',
            'price' => 'required',
            'gallery.*' => ['image','mimes:jpg,png,jpeg,webp','max:2048'],
            "category_id"=> 'required',
            "offer_id"=>'max:2048',
            "worker_id"=>'required',
        ]);
        $service= $this->serviceRepository->storeService($data);
        $service->addMediaFromRequest('gallery')->toMediaCollection('services');
        $service->save();
    return ['statusCode' => 200,'status' => true ];

    }

    public function AddServiceWoeker(Request $request, $id)
    {
        $service = $this->serviceRepository->addServiceWoeker($request, $id);
        return ['statusCode' => 200, 'status' => true, 'data' => $service];
    }

    public function DeleteWoekerFromService(Request $request,$id)
    {
        return $this->serviceRepository->deleteWoekerFromService($request, $id);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Service = $this->serviceRepository->findService($id);
        return ['statusCode' => 200,
            'status' => true ,
            'data' => new ServiceResource($Service) ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return array|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title_en' => 'required|string|max:255',
            'description_en' => 'required|string',
            'title_swd' => 'required|string|max:255',
            'description_swd' => 'required|string',
            'price' => 'required',
            'gallery.*' => ['image','mimes:jpg,png,jpeg,webp','max:2048'],
            "category_id"=> 'required',
            "offer_id"=>'max:2048',
            "worker_id"=>'required',
        ]);
        $service= $this->serviceRepository->updateService($data, $id);
        if ($request->hasFile('gallery')) {
            $service->addMediaFromRequest('gallery')->toMediaCollection('services');
        }
        return ['statusCode' => 200,
            'status' => true ,
            'message' => 'service updated successfully ',
            'data' =>$service,
             ];
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $msg="deleted";
        $service=   $this->serviceRepository->destroyService($id);
        return response()->json(['statusCode' => 200,'status' => true , 'message' =>  $msg ]);
    }
}
