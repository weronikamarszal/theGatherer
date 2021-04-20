<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Collection;
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
Route::get('/get-collections', function(){
    return Collection::all();
});
Route::get('/get-collections/{collection}', function(Collection $collection){
    return $collection;
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
