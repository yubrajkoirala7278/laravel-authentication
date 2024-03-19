<?php

namespace App\Service;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class AuthService{
    // =======register==========
    public function register($request){
        $user = new User();
        $user->name=$request['name'];
        $user->email=$request['email'];
        $user->password=Hash::make($request['password']);
        $user->save();
    }
    // ================================

    // =========forget password===========
    public function forgetPassword($request){
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
            return true;

        }
    }
    // ===============================


    // =========reset password===========
    public function resetPassword($request){
        $user = User::where('email', $request->email)->first();
        $user->password=Hash::make($request->password);
        $user->save();
 
        PasswordReset::where('email',$user->email)->delete();
    }
    // ==================================

}