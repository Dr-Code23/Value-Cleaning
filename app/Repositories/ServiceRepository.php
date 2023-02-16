<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Models\Service;

class ServiceRepository implements ServiceRepositoryInterface
{

    public function allServices()
    {
        return Service::latest()->paginate(10);
    }

    public function storeService($data)
    {       
      $Service=  Service::create($data);
        if($data->gallery){

            foreach($data->gallery as $image ){
                $Service->addMedia($image)->toMediaCollection('service_images');
            }
          }

          //sending the model data to the frontend
          $Service->refresh();         
          return $Service;

    }

    public function findService($id)
    {
        return Service::find($id);
    }

    public function updateService($data, $id)
    {           
         $Service = Service::where('id', $id)->first();

        if($data->gallery){
        
            foreach($data->gallery as $image ){
                $Service->addMedia($image)->toMediaCollection('service_images');
            }

            $Service->name = $data['title'];
            $Service->description = $data['description'];
            $Service->price = $data['price'];         
            $Service->refresh(); 

          }
          
          //sending the model data to the frontend
        $Service->name = $data['title'];
        $Service->gallery = $data['gallery'];
        $Service->description = $data['description'];
        $Service->price = $data['price'];

        $Service->save();
    }

    public function destroyService($id)
    {
        $Service = Service::find($id);
        $Service->delete();
    }
}