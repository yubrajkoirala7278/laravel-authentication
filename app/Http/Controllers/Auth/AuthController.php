<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Service\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // =======Constructor==========
    private $authService;
    public function __construct()
    {
        $this->authService = new AuthService();
    }
    // ============================

    // =======Register/Create an account=======
    public function loadRegister()
    {
        if (Auth::user() && Auth::user()->is_admin == 1) {
            return redirect('/admin/dashboard');
        } else if (Auth::user() && Auth::user()->is_admin == 0) {
            return redirect('/user/dashboard');
        }
        return view('auth.register');
    }
    public function userRegister(AuthRequest $request)
    {
        try {
            $this->authService->register($request->validated());
            return redirect()->route('user.login')->with('success', 'Registration Successful');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    // ===========================================

    // =========Admin/User login========================
    public function loadLogin()
    {
        if (Auth::user() && Auth::user()->is_admin == 1) {
            return redirect('/admin/dashboard');
        } else if (Auth::user() && Auth::user()->is_admin == 0) {
            return redirect('/user/dashboard');
        }
        return view('auth.login');
    }

    public function userLogin(AuthRequest $request)
    {
        try {
            $userCredential = $request->only('email', 'password');
            if (Auth::attempt($userCredential)) {
                // login to admin dashboard
                if (Auth::user()->is_admin == 1) {
                    return redirect()->route('admin.dashboard')->with('success', 'You are logged in');
                } else {
                    // login to user dashboard
                    return redirect()->route('user.dashboard')->with('success', 'You are logged in');
                }
            } else {
                return back()->with('error', 'Email or password is incorrect');
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    // ===========================================


    // ==========Admin/User login==================
    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/login');
    }
}
