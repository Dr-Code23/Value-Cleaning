<?php

namespace Modules\Service\Http\Controllers\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Category\Transformers\CategoryResource;
use Modules\Offer\Entities\Offer;
use Modules\Offer\Transformers\OfferResource;
use Modules\Order\Entities\Order;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\SubService;
use Modules\Service\Http\Requests\CreateSubServiceRequest;
use Modules\Service\Http\Requests\UpdateSubServiceRequest;
use Modules\Service\Transformers\ServiceResource;
use Modules\Service\Transformers\SubServiceResource;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function UserHome()
    {
        $Service = Service::where('active', 1)->get();
        $categories= Category::latest()->get();
        $offer =Offer::latest()->get();

        return response()->json(["offers"=> OfferResource::collection($offer), "Service"=>SubServiceResource::collection($Service) ,"categories" =>CategoryResource::collection($categories) ]);

    }

    public function ServiceDetalis($id)
    {
        $SubService=Service::find($id);
        return ['statusCode' => 200,
            'status' => true ,
            'data' =>  new ServiceResource($SubService ) ];
    }

    public function SubService($id)
    {
        $SubService=SubService::where('service_id',$id)->get();
        return ['statusCode' => 200,
            'status' => true ,
            'data' => $SubService ];
    }

    public function jobDone($id)

    {

        $job_done =Order::where(["service_id"=>$id,'status'=>'Finished'])->count();
        return ['statusCode' => 200, 'status' => true, 'job_done' => $job_done ];
    }

}
