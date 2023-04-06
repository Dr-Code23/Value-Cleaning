<?php

namespace Modules\Service\Repositories;

use Modules\Offer\Entities\Offer;
use Modules\Review\Entities\Review;
use Modules\Service\Entities\Service;
use Modules\Service\Repositories\Interfaces\ServiceRepositoryInterface;
use Modules\Service\Transformers\ServiceResource;

class ServiceRepository implements ServiceRepositoryInterface
{
    private $serviceModel;

    public function __construct(Service $service)
    {
        $this->serviceModel = $service;
    }

    public function allServices($data)
    {
        if ($data->q) {
            $services = $this->serviceModel->whereLocale("title", $data->lang)->where("title", "like", "%$data->q%")
                ->orderBy("id", "DESC")
                ->get();
            return ['statusCode' => 200, 'status' => true,
                'data' => ServiceResource::collection($services)
            ];
        } else {
            $services = $this->serviceModel->latest()->get();
            return ['statusCode' => 200, 'status' => true,
                'data' => ServiceResource::collection($services)
            ];
        }
    }

    public function storeService($data)
    {
        $service = $this->serviceModel->create([
            'title' => [
                'en' => $data['title_en'],
                'sv' => $data['title_sv']
            ],
            'description' => [
                'en' => $data['description_en'],
                'sv' => $data['description_sv']
            ],
            "category_id" => $data['category_id'],
            'price' => $data['price'],
        ]);
        $service->addMediaFromRequest('gallery')->toMediaCollection('services');
        $service->save();

        $service->workers()->sync($data['worker_id']);

        return ['statusCode' => 200, 'status' => true, 'data' => $service];
    }

    public function addServiceWorker($data, $id)
    {
        $sevice = $this->serviceModel->findOrFail($id);
        $sevice->workers()->sync($data->all());
    }

    public function WorkerFromService($id)
    {
        $sevice = $this->serviceModel->findOrFail($id);

        $sevice->workers();

        return ['statusCode' => 200, 'status' => true, 'data' => $sevice->workers, 'message' => 'all worker from service successfully ',];
    }

    public function findService($id)
    {
        $service = $this->serviceModel->where('id', $id)->with('workers')->first();
        return ['statusCode' => 200,
            'status' => true,
            'data' => $service,
            'rate' => $service->revices->avg('star_rating')];
    }

    public function updateService($data, $id)
    {
        $service = $this->serviceModel->where('id', $id)->first();

        //sending the model data to the frontend
        $service->update([
            'title' => [
                'en' => $data['title_en'],
                'sv' => $data['title_sv']
            ],
            'description' => [
                'en' => $data['description_en'],
                'sv' => $data['description_sv']
            ],
            "category_id" => $data['category_id'],
            'price' => $data['price'],
        ]);
        if ($data->hasFile('gallery')) {
            $service->media()->delete();
            $service->addMediaFromRequest('gallery')->toMediaCollection('services');
        }
        if ($data['worker_id']) {
            $service->workers()->sync($data['worker_id']);
        }
        return ['statusCode' => 200,
            'status' => true,
            'message' => 'service updated successfully ',
            'data' => $service,
        ];

    }

    public function destroyService($id)
    {
        $Service = $this->serviceModel->find($id);
        $Service->delete();
        return response()->json(['statusCode' => 200, 'status' => true, 'message' => 'Deleted']);
    }
}
