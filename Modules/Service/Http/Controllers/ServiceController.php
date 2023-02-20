<?php

namespace Modules\Service\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Service\Entities\Service;
use Modules\Auth\Repositories\Repository\Interfaces\ServiceRepositoryInterface;
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
    public function index()
    {
        $Services =  $this->serviceRepository->allServices();

        return ServiceResource::collection($Services);
;

      }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required',
            'gallery.*' => ['image','mimes:jpg,png,jpeg,webp','max:2048'],
            "category_id"=> 'required'


        ]);
    //saving data

    // check if $request->gallery array has images. if true, we save them

       $service= $this->serviceRepository->storeService($data);
        $service->addMediaFromRequest('gallery')->toMediaCollection('services');
        $service->save();

    return ['statusCode' => 200,'status' => true , 'data' => new ServiceResource($service) ];

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required',
            'gallery.*' => ['image','mimes:jpg,png,jpeg,webp','max:2048'],
            "category_id"=> 'required'


        ]);


        $service= $this->serviceRepository->updateService($data, $id);
        if ($request->hasFile('gallery')) {
            $service->addMediaFromRequest('gallery')->toMediaCollection('services');
        }
        return ['statusCode' => 200,
            'status' => true ,
            'message' => 'service updated successfully ',
            'data' => new ServiceResource($service) ];

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
