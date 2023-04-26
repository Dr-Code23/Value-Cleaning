<?php

namespace Modules\Worker\Repositories\Repository;


use Illuminate\Support\Facades\DB;
use Modules\Worker\Entities\Worker;
use Modules\Worker\Repositories\Interfaces\WorkerRepositoryInterface;
use Modules\Worker\Transformers\WorkerResource;

class WorkerRepository implements WorkerRepositoryInterface
{
    private $workerModel;

    public function __construct(Worker $worker)
    {
        $this->workerModel = $worker;
    }

    public function index($data){

        if($data->q) {
            $worker= $this->workerModel->where("email", "like", "%$data->q%")
                ->orwhere("name", "like", "%$data->q%")
                ->orderBy("id", "DESC")
                ->get();
            return ['statusCode' => 200, 'status' => true,
                'data' =>  WorkerResource::collection($worker)
            ];
        }else{
        $worker=   $this->workerModel->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'data' =>  WorkerResource::collection($worker)
        ];
    }
    }

    public function store($data)
    {

        //Request is valid, create new user
       

        $worker=$this->workerModel->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'address' => $data['address'],
            'latitude' => $data['latitude'] ?? 0,
            'longitude' => $data['longitude'] ?? 0,
            'phone' => $data['phone'],
            'NIN' =>$data['NIN'],
        ]);

if (isset($data['photo'])) {
        $worker->addMediaFromRequest('photo')->toMediaCollection('workers');
        $worker->save();
    }
        $worker->save();
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Worker successfully created ',
            'data' => new WorkerResource($worker)
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

        $worker =  $this->workerModel->find($id);
        return ['statusCode' => 200, 'status' => true,
            'data' => new WorkerResource($worker)
        ];
    }

    public function Update($data , $id)
    {

        $worker =  $this->workerModel->find($id);
       
        $worker->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'address' => $data['address'],
            'latitude' => $data['latitude'] ?? 0,
            'longitude' => $data['longitude'] ?? 0,
            'phone' => $data['phone'],
        ]);

       if ($data['photo']) {
            $worker->media()->delete();
            $worker->addMediaFromRequest('photo')->toMediaCollection('workers');
            $worker->save();
        }
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Worker updated successfully ',
            'data' => new WorkerResource($worker)
        ];
    }


    public function destory($id)
    {
        try {
            $worker =  $this->workerModel->find($id);


            $worker->delete();
        }catch (\Exception $e){
            return ['statusCode' => 404, 'status' => false,
                'message' => 'something wrong ',

            ];


        }


        $msg = 'Deleted';
        return response()->json(['statusCode' => 200, 'status' => true, 'message' => $msg]);
    }


}
