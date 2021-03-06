<?php

use App\Http\Controllers\ImageUploadController;
use App\Models\Objects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ObjectController;
use App\Http\Controllers\ObjectPdfController;


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
Route::get('/get-collection-attributes/{id}',function($id){
   $obj=new ObjectController();
   return $obj->getAttributes($id);
});
Route::get('/get-collection-objects/{id}', function($id){
    $obj=new ObjectController();
    $objects=$obj->getObjects($id,true);
    return $objects;
});

Route::get('/get-object/{id}',function($id){
    $obj=new ObjectController();
    return $obj->getObject($id,true);
});

Route::get('/get-collection-csv/{id}',[ObjectController::class,'getCollectionCsv']);
Route::post('/get-sorted/{id}',[ObjectController::class,'getSorted']);
Route::post('/create-collection',[ ObjectController::class,'createCollection']);
Route::post('/add-object',[ ObjectController::class,'createObject']);
Route::post('/create-attributes/{id}',[ ObjectController::class,'createAttributes']);

Route::patch('/update-collection/{id}',[ObjectController::class,'updateCollection']);
Route::post('/update-object/{id}',[ObjectController::class,'updateObject']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/delete-object/{id}',[ ObjectController::class,'deleteObject']);
Route::post('/delete-collection/{id}',[ ObjectController::class,'deleteCollection']);

Route::get('/get-pdf/{id}', [ObjectPdfController::class, 'pdfview']);

//Route::get('/get-pdf/{id}',array('as'=>'pdfview','uses'=>'ObjectPdfController@pdfview'));
