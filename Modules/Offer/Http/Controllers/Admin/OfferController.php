<?php

namespace Modules\Offer\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Offer\Entities\Offer;
use Modules\Offer\Http\Requests\CreateRequest;
use Modules\Offer\Http\Requests\UpdateRequest;
use Modules\Offer\Transformers\OfferResource;

class OfferController extends Controller
{
    private $offerModel;

    public function __construct(Offer $offer)
    {
        $this->offerModel = $offer;
    }
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $offers = $this->offerModel->latest()->get();
        return OfferResource::collection($offers);
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateRequest $request)
    {

        $offer=$this->offerModel->create($request->all());

        return ['statusCode' => 200,'status' => true ,'message'=>'Created successfully' ,'data' => new OfferResource($offer) ];

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $offer=$this->offerModel->find($id);
        return ['statusCode' => 200,'status' => true  ,'data' => new OfferResource($offer) ];

    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateRequest $request, $id)
    {
        $offer = $this->offerModel->where('id', $id)->first();
        $offer->offer_price = $request['offer_price'];
        $offer->update();


        return ['statusCode' => 200,'status' => true ,'message'=>'Updated successfully' ,'data' => new OfferResource($offer) ];

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $offer = $this->offerModel->find($id);
        $offer->delete();
        return ['statusCode' => 200,'status' => true ,'message'=>'Deleted successfully' ];

    }
}
