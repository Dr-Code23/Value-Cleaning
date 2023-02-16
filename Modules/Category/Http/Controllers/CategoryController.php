<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $Categories= Category::latest()->paginate(10);  

        return response()->json(['statusCode' => 200,'status' => true , '$category' =>  $Categories ]);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            "gallery" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048"
        ]);

        $requestData = $request->all();

         $category=Category::create($requestData); 


      
        $category->addMediaFromRequest('gallery')->toMediaCollection('category_images');
        $category->save();

       
     
     //sending the model data to the frontend
     return response()->json(['statusCode' => 200,'status' => true , '$category' =>  $category ]);

       }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $validated = $request->validate([
            'title' => 'required|string|max:255',
            "gallery" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048"
        ]);

        $Category = Category::where('id', $id)->first();

        if($request->gallery){
        
            foreach($request->gallery as $image ){
                $Category->addMedia($image)->toMediaCollection('Category_images');
            }

            $Category->name = $request['title'];
            $Category->description = $request['description'];
            $Category->price = $request['price'];         
            $Category->refresh(); 

          }
          
          //sending the model request to the frontend
        $Category->name = $request['title'];
        $Category->gallery = $request['gallery'];
        $Category->description = $request['description'];
        $Category->price = $request['price'];

        $Category->save();
        return response()->json(['statusCode' => 200,'status' => true , '$category' =>  $Category ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Category = Category::find($id);
        $Category->delete();   

       $message="deleted " + $Category->title;
        return response()->json(['statusCode' => 200,'status' => true , 'message' =>  $message ]);

     }
}
