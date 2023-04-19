<?php

namespace Modules\Auth\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Auth\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{

    private $roleModel;
    private $permissionModel;

    public function __construct(Role $role, Permission $permission)
    {
        $this->roleModel = $role;
        $this->permissionModel = $permission;

        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store', 'update', 'destroy']]);
        $this->middleware('permission:role-create', ['only' => ['store']]);
        $this->middleware('permission:role-edit', ['only' => ['update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);


    }


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $roles = $this->roleModel->orderBy('id', 'DESC')->paginate(5);
        return response()->json([
            'success' => true,
            'message' => 'success',
            'roles' => $roles
        ], 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request)
    {

        $role = $this->roleModel->create(['name' => $request->input('name')]);
        $role->syncPermissions([$request->input('permission')]);
        return response()->json([
            'success' => true,
            'message' => 'success', 'U Role created successfully',
            'role' => $role
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse|Response
     */
    public function show($id)
    {
        $role = $this->roleModel->find($id);
        $rolePermissions = $this->permissionModel->join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'success',
            'role' => $role,
            'rolePermissions' => $rolePermissions

        ], 201);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(RoleRequest $request, $id)
    {
        $role = $this->roleModel->find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));
        return response()->json([
            'success' => true,
            'message' => 'success Role updated successfully',
            'role' => $role,

        ], 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $role = DB::table("roles")->where('id', $id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'success Role deleted successfully',
            'role' => $role,

        ], 201);

    }
}
