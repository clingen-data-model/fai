<?php

use Illuminate\Http\Request;
use App\Actions\AssayClassFind;
use App\Actions\AssayClassList;
use App\Actions\AssayClassCreate;
use App\Actions\AssayClassUpdate;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'assay-classes'
], function () {
    Route::post('/', AssayClassCreate::class);
    Route::get('/', AssayClassList::class);
    Route::get('/{assayClass}', AssayClassFind::class);
    Route::put('/{assayClass}', AssayClassUpdate::class);
});
