<?php

namespace Modules\Requirement\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Requirement\Entities\Requirement;
use Modules\Requirement\Http\Requests\CreateRequest;
use Modules\Requirement\Http\Requests\UpdateRequest;
use Modules\Requirement\Transformers\RequirementResource;

class RequirementController extends Controller
{
    private $requirementModel;

    public function __construct(Requirement $requirement)
    {
        $this->requirementModel = $requirement;
    }

    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $requirements = $this->requirementModel->latest()->get();
        return RequirementResource::collection($requirements);
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return array
     */
    public function store(CreateRequest $request)
    {
        $requirements = $this->requirementModel->create([
            'title' => [
                'en' => $request['title_en'],
                'sv' => $request['title_sv'],
            ],
            'requirement_price' => $request['requirement_price'],
            'service_id' => $request['service_id']
        ]);
        return ['statusCode' => 200, 'status' => true, 'message' => 'Created successfully', 'data' => new RequirementResource($requirements)];

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return array
     */
    public function show($id)
    {
        $requirement = $this->requirementModel->findOrFail($id);
        return ['statusCode' => 200,
            'status' => true,
            'data' => new RequirementResource($requirement)];
    }

    public function showWith($id)
    {
        $requirement = $this->requirementModel->where('service_id', $id)->get();
        return ['statusCode' => 200,
            'status' => true,
            'data' => $requirement];
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $requirement = $this->requirementModel->where('id', $id)->first();
        $requirement->update([
            'title' => [
                'en' => $request['title_en'],
                'sv' => $request['title_sv'],
            ],
            'requirement_price' => $request['requirement_price'],
            'count' => $request['count'],
            'service_id' => $request['service_id']
        ]);
        return ['statusCode' => 200,
            'status' => true,
            'message' => 'Requirement updated successfully ',
            'data' => new RequirementResource($requirement)];
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return array
     */
    public function destroy($id)
    {
        $requirement = $this->requirementModel->find($id);
        $requirement->delete();
        return ['statusCode' => 200, 'status' => true, 'message' => 'Deleted successfully'];

    }
}
