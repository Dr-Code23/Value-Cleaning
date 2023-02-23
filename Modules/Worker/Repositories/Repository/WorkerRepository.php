<?php

namespace Modules\Worker\Repositories\Repository;


use Modules\Worker\Repositories\Interfaces\WorkerRepositoryInterface;
use Modules\Worker\Entities\Worker;
use Modules\Worker\Transformers\WorkerResource;

class WorkerRepository implements WorkerRepositoryInterface
{



    public function index(){

        $Worker=  Worker::latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'data' =>  WorkerResource::collection($Worker)
        ];

    }

    public function create($data)
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




    public function show($id)
    {

        $Worker = Worker::find($id);


        return ['statusCode' => 200,'status' => true ,
            'data' => new WorkerResource($Worker)
        ];
    }


    public function Update($data ,$id)
    {




        $Worker = Worker::find($id);
        $Worker->update($data);


        $Worker->addMediaFromRequest('photo')->toMediaCollection('workers');
        $Worker->save();

        return ['statusCode' => 200,'status' => true ,
            'message' => 'Worker updated successfully ',
            'data' => new WorkerResource($Worker)
        ];
    }


    public function delete($id){
        $Worker = Worker::find($id);


    $Worker->delete();

        $msg='Deleted';
        return response()->json(['statusCode' => 200,'status' => true , 'message' =>  $msg ]);    }



}
