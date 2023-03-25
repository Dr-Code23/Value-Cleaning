<?php

namespace Modules\Auth\Http\Controllers\Api\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{

    private $permissionModel;

    public function __construct(Role $role, Permission $permission)
    {
        $this->roleModel = $role;
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

    /**
     * Show the form for creating a new resource.
     * @return JsonResponse
     */

    public function store(Request $request)
    {
        $permission = $this->permissionModel->create([
            'name' => $request->name,
            'guard_name' => 'api',
        ]);
        return response()->json([
            'success' => true,
            'message' => 'success',
            'permission' => $permission
        ], 200);

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $permission = $this->permissionModel->query()->where('id', $id)->first();
        return response()->json([
            'success' => true,
            'message' => 'success',
            'permission' => $permission
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
        $permission = $this->permissionModel->query()->where('id', $id)->first();

        $permission->update($request);
        return response()->json([
            'success' => true,
            'message' => 'success update',
            'permission' => $permission
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $permission = $this->permissionModel->query()->where('id', $id)->first();
        $permission->delete();
        return response()->json([
            'success' => true,
            'message' => 'success delete',
        ], 200);
    }
}
