<?php

namespace Modules\Auth\Http\Controllers\Api\Admin\FrontEnd;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\Footer;
use Modules\Auth\Http\Requests\CreateFooterRequest;
use Modules\Auth\Http\Requests\updateFooterRequest;

class FooterController extends Controller
{
    /**
     * @var Footer
     */

    protected Footer $footerModel;

    /**
     * @param Footer $footer
     */
    public function __construct(Footer $footer)
    {
        $this->footerModel = $footer;
    }

    /**
     * @return JsonResponse
     */

    public function index()
    {
        $footers = $this->footerModel->all();

        return response()->json($footers);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function store(CreateFooterRequest $request)
    {
        $footer = $this->footerModel->create($request->all());

        return response()->json($footer, 201);
    }

    /**
     * @param $id
     * @return JsonResponse
     */

    public function show($id)
    {
        $footer = $this->footerModel->findOrFail($id);

        return response()->json($footer);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */

    public function update(UpdateFooterRequest $request, $id)
    {
        $footer = $this->footerModel->findOrFail($id);
        $footer->update($request->all());

        return response()->json($footer);
    }

    /**
     * @param $id
     * @return JsonResponse
     */

    public function destroy($id)
    {
        $footer = $this->footerModel->findOrFail($id);
        $footer->delete();

        return response()->json(null, 204);
    }
}
