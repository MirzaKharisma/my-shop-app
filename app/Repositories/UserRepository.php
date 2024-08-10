<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository{
    protected $userModel;

    public function __construct(User $userModel) {
        $this->userModel = $userModel;
    }

    public function findByEmail($email){
        return $this->userModel->query()->where('email', $email)->first();
    }

    public function isEmailUnique($email)
    {
        return $this->userModel->query()->where('email', $email)->doesntExist();
    }

    public function createUser(array $userData)
    {
        return $this->userModel->query()->create($userData);
    }
}
