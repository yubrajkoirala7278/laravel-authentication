<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

// ============frontend================
// home-page
Route::get('/',[HomeController::class,'index'])->name('index');


// ============admin dashboard============
Route::group(['middleware'=>['web','checkAdmin']],function(){
    Route::prefix('admin')->group(function(){
        require __DIR__.'/admin.php';
    });
});

// ============user dashboard==================
Route::group(['middleware'=>['web','checkUser']],function(){
    Route::prefix('user')->group(function(){
        require __DIR__.'/user.php';
    });
});



// ============Auth==========================
// register user
Route::get('/register',[AuthController::class,'loadRegister']);
Route::post('/register',[AuthController::class,'userRegister'])->name('user.register');

// login user/admin
Route::get('/login',[AuthController::class,'loadLogin']);
Route::post('/login',[AuthController::class,'userLogin'])->name('user.login');

// logout user/admin
Route::get('/logout',[AuthController::class,'logout'])->name('logout');


// ==========================================