<?php

namespace Modules\Auth\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Modules\Auth\Http\Requests\CreateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Http\Requests\UpdateRequest;
use Modules\Auth\Transformers\UserResource;

class UserController extends Controller
{

    private $userModel;

    public function __construct(User $user)
    {
        $this->userModel = $user;

    }


    public function index(Request $request)
    {
        if ($request->q) {
            $data = $this->userModel->where("email", "like", "%$request->q%")
                ->orwhere("name", "like", "%$request->q%")->orderBy('id', 'DESC')->get();
            return response()->json([
                'success' => true,
                'user' => UserResource::collection($data)
            ], 201);
        }
        $data = $this->userModel->query()->whereHas('roles', function ($query) {

            $query->where('name', 'user');

        })->latest()->get();
        return response()->json([
            'success' => true,
            'user' => UserResource::collection($data)
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
    public function store(CreateRequest $request)
    {


        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = $this->userModel->create($input);
        $user->assignRole($request->input('roles'));
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'user' => $user
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $user = $this->userModel->find($id);
        return response()->json([
            'success' => true,
            'message' => 'User',
            'user' => $user
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, $id)
    {


        $input = $request;
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except((array)$input, array('password'));
        }

        $user = $this->userModel->find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => $user
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
        $user = $this->userModel->find($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'success', 'User deleted successfully',
            'user' => $user
        ], 201);


    }
}
