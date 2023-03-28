<?php

namespace Modules\Auth\Http\Controllers\Api\Admin\FrontEnd;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\About;
use Modules\Auth\Http\Requests\CreateAboutRequest;

class AboutController extends Controller
{
    private $aboutModel;

    public function __construct(About $about)
    {
        $this->aboutModel = $about;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index()
    {
        $about = $this->aboutModel->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'success',
            'about' => $about
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     * @return JsonResponse
     */

    public function store(CreateAboutRequest $request)
    {
        $about = $this->aboutModel->create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'success',
            'about' => $about
        ], 200);

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $about = $this->aboutModel->query()->where('id', $id)->first();
        return response()->json([
            'success' => true,
            'message' => 'success',
            'about' => $about
        ], 200);

    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $about = $this->aboutModel->query()->where('id', $id)->first();

        $about->update($request);
        return response()->json([
            'success' => true,
            'message' => 'success update',
            'about' => $about
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $about = $this->aboutModel->query()->where('id', $id)->first();
        $about->delete();
        return response()->json([
            'success' => true,
            'message' => 'success delete',
        ], 200);
    }
}
