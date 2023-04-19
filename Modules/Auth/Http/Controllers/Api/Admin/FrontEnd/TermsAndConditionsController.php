<?php

namespace Modules\Auth\Http\Controllers\Api\Admin\FrontEnd;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\TermsAndConditions;
use Modules\Auth\Http\Requests\CreateTermsAndConditionsRequest;
use Modules\Auth\Http\Requests\updateTermsAndConditionsRequest;

class TermsAndConditionsController extends Controller
{

    protected $termsAndConditionsModel;

    public function __construct(TermsAndConditions $termsAndConditions)
    {
        $this->termsAndConditionsModel = $termsAndConditions;
    }

    public function index()
    {
        $termsAndConditions = $this->termsAndConditionsModel->all();
        return response()->json(['success' => true, $termsAndConditions]);
    }

    public function store(CreateTermsAndConditionsRequest $request)
    {
        $termsAndConditions = new TermsAndConditions([
            'title' => $request->input('title'),
            'content' => $request->input('content')
        ]);

        $termsAndConditions->save();

        return response()->json(['success' => true, 'message' => 'Terms and conditions created successfully']);
    }

    public function show($id)
    {
        $termsAndConditions = $this->termsAndConditionsModel->find($id);
        return response()->json(['success' => true, $termsAndConditions]);
    }

    public function update(updateTermsAndConditionsRequest $request, $id)
    {
        $termsAndConditions = $this->termsAndConditionsModel->find($id);

        $termsAndConditions->title = $request->input('title');
        $termsAndConditions->content = $request->input('content');

        $termsAndConditions->save();

        return response()->json(['success' => true, 'message' => 'Terms and conditions updated successfully']);
    }

    public function destroy($id)
    {
        $termsAndConditions = $this->termsAndConditionsModel->find($id);
        $termsAndConditions->delete();

        return response()->json(['success' => true, 'message' => 'Terms and conditions deleted successfully']);
    }
}
