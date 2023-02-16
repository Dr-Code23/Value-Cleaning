<?php

namespace Modules\Service\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Service\Entities\Service;
use Modules\Service\Repositories\Interfaces\ServiceRepositoryInterface;

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
        
        return response()->json(['statusCode' => 200,'status' => true , 'Services' =>  $Services ]);
    
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
            'gallery.*' => ['image','mimes:jpg,png,jpeg,webp','max:2048'],
            'description' => 'required|string',
            'price' => 'required',
    
            
        ]);
    //saving data
    
    // check if $request->gallery array has images. if true, we save them
    
       $service= $this->serviceRepository->storeService($data);
    
    
    return response()->json(['statusCode' => 200,'status' => true , 'service' =>  $service ]);
    
    }  
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Service = $this->serviceRepository->findService($id);
    
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
        $request->validate([
            'title' => 'required|string|max:255',
            'gallery.*' => ['image','mimes:jpg,png,jpeg,webp','max:2048'],
            'description' => 'required|string',
            'price' => 'required',
    
        ]);
    
       
        $service= $this->serviceRepository->updateService($request->all(), $id);
        
          
        return response()->json(['statusCode' => 200,'status' => true , 'service' =>  $service ]);
    
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    
        $service=   $this->serviceRepository->destroyService($id);
        return response()->json(['statusCode' => 200,'status' => true , 'service' =>  $service ]);
    
    
    }
}
