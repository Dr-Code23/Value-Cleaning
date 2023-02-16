<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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


        if($request->gallery){

            foreach($request->gallery as $image ){
                $category->addMedia($image)->toMediaCollection('category_images');
            }
         }
     
     //sending the model data to the frontend
     $category->refresh(); 
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
