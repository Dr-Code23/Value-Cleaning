<?php

namespace Modules\Review\Repositories\Repository;


use Illuminate\Support\Facades\Auth;
use Modules\Review\Entities\Review;
use Modules\Review\Repositories\Interfaces\ReviewRepositoryInterface;
use Modules\Review\Transformers\ReviewResource;

class ReviewRepository implements ReviewRepositoryInterface
{
    private $reviewModel;

    public function __construct(Review $review)
    {
        $this->reviewModel = $review;
    }

    public function index()
    {

        $review = $this->reviewModel->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'data' => ReviewResource::collection($review)
        ];
    }

    public function reviewStore($data)
    {

        $data['user_id'] = Auth::user()->id;
        $review = $this->reviewModel->create($data->all());
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Review successfully created ',
            'data' => new ReviewResource($review)
        ];
    }

    public function reviewUpdate($data, $id)
    {
        $userId = Auth::user()->id;
        $review = $this->reviewModel->where(['user_id'=> $userId,'service_id'=>$id])->first();
        $review->update($data->all());
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Review successfully updated ',
            'data' => new ReviewResource($review)
        ];
    }

    public function show($id)
    {
        $userId = Auth::user()->id;
        $review = $this->reviewModel->where(['user_id'=> $userId,'service_id'=>$id])->first();
        return ['statusCode' => 200, 'status' => true,
            'data' => new ReviewResource($review)
        ];
    }

    public function destroy($id)
    {
        $Userid = Auth::user()->id;
        try {

            $this->reviewModel->where(['user_id'=> $Userid,'service_id'=>$id])->delete();

            $msg = 'Deleted';
            return response()->json(['statusCode' => 200, 'status' => true, 'message' => $msg]);

        } catch (\Exception $e) {

            return response()->json(['statusCode' => 400, 'status' => false]);

        }
    }




}
