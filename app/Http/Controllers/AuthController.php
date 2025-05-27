<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    /**
     * 註冊新用戶
     */
    public function register(Request $request)
    {
        // Validate the request data
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $result = $this->authService->register($data);

        return response()->json($result, 201);
    }

    /**
     * 用戶登錄
     */
    public function login(Request $request)
    {
        // Validate the request data
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $result = $this->authService->login($data);

        return response()->json($result);
    }


    /**
     * 登出（清除token）
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json(['message' => '成功登出']);
    }

    /**
     * 取得登入者資訊
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
