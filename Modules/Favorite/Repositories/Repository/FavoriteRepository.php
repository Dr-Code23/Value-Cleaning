<?php

namespace Modules\Favorite\Repositories\Repository;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Modules\Favorite\Entities\Favorite;
use Modules\Favorite\Repositories\Interfaces\FavoriteRepositoryInterface;
use Modules\Favorite\Transformers\FavoriteResource;
use Modules\Favorite\Transformers\FavoriteShowResource;
use Modules\Service\Entities\Service;

class FavoriteRepository implements FavoriteRepositoryInterface
{



    public function index(){

        $Favorite=  Favorite::latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'data' =>  FavoriteResource::collection($Favorite)
        ];

    }

    public function create($data)
    {
        if (Auth::guard('api')->check()) {
            $userID = auth('api')->user()->getKey();
        }
        $serviceID = $data->service_id;


        //Check if the proudct exist or return 404 not found.
        try { $service = Service::findOrFail($serviceID);} catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'The service you\'re trying to add does not exist.',
            ], 404);
        }

        //check if the the same serrvice is already in the Cart, if true update the quantity, if not create a new one.
        $Favorite = Favorite::where( ['user_id'=>$userID,'service_id' => $serviceID])->first();
        if ($Favorite) {
            Favorite::where(['user_id'=>$userID, 'service_id' => $serviceID]);
            return response()->json(['message' => 'The Favourite exite'], 200);

        } else {
            $Favorite=  Favorite::create(['user_id'=>$userID, 'service_id' => $serviceID]);
            return ['statusCode' => 200, 'status' => true,
                'message' => 'Favorite successfully created ',
                'data' => new FavoriteResource($Favorite)
            ];

        }




    }




    public function show($id)
    {

        $Favorite = Favorite::find($id);


        return ['statusCode' => 200,'status' => true ,
            'data' => new FavoriteShowResource($Favorite)
        ];
    }





    public function delete($id){
        $Favorite = Favorite::find($id);


    $Favorite->delete();

        $msg='Deleted';
        return response()->json(['statusCode' => 200,'status' => true , 'message' =>  $msg ]);    }



}
