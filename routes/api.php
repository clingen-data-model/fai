<?php

use Illuminate\Http\Request;
use App\Actions\AssayClassFind;
use App\Actions\AssayClassList;
use App\Actions\PublicationFind;
use App\Actions\PublicationList;
use App\Actions\AssayClassCreate;
use App\Actions\AssayClassUpdate;
use App\Actions\CodingSystemFind;
use App\Actions\CodingSystemList;
use App\Actions\PublicationCreate;
use App\Actions\PublicationUpdate;
use App\Actions\CodingSystemCreate;
use App\Actions\CodingSystemUpdate;
use App\Actions\FunctionalAssayFind;
use App\Actions\FunctionalAssayList;
use Illuminate\Support\Facades\Route;
use App\Actions\FunctionalAssayCreate;
use App\Actions\FunctionalAssayUpdate;
use App\Actions\OtherAssayClassCreate;

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

Route::group([
    'prefix' => 'coding-systems'
], function () {
    Route::post('/', CodingSystemCreate::class);
    Route::get('/', CodingSystemList::class);
    Route::get('/{codingSystem}', CodingSystemFind::class);
    Route::put('/{codingSystem}', CodingSystemUpdate::class);
});

Route::group([
    'prefix' => 'functional-assays'
], function () {
    Route::post('/', FunctionalAssayCreate::class);
    Route::get('/', FunctionalAssayList::class);
    Route::get('/{functionalAssay}', FunctionalAssayFind::class);
    Route::put('/{functionalAssay}', FunctionalAssayUpdate::class);
});


Route::group([
    'prefix' => 'publications'
], function () {
    Route::post('/', PublicationCreate::class);
    Route::get('/', PublicationList::class);
    Route::get('/{publication}', PublicationFind::class);
    Route::put('/{publication}', PublicationUpdate::class);
});
