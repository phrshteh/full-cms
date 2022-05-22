<?php

use Phrshte\FullCms\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use Phrshte\FullCms\Http\Controllers\Admin\CategoryExtraFieldController;
use Phrshte\FullCms\Http\Controllers\Admin\CommentController as AdminCommentController;
use Phrshte\FullCms\Http\Controllers\Admin\ContentController as AdminContentController;
use Phrshte\FullCms\Http\Controllers\Admin\MediaController;
use Phrshte\FullCms\Http\Controllers\Admin\SettingController;
use Phrshte\FullCms\Http\Controllers\Admin\UpdateCommentController;
use Phrshte\FullCms\Http\Controllers\CategoryController;
use Phrshte\FullCms\Http\Controllers\CommentController;
use Phrshte\FullCms\Http\Controllers\ContentController;
use Phrshte\FullCms\Http\Controllers\ContentSearchController;
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


Route::name('admin.')->middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {

    Route::apiResource('categories', AdminCategoryController::class);
    Route::apiResource('comments', AdminCommentController::class);
    Route::apiResource('contents', AdminContentController::class);
    Route::apiResource('settings', SettingController::class);
    Route::apiResource('media', MediaController::class)->only('store');


    Route::apiResource('categories.extra-fields', CategoryExtraFieldController::class)->only(['index', 'store']);
    Route::get('extra-fields/{extraField}', [CategoryExtraFieldController::class, 'show']);
    Route::patch('extra-fields/{extraField}', [CategoryExtraFieldController::class, 'update']);
    Route::delete('extra-fields/{extraField}', [CategoryExtraFieldController::class, 'destroy']);

    Route::patch('comments/{comment}/edit', [UpdateCommentController::class, 'update']);

});

Route::apiResource('contents', ContentController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class);
Route::get('/categories/{category}/{content}', [ContentController::class, 'show']);
Route::post('comments', [CommentController::class, 'store']);
Route::get('contents-search', [ContentSearchController::class, 'index']);




