<?php

namespace Modules\Worker\Repositories\Repository;


use Illuminate\Support\Facades\DB;
use Modules\Worker\Entities\Worker;
use Modules\Worker\Repositories\Interfaces\WorkerRepositoryInterface;
use Modules\Worker\Transformers\WorkerResource;

class WorkerRepository implements WorkerRepositoryInterface
{


    public function index(){

        $Worker=  Worker::latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'data' =>  WorkerResource::collection($Worker)
        ];

    }

    public function store($data)
    {

        //Request is valid, create new user
        $Worker = Worker::create($data);
        $Worker->addMediaFromRequest('photo')->toMediaCollection('workers');


        $Worker->save();
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Worker successfully created ',
            'data' => new WorkerResource($Worker)
        ];

    }

    public function tasks($id)
    {
        $tasks = DB::table('order_worker')->where("worker_id", $id)->count();
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Worker successfully created ',
            'tasks ' => $tasks
        ];
    }


    public function show($id)
    {

        $Worker = Worker::find($id);

        return ['statusCode' => 200, 'status' => true,
            'data' => new WorkerResource($Worker)
        ];
    }


    public function Update($data , $id)
    {

        $Worker = Worker::find($id);
        $Worker->update($data);

        $Worker->addMediaFromRequest('photo')->toMediaCollection('workers');
        $Worker->save();

        return ['statusCode' => 200, 'status' => true,
            'message' => 'Worker updated successfully ',
            'data' => new WorkerResource($Worker)
        ];
    }


    public function destory($id)
    {
        $Worker = Worker::find($id);


        $Worker->delete();

        $msg = 'Deleted';
        return response()->json(['statusCode' => 200, 'status' => true, 'message' => $msg]);
    }


}
