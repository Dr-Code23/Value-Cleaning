<?php

namespace Modules\Announcement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Http\Requests\CreateRequest;
use Modules\Announcement\Transformers\AnnouncementResource;

class AnnouncementController extends Controller
{
    private $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }



    public function activate($id){
        //sleep(3);
        $item =  $this->announcement->find($id);
        if($item){
            $item->active = !$item->active;
            $item->save();
            return response()->json(['status' => $item->active, 'msg' => 'updated successfully']);
        }
        return response()->json(['status' => 0, 'msg' => 'invalid id']);
    }
    /**
     * Display a listing of the resource.
     * @return array
     */
    public function index()
    {
        $announcement=  $this->announcement->latest()->get();
        return ['statusCode' => 200,'status' => true ,
            'data' => AnnouncementResource::collection($announcement)
        ];
    }

    public function store(CreateRequest $request)
    {
        $announcement=$this->announcement->create(['title'=>$request->title]);
        $announcement->addMediaFromRequest('photo')->toMediaCollection('announcements');
        $announcement->save();
        return ['statusCode' => 200,'status' => true ,
            'message' => ' saved successfully ',
            'data' => new AnnouncementResource($announcement)
        ];

    }

    public function show($id)
    {
        $announcement=$this->announcement->find($id);

        return ['statusCode' => 200,'status' => true ,
            'data' => new AnnouncementResource($announcement)
        ];
    }


    public function update(Request $request, $id)
    {
        $announcement=$this->announcement->find($id);
        $announcement->update($request->all());
        if ($request->hasFile('photo')) {
            $announcement->media()->delete();
            $announcement->addMediaFromRequest('photo')->toMediaCollection('announcements');
            $announcement->save();

        }
        return ['statusCode' => 200,'status' => true ,
            'message' => ' update successfully ',
            'data' => new AnnouncementResource($announcement)
        ];
    }


    public function destroy($id)
    {
        $announcement=$this->announcement->find($id);
        $announcement->delete();
        return ['statusCode' => 200,'status' => true ,
            'message' => ' deleted successfully ',

        ];

    }
}
