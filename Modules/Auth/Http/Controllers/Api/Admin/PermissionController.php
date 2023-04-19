<?php

namespace Modules\Auth\Http\Controllers\Api\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\About;
use Modules\Auth\Http\Requests\CreateAboutRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{

    private $permissionModel;

    public function __construct(Permission $permission)
    {
        $this->permissionModel = $permission;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index()
    {
        $permission = $this->permissionModel->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'success',
            'permission' => $permission
        ], 200);
    }

}
