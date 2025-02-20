<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\PermissionsController;
use App\Http\Controllers\ImageController;





// Public Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::post('/forgot-password', [UserController::class, 'sendResetLinkEmail']);



// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/loggeduser', [UserController::class, 'logged_user']);
    // Reset password form
    Route::get('/reset-password/{token}', function ($token) {
        return response()->json(['token' => $token]);
    })->name('password.reset');

    Route::post('/reset-password', [UserController::class, 'reset']);

    //List
    Route::get('/role_list', [UserController::class, 'role_list']);
    Route::get('/permission_list', [UserController::class, 'permission_list']);
    Route::get('/user_list', [UserController::class, 'user_list']);

    Route::get('/user_show/{id}', [UserController::class, 'show']);

    //User Update
    Route::get('/user_edit/{id}', [UserController::class, 'user_edit']);
    Route::put('/user_update/{id}', [UserController::class, 'user_update']);

    Route::get('/role_wise_user', [UserController::class, 'role_wise_user']);

    //User Update
    Route::put('/user_update/{id}', [UserController::class, 'user_update']);

    //User Delete
    Route::delete('/user_delete/{id}', [UserController::class, 'user_delete']);

    //Assign Permission
    Route::put('/assign_permission/{id}', [UserController::class, 'assign_permission']);

    //role & permission
    Route::resource('roles', RolesController::class);
    Route::resource('permissions', PermissionsController::class);

    

});

Route::post('/remove-bg', [ImageController::class, 'removeBackground']);
Route::post('/remove-bg-zip', [ImageController::class, 'removeBackgroundZip']);

Route::post('/remove-bg-profile', [ImageController::class, 'removeBackgroundProfile']);
Route::post('/remove-bg-signature', [ImageController::class, 'removeBackgroundSignature']);


