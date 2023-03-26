<?php

namespace Modules\Auth\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\Notification;
use Modules\Auth\Http\Requests\CreateCompanyRequest;
use Modules\Auth\Http\Requests\UpdateRequest;
use Modules\Auth\Notifications\ApprovedNotification;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use Modules\Order\Notifications\TaskReminderNotification;

class CompanyController extends Controller
{
    private $userRepository;
    private $userModel;

    public function __construct(UserRepositoryInterface $userRepository, User $user)
    {
        $this->userRepository = $userRepository;
        $this->userModel = $user;
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function approved($id)
    {

        $company = $this->userModel->query()->where(['id' => $id, 'type' => 'company'])->first();
        if ($company) {
            $company->approved = !$company->approved;
            $company->save();

            $company->notify(new ApprovedNotification($company));

            return response()->json(['status' => $company->approved, 'msg' => 'updated successfully']);

        }
        return response()->json(['status' => 400, 'msg' => 'invalid id']);
    }

    /**
     * @param CreateCompanyRequest $request
     * @return mixed
     *
     */
    public function companyRegister(CreateCompanyRequest $request): mixed
    {
        return $this->userRepository->companyRegister($request);

    }

    /**
     * @return mixed
     */
    public function profile(): mixed
    {
        return $this->userRepository->profile();

    }

    /**
     * @param UpdateRequest $request
     * @return mixed
     * /**
     *
     */

    public function updateProfile(UpdateRequest $request)
    {


        return $this->userRepository->updateProfile($request);

    }

    public function allCompanies()
    {
        return $this->userRepository->allCompanies();
    }

    public function showCompany($id)
    {
        return $this->userRepository->showCompany($id);
    }

    public function allCompaniesApproved()
    {
        return $this->allCompaniesApproved();
    }
}
