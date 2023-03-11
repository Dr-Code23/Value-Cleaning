<?php

namespace Modules\Category\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Category\Transformers\CategoryResource;

class CategoryController extends Controller
{
    private $categoryModel;

    public function __construct(Category $category)
    {
        $this->categoryModel = $category;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $Categories = $this->categoryModel->latest()->get();
        return CategoryResource::collection($Categories);

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
            'title_en' => 'required|string|max:255',
            'title_sv' => 'required|string|max:255',
            "gallery" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048"
        ]);

        $category= $this->categoryModel->create([
            'title' =>
                [
                    'en' => $validated['title_en'],
                    'sv' => $validated['title_sv']
                ]
        ]);
        $category->addMediaFromRequest('gallery')->toMediaCollection('categories');
        $category->save();

        //sending the model data to the frontend
        return [
            'statusCode' => 200,
            'status' => true,
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
        $category = $this->categoryModel->find($id);
        return new CategoryResource($category);
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


        $category = $this->categoryModel->find($id)->first();
        if($request['title_en'] &&  $request['title_sv']){
            $category->update([
                'title' =>
                    [
                        'en' => $request['title_en'],
                        'sv' => $request['title_sv']
                    ]
            ]);}
        if ($request->hasFile('gallery')) {
            $category->media()->delete();
            $category->addMediaFromRequest('gallery')->toMediaCollection('categories');
            $category->save();
        }

        return ['statusCode' => 200, 'status' => true,
            'message' => 'Category updated successfully ',
            'data' => new CategoryResource($category)
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
        $category = $this->categoryModel->find($id);
        $category->delete();

        $message = "deleted ";
        return response()->json(['statusCode' => 200, 'status' => true, 'message' => $message]);

    }
}
