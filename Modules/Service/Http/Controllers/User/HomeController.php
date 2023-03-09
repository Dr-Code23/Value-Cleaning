<?php

namespace Modules\Service\Http\Controllers\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Transformers\AnnouncementResource;
use Modules\Category\Entities\Category;
use Modules\Category\Transformers\CategoryResource;
use Modules\Offer\Entities\Offer;
use Modules\Offer\Transformers\OfferResource;
use Modules\Order\Entities\Order;
use Modules\Review\Entities\Review;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\SubService;
use Modules\Service\Http\Requests\CreateSubServiceRequest;
use Modules\Service\Http\Requests\UpdateSubServiceRequest;
use Modules\Service\Transformers\ServiceResource;
use Modules\Service\Transformers\SubServiceResource;

class HomeController extends Controller
{

    public function userHome()
    {
        $Service = Service::where('active', 1)->get();
        $categories= Category::latest()->get();
        $announcement =Announcement::latest()->get();
        return response()->json(["announcement"=> AnnouncementResource::collection($announcement), "Service"=>SubServiceResource::collection($Service) ,"categories" =>CategoryResource::collection($categories) ]);

    }

    public function serviceDetails($id)
    {
        $SubService=Service::find($id);
        $rate= Review::where('service_id',$id)->avg('star_rating');

        return ['statusCode' => 200,
            'status' => true ,
            'data' =>  new ServiceResource($SubService ),
            'rate'=>$rate ];
    }

    public function subService($id)
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
