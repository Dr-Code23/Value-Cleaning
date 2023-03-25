<?php

namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Http\Requests\CreateRequest;
use Modules\Auth\Http\Requests\loginRequest;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use Modules\Auth\Transformers\UserResource;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private $UserRepository;

    public function __construct(UserRepositoryInterface $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    /**
     * @param CreateRequest $request
     * @return mixed
     */
    public function register(CreateRequest $request): mixed
    {
        return $this->UserRepository->register($request);

    }


    /**
     * @param loginRequest $request
     * @return mixed
     */
    public function Login(loginRequest $request): mixed
    {

        $user = $this->UserRepository->login($request);

        return $user;


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
        return response()->json(['statusCode' => 200, 'status' => true,
            'message' => ' success  ', 'data' => new UserResource($userCreated), 'Access-Token' => $token]);
    }

    /**
     * @param $provider
     * @return JsonResponse
     */
    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            return response()->json(['error' => 'Please login using facebook or google'], 422);
        }
    }

    public function notification()
    {

        return $this->UserRepository->notification();

    }

    public function deleteNotification($id)
    {

        return $this->UserRepository->deleteNotification($id);

    }


}
