<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Service\AuthService;

class AuthController extends Controller
{
    // =======constructor==========
    private $authService;
    public function __construct() {
        $this->authService = new AuthService();
    }
    // ============================

    // =======register or create an account=======
    public function loadRegister()
    {
        return view('auth.register');
    }
    public function studentRegister(AuthRequest $request)
    {
        try {
            $this->authService->register($request->validated());
            return back()->with('success','Registration Successful');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    // ===========================================
}
