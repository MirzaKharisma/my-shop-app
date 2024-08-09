<?php

namespace App\Services;

use App\Repositories\UserRepository;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(array $userData)
    {
        if ($this->userRepository->isEmailUnique($userData['email'])) {

            $userData['password'] = bcrypt($userData['password']);

            $user = $this->userRepository->createUser($userData);

            return $user;
        }

        // Handle duplicate email scenario
        return null;
    }

    public static function login($email, $password){
        try {
            $credentials = ['email' => $email, 'password' => $password];
            if(!$token = JWTAuth::attempt($credentials)){
                return [
                    'status' => false,
                    'error' => ['Kombinasi email dan password yang kamu masukkan salah']
                ];
            }
        } catch (JWTException) {
            return [
                'status' => false,
                'error' => ['Could not create token.']
            ];
        }

        return [
            'status' => true,
            'data' => self::createNewToken($token)
        ];
    }

    protected static function createNewToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user()
        ];
    }

}
