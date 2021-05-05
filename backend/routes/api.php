<?php

use App\Http\Controllers\ImageUploadController;
use App\Models\Objects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ObjectController;
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
//Route::get('/get-collection-objects/{id}', function($id){
//    $obj=new ObjectController();
//    $objects=$obj->getObjects($id);
//    return $objects;
//});
Route::get('/get-collection-attributes/{id}',function($id){
   $obj=new ObjectController();
   return $obj->getAttributes($id);
});
Route::get('/get-collection-objects/{id}', function($id){
    $obj=new ObjectController();
    $objects=$obj->getObjects($id);
    return $objects;
});

Route::get('/get-object/{id}',function($id){
    $obj=new ObjectController();
    return $obj->getObject($id,true);
});

Route::post('/create-collection',[ ObjectController::class,'createCollection']);
Route::post('/add-object',[ ObjectController::class,'createObject']);
Route::post('/create-attributes/{id}',[ ObjectController::class,'createAttributes']);

Route::patch('/update-collection/{id}',[ObjectController::class,'updateCollection']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/delete-item/{id}',[ ObjectController::class,'deleteItem']);
Route::post('/delete-collection/{id}',[ ObjectController::class,'deleteCollection']);
