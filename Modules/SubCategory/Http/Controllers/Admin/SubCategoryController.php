<?php

namespace Modules\SubCategory\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\SubCategory\Entities\SubCategory;
use Modules\SubCategory\Transformers\SubCategoryResource;

class SubCategoryController extends Controller
{
    private $categoryModel;

    public function __construct(SubCategory $category)
    {
        $this->categoryModel = $category;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if ($request->q) {
            $data = $this->categoryModel->where("title", "like", "%$request->q%")
                ->orderBy('id', 'DESC')->get();

            return SubCategoryResource::collection($data);
        }
        $Categories = $this->categoryModel->latest()->get();

        return SubCategoryResource::collection($Categories);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return array|JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_sv' => 'required|string|max:255',
            'subsubcategory_id' => 'required',
            "gallery" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048"
        ]);

        $category = $this->categoryModel->create([
            'title' =>
                [
                    'en' => $validated['title_en'],
                    'sv' => $validated['title_sv']
                ],
            'subsubcategory_id' => $validated['subsubcategory_id'],

        ]);
        if ($request->gallery) {
            $category->addMediaFromRequest('gallery')->toMediaCollection('categories');
            $category->save();
        }
        //sending the model data to the frontend
        return [
            'statusCode' => 200,
            'status' => true,
            'message' => 'subCategory stored successfully ',
            'data' => new SubCategoryResource($category)
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return array
     */
    public function show($id)
    {
        $category = $this->categoryModel->find($id);
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Category updated successfully ',
            'data' => $category
        ];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function update(Request $request, $id)
    {

        $category = $this->categoryModel->find($id);
        $categories = json_decode($category);
        $category->update([
            'title' =>
                [
                    'en' => $request['title_en'] ?? $categories->title->en,
                    'sv' => $request['title_sv'] ?? $categories->title->sv
                ],
            'subsubcategory_id' => $request['subsubcategory_id'] ??  $category->subsubcategory_id,

        ]);
        if ($request->hasFile('gallery')) {
            $category->media()->delete();
            $category->addMediaFromRequest('gallery')->toMediaCollection('categories');
            $category->save();
        }

        return ['statusCode' => 200, 'status' => true,
            'message' => 'Category updated successfully ',
            'data' => $category
        ];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $category = $this->categoryModel->find($id);
        $category->delete();

        $message = "deleted ";
        return response()->json(['statusCode' => 200, 'status' => true, 'message' => $message]);

    }
}
