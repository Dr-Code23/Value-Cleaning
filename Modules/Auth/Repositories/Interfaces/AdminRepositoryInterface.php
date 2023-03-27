<?php

namespace Modules\Auth\Repositories\Interfaces;

interface AdminRepositoryInterface
{
    public function register($data);

    public function Login($data);

    public function forgotPassword($data);

    public function checkCode($data);

    public function reset($data);

    public function profile();

    public function updateProfile($data);

    public function changePassword($data);

    public function pushNotification($data);

    public function all();


}






