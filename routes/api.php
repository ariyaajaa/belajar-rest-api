<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RecipeController;

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::get('recipes',[RecipeController::class,'show_recipes']);
Route::post('recipes/get-recipe',[RecipeController::class,'recipe_by_id']);
Route::post('recipes/rating',[RecipeController::class,'rating']);
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['admin.api'])->prefix('admin')->group(function(){
    Route::get('dashboard',[AdminController::class,'dashboard']);
    Route::post('register',[AdminController::class,'register']);
    Route::get('register',[AdminController::class,'show_register']);  
    Route::get('register/{id}',[AdminController::class,'show_register_by_id']);  
    Route::put('register/{id}',[AdminController::class,'update_register']);  
    Route::delete('register/{id}',[AdminController::class,'delete_register']);  
    Route::get('activation-account/{id}',[AdminController::class,'activation_account']);  
    Route::get('deactivation-account/{id}',[AdminController::class,'deactivation_account']);
    Route::post('create-recipe',[AdminController::class,'create_recipe']);
    Route::put('update-recipe/{id}',[AdminController::class,'update_recipe']);
    Route::delete('delete-recipe/{id}',[AdminController::class,'delete_recipe']);
    Route::get('publish/{id}',[AdminController::class,'publish_recipe']);
    Route::get('unpublish/{id}',[AdminController::class,'unpublish_recipe']);
});
Route::middleware(['user.api'])->prefix('user')->group(function(){
    Route::post('submit-recipe',[UserController::class,'create_recipe']);
});
