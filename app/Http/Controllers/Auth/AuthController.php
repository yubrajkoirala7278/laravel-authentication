<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\PasswordReset;
use App\Models\User;
use App\Service\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

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


    // ==========Admin/User Logout==================
    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/login');
    }
    // ===============================================

    // ============Admin/User Forget password===================
    public function forgetPasswordLoad()
    {
        return view('auth.forget-password');
    }

    public function forgetPassword(Request $request)
    {
        try {
            $isReset = $this->authService->forgetPassword($request);
            if ($isReset) {
                return back()->with('success', 'Please check your mail to reset your password!');
            } else {
                return back()->with('error', 'Email not exist!');
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    // ========================================================


    // ============Reset Admin/User Password===================
    public function resetPasswordLoad(Request $request)
    {
        $resetDataEmail = PasswordReset::where('token', $request->token)->first()->email;
        if (isset($request->token) && isset($resetDataEmail)) {
            $userEmail = User::where('email', $resetDataEmail)->first()->email;
            return view('auth.resetPassword', compact('userEmail'));
        } else {
            return view('error.404');
        }
    }

    public function resetPassword(AuthRequest $request)
    {
        try{
            $this->authService->resetPassword($request);
            return redirect()->route('user.login')->with('success', 'Password reset successfully!');
        }catch(\Throwable $th){
            return back()->with('error',$th->getMessage());
        }
    }
    // ========================================================
}
