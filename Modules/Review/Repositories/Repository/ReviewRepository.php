<?php

namespace Modules\Review\Repositories\Repository;


use Illuminate\Support\Facades\Auth;
use Modules\Review\Entities\Review;
use Modules\Review\Repositories\Interfaces\ReviewRepositoryInterface;
use Modules\Review\Transformers\ReviewResource;

class ReviewRepository implements ReviewRepositoryInterface
{


    public function index()
    {

        $Review = Review::with(['users', 'services', 'workers'])->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'data' => ReviewResource::collection($Review)
        ];

    }

    public function reviewStore($data)
    {

        $data['user_id'] = Auth::user()->id;

        $Review = Review::create($data->all());
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Review successfully created ',
            'data' => new ReviewResource($Review)
        ];


    }

    public function reviewUpdate($data, $id)
    {
        $userId = Auth::user()->id;
        $Review = Review::where(['user_id'=> $userId,'service_id'=>$id])->with(['users', 'services', 'workers'])->first();
        $Review->update($data->all());
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Review successfully updated ',
            'data' => new ReviewResource($Review)
        ];


    }


    public function show($id)
    {

        $Review = Review::find($id)->with(['users', 'services']);

        return ['statusCode' => 200, 'status' => true,
            'data' => new ReviewResource($Review)
        ];
    }


    public function destroy($id)
    {
        $Userid = Auth::user()->id;
        try {

        Review::where(['user_id'=> $Userid,'service_id'=>$id])->delete();

        $msg = 'Deleted';
        return response()->json(['statusCode' => 200, 'status' => true, 'message' => $msg]);

        } catch (\Exception $e) {

            return response()->json(['statusCode' => 400, 'status' => false]);

        }   }




}
