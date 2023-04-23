<?php

namespace Modules\Favorite\Repositories\Repository;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Favorite\Entities\Favorite;
use Modules\Favorite\Repositories\Interfaces\FavoriteRepositoryInterface;
use Modules\Favorite\Transformers\FavoriteResource;
use Modules\Favorite\Transformers\FavoriteShowResource;
use Modules\Service\Entities\Service;

class FavoriteRepository implements FavoriteRepositoryInterface
{
    private $favoriteModel;

    public function __construct(Favorite $favorite)
    {
        $this->favoriteModel = $favorite;
    }

    public function index()
    {

        $Favorite = $this->favoriteModel->where('user_id',Auth::id())->with('services')->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'data' => FavoriteResource::collection($Favorite)
        ];

    }

    public function store($data)
    {
        if (Auth::guard('api')->check()) {
            $userID = auth('api')->user()->getKey();
        }
        $serviceID = $data->service_id;

        //Check if the proudct exist or return 404 not found.
        try {
            $service = Service::findOrFail($serviceID)->with('services');
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'The service you\'re trying to add does not exist.',
            ], 404);
        }

        //check if the the same serrvice is already in the Cart, if true update the quantity, if not create a new one.
        $Favorite = $this->favoriteModel->where(['user_id' => $userID, 'service_id' => $serviceID])->first();
        if ($Favorite) {
            $this->favoriteModel->where(['user_id' => $userID, 'service_id' => $serviceID]);
            return response()->json(['message' => 'The Favourite exite'], 200);

        } else {
            $Favorite = $this->favoriteModel->create(['user_id' => $userID, 'service_id' => $serviceID])->with('services');
            return ['statusCode' => 200, 'status' => true,
                'message' => 'Favorite successfully created ',
                'data' => $Favorite
            ];

        }

    }

    public function show($id)
    {

        $favorite = $this->favoriteModel->find($id);
        return ['statusCode' => 200, 'status' => true,
            'data' => new FavoriteResource($favorite)
        ];
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $favorite = $this->favoriteModel->where(['service_id'=>$id,'user_id'=>Auth::id()])->delete();
        $msg = 'Deleted';
        return response()->json(['statusCode' => 200, 'status' => true, 'message' => $msg]);
    }


}
