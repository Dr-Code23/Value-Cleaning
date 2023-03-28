<?php

namespace Modules\Auth\Http\Controllers\Api\Admin\FrontEnd;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\ContactUs;
use Modules\Auth\Events\Contact_us;

class ContactUsController extends Controller
{

    /**
     * @var ContactUs
     */
    protected ContactUs $contactUsModel;

    /**
     * @param ContactUs $contactUs
     */

    public function __construct(ContactUs $contactUs)
    {
        $this->contactUsModel = $contactUs;
    }

    /**
     * @return JsonResponse
     */

    public function index()
    {
        $messages = $this->contactUsModel->all();

        return response()->json($messages);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function store(Request $request)
    {
        $message = $this->contactUsModel->create($request->all());

        event(new Contact_us($message));

        return response()->json($message, 201);


    }

    /**
     * @param $id
     * @return JsonResponse
     */

    public function show($id)
    {
        $message = $this->contactUsModel->findOrFail($id);

        return response()->json($message);
    }

    /**
     * @param $id
     * @return JsonResponse
     */


    public function destroy($id)
    {
        $message = $this->contactUsModel->findOrFail($id);
        $message->delete();

        return response()->json(null, 204);
    }
}
