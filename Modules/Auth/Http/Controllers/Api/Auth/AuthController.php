<?php

namespace Modules\Auth\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use JWTAuth;
use Auth;
use App\Models\User;
use Request;

use Modules\Auth\Http\Requests\CreateRequest;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use Modules\Auth\Transformers\UserResource;
use Tymon\JWTAuth\Exceptions\JWTException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    private $UserRepository;

    public function __construct(UserRepositoryInterface $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }





    public function register(CreateRequest $request)
    {
        //Validate data
        $user= $this->UserRepository->register($request);

        return ['statusCode' => 200, 'status' => true,
            'message' => 'User successfully registered ',
            'data' => new UserResource($user)
        ];


    }


    public function Login(loginRequest $request)
    {

        $user= $this->UserRepository->login($request);

        return response()->json([
            'success' => true,
            'message' => 'User successfully login',
            'user' => $user,
            'token' => $user->token,
        ]);
    }

    /**
     * Redirect the user to the Provider authentication page.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }



    /**
     * Obtain the user information from Provider.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function handleProviderCallback($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'name' => $user->getName(),
                'status' => true,
            ]
        );
        $userCreated->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $user->getId(),
            ],
            [
                'avatar' => $user->getAvatar()
            ]
        );
        $userCreated->assignRole('user');


        $token = jwtAuth::fromUser($userCreated);
        return response()->json([$userCreated, 200, 'Access-Token' => $token]);
    }

    /**
     * @param $provider
     * @return JsonResponse
     */
    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook','google'])) {
            return response()->json(['error' => 'Please login using facebook or google'], 422);
        }
    }


}
