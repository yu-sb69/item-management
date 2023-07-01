<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

// routes/web.php
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// 商品の在庫数を更新するルート
Route::post('/update-stock/{itemId}', [\App\Http\Controllers\ItemController::class, 'updateStock']);

// 商品を削除するルート
Route::delete('/items/{itemId}', [\App\Http\Controllers\ItemController::class, 'deleteItem'])->name('items.delete');


// 商品を検索するルート
Route::get('/search-items', [\App\Http\Controllers\ItemController::class,'search']);


// 検索をクリアするルート
Route::get('/all-items', [\App\Http\Controllers\ItemController::class, 'getAllItems']);


// 変更履歴のルート
Route::get('/historys', [\App\Http\Controllers\ItemController::class,'historys']);





Route::prefix('items')->group(function () {
    Route::get('/', [App\Http\Controllers\ItemController::class, 'index']);
    Route::get('/add', [App\Http\Controllers\ItemController::class, 'add']);
    Route::post('/add', [App\Http\Controllers\ItemController::class, 'add']);
});
