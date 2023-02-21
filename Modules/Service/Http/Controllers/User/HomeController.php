<?php

namespace Modules\Service\Http\Controllers\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Category\Transformers\CategoryResource;
use Modules\Offer\Entities\Offer;
use Modules\Offer\Transformers\OfferResource;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\SubService;
use Modules\Service\Http\Requests\CreateSubServiceRequest;
use Modules\Service\Http\Requests\UpdateSubServiceRequest;
use Modules\Service\Transformers\SubServiceResource;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function UserHome()
    {
        $SubServices= SubService::latest()->get();
        $Service = Service::where('active', 1)->get();
        $categories= Category::latest()->get();
        $offer =Offer::latest()->get();
        return response()->json(["offers"=> OfferResource::collection($offer), "Service"=>SubServiceResource::collection($Service) ,"SubServices" =>SubServiceResource::collection($SubServices) ,"categories" =>CategoryResource::collection($categories) ]);

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */

}
