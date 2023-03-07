<?php

namespace Modules\Review\Repositories\Repository;


use Illuminate\Support\Facades\Auth;
use Modules\Review\Entities\WorkerReview;
use Modules\Review\Repositories\Interfaces\WorkerReviewRepositoryInterface;
use Modules\Review\Transformers\ReviewWorkerResource;

class WorkerReviewRepository implements WorkerReviewRepositoryInterface
{
    private $reviewModel;

    public function __construct(WorkerReview $review)
    {
        $this->reviewModel = $review;
    }

    public function index()
    {

        $review = $this->reviewModel->with(['users','workers'])->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'data' => ReviewWorkerResource::collection($review)
        ];
    }

    public function reviewStore($data)
    {

        $data['user_id'] = Auth::user()->id;
        $review = $this->reviewModel->create($data->all());
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Review successfully created ',
            'data' => new ReviewWorkerResource($review)
        ];
    }

    public function reviewUpdate($data, $id)
    {
        $userId = Auth::user()->id;
        $review = $this->reviewModel->where(['user_id'=> $userId,'worker_id'=>$id])->with(['users','workers'])->first();
        $review->update($data->all());
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Review successfully updated ',
            'data' => new ReviewWorkerResource($review)
        ];
    }

    public function show($id)
    {

        $review = $this->reviewModel->find($id)->with(['users', 'services']);
        return ['statusCode' => 200, 'status' => true,
            'data' => new ReviewWorkerResource($review)
        ];
    }

    public function destroy($id)
    {
        $Userid = Auth::user()->id;
        try {

        $this->reviewModel->where(['user_id'=> $Userid,'worker_id'=>$id])->delete();

        $msg = 'Deleted';
        return response()->json(['statusCode' => 200, 'status' => true, 'message' => $msg]);

        } catch (\Exception $e) {

            return response()->json(['statusCode' => 400, 'status' => false]);

        }
    }




}
