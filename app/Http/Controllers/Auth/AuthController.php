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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
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
    public function forgetPasswordLoad(){
        return view('auth.forget-password');
    }

    public function forgetPassword(Request $request){
        try{
            $user=User::where('email',$request->email)->get();

            if(count($user)>0){
                $token=Str::random(40);
                $domain=URL::to('/');
                $url=$domain.'/reset-password?token='.$token;

                $data['url']=$url;
                $data['email']=$request->email;
                $data['title']='Password Reset';
                $data['body']='Please click on below link to reset your password.';

                // sending mail
                Mail::send('auth.forgetPasswordMail',['data'=>$data],function($message) use ($data){
                    $message->to($data['email'])->subject($data['title']);
                });

                // 
                $dateTime= Carbon::now()->format('Y-m-d H:i:s');
                PasswordReset::updateOrCreate(
                    ['email'=>$request->email],
                    [
                        'email'=>$request->email,
                        'token'=>$token,
                        'created_at'=>$dateTime
                    ]
                );

                return back()->with('success','Please check your mail to reset your password!');

            }else{
                return back()->with('error','Email not exist!');
            }

        }catch(\Throwable $th){
            return back()->with('error',$th->getMessage());
        }
    }
    // ========================================================


    // ============Reset Admin/User Password===================
    public function resetPasswordLoad(Request $request){
        $resetData = PasswordReset::where('token',$request->token)->get();

        if(isset($request->token) && count($resetData)>0){
            $user=User::where('email',$resetData[0]['email'])->get();
            return view('auth.resetPassword',compact('user'));
        }else{
            return view('error.404');
        }
    }

    public function resetPassword(Request $request){
        $request->validate([
            'password' => ['required', 'string', 'confirmed', 'min:6'],
            'password_confirmation' => ['required', 'min:6', 'same:password'],
        ]);

       $user= User::find($request->id);
       $user->password=Hash::make($request->password);
       $user->save();

       PasswordReset::where('email',$user->email)->delete();

       return redirect()->route('user.login')->with('success','Password reset successfully!');
    }
    // ========================================================
}
