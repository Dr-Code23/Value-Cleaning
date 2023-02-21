<?php

namespace Modules\Category\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Category\Transformers\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $Categories= Category::latest()->get();
        return CategoryResource::collection($Categories);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            "gallery" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048"
        ]);

        $requestData = $request->all();

         $category=Category::create($requestData);



        $category->addMediaFromRequest('gallery')->toMediaCollection('categories');
        $category->save();



     //sending the model data to the frontend
     return [
         'statusCode' => 200,
         'status' => true ,
         'message' => 'Category stored successfully ',
         'data' => new CategoryResource($category)

     ];

       }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return CategoryResource
     */
    public function show($id)
    {
        $Category = Category::find($id);

        return new CategoryResource($Category);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return array
     */
    public function update(Request $request, $id)
    {


        $validated = $request->validate([
            'title' => 'required|string|max:255',
            "gallery" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048"
        ]);
        $Category = Category::find($id)->first();
        $Category->title = $request->title;
        $Category->save();

        if ($request->hasFile('gallery')) {
            $Category->addMediaFromRequest('gallery')->toMediaCollection('categories');
        }

        return ['statusCode' => 200,'status' => true ,
            'message' => 'Category updated successfully ',
            'data' => new CategoryResource($Category)
        ];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $Category = Category::find($id);
        $Category->delete();

       $message="deleted " ;
        return response()->json(['statusCode' => 200,'status' => true , 'message' =>  $message ]);

     }
}
