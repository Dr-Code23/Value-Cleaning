<?php

namespace Modules\Review\Repositories\Repository;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Modules\Review\Entities\Review;
use Modules\Review\Repositories\Interfaces\ReviewRepositoryInterface;
use Modules\Review\Transformers\ReviewResource;

class ReviewRepository implements ReviewRepositoryInterface
{



    public function index(){

        $Review=  Review::latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'data' =>  ReviewResource::collection($Review)
        ];

    }

    public function reviewstore($data)
    {

        $data['user_id']= Auth::user()->id;

            $Review=  Review::create($data->all());
            return ['statusCode' => 200, 'status' => true,
                'message' => 'Review successfully created ',
                'data' => new ReviewResource($Review)
            ];






    }

    public function reviewupdate($data,$id)
    {
       $userid= Auth::user()->id;
        $Review =Review::where('user_id',$userid)->where('service_id',$id)->first();
        $Review->update($data->all());
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Review successfully updated ',
            'data' => new ReviewResource($Review)
        ];






    }


    public function show($id)
    {

        $Review = Review::find($id);


        return ['statusCode' => 200,'status' => true ,
            'data' => new ReviewShowResource($Review)
        ];
    }





    public function delete($id){
        $Userid= Auth::user()->id;

       Review::query()->where('user_id',$Userid)->where('service_id',$id)->delete();



        $msg='Deleted';
        return response()->json(['statusCode' => 200,'status' => true , 'message' =>  $msg ]);    }



}
