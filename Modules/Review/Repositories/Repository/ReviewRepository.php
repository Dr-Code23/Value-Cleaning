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

        $Review = $this->reviewModel->with(['users', 'services', 'workers'])->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'data' => ReviewResource::collection($Review)
        ];
    }

    public function reviewStore($data)
    {

        $data['user_id'] = Auth::user()->id;
        $Review = $this->reviewModel->create($data->all());
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Review successfully created ',
            'data' => new ReviewResource($Review)
        ];
    }

    public function reviewUpdate($data, $id)
    {
        $userId = Auth::user()->id;
        $Review = $this->reviewModel->where(['user_id'=> $userId,'service_id'=>$id])->with(['users', 'services', 'workers'])->first();
        $Review->update($data->all());
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Review successfully updated ',
            'data' => new ReviewResource($Review)
        ];
    }

    public function show($id)
    {

        $Review = $this->reviewModel->find($id)->with(['users', 'services']);
        return ['statusCode' => 200, 'status' => true,
            'data' => new ReviewResource($Review)
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
