<?php

use App\Http\Controllers\Api\Admin\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\MainCategoriesController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware'=>['api','checkpassword','checklanguage'],'namespace' => 'Api'], function (){
    Route::post('category',[MainCategoriesController::class,'index']);
    Route::post('getcategorybyid',[MainCategoriesController::class,'getCategoryById']);
    Route::post('get-category-byid',[MainCategoriesController::class,'getCategoryById']);
    Route::post('change-category-status', [MainCategoriesController::class,'changeStatus']);
    Route::post('show', [MainCategoriesController::class,'show']);
    Route::post('add-category', [MainCategoriesController::class,'addCategory']) -> middleware(['auth.guard:admin-api']);
    Route::group(['prefix' => 'admin','namespace'=>'Admin'],function (){
        Route::post('login', [AuthController::class,'login']);
        
        Route::post('logout',[AuthController::class,'logout'])
         -> middleware(['auth.guard:admin-api']);
        
          
    });
    

});
Route::group(['prefix' => 'user','namespace'=>'User'],function (){
    Route::post('login','AuthController@Login') ;
});
Route::group(['prefix' => 'user' ,'middleware' => 'auth.guard:user-api'],function (){
    Route::post('profile',function(){
        return  \auth()::user(); // return authenticated user data
    }) ;


 });

Route::group(['middleware' => ['api','checkpassword','checklanguage','checkAdminToken:admin-api'], 'namespace' => 'Api'], function () {
    Route::post('offers', [MainCategoriesController::class,'index']);
});
