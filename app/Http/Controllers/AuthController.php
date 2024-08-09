<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function register(RegisterUserRequest $request)
    {
        // Validate input using RegisterUserRequest
        $validatedData = $request->validated();

        // Call the registerUser method from the UserService
        $user = $this->authService->registerUser($validatedData);

        if ($user) {
            // Registration successful
            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user
            ], 201);
        }

        // Handle registration failure
        return response()->json([
            'message' => 'User registration failed. Email exists.'
        ], 422);
    }

    public function login(AuthRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'message' => $request->validator->errors()
            ], 422);
            // return response()->failed($request->validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');
        $login       = AuthService::login($credentials['email'], $credentials['password']);

        if (!$login['status']) {
            return response()->json([
                'message' => $login['error']
            ], 422);
            // return response()->failed($login['error'], 422);
        }

        return response()->json([
            'message' => 'login successfuly',
            'data' => $login['data']
        ], 200);
        // return response()->success($login['data']);
    }
}
