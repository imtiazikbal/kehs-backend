<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\HomeController;
use App\Http\Controllers\api\v1\AdminController;
use App\Http\Controllers\api\v1\NoticeController;
use App\Http\Controllers\api\v1\TeacherController;
use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\HomeBannerController;
use App\Http\Controllers\api\v1\InstitutionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Admin routes
Route::group(['prefix' => 'v1'], function () {

    Route::post('/admin/signup', [AdminController::class, 'signup']);
    Route::post('/admin/login', [AdminController::class, 'login']);
    Route::get('admin', [AdminController::class, 'tokenVarificationForAdmin'])->middleware('api.auth');
    Route::post('/admin/auth/verify', [AdminController::class, 'verifyOtp']);
    Route::post('/admin/verify-token', [AdminController::class, 'varifyToken']);

});

// Category routes here
Route::group(['prefix' => 'v1'], function () {
    // Store Category
    Route::post('/category', [CategoryController::class, 'storeCategory'])->middleware('api.auth');
    // Update Category
    Route::post('/category/{id}', [CategoryController::class, 'updateCategory'])->middleware('api.auth');
    // Update category Status
    Route::patch('/category/status/{id}', [CategoryController::class, 'updateCategoryStatus'])->middleware('api.auth');
    // Delete Category
    Route::delete('/category/{id}', [CategoryController::class, 'deleteCategory'])->middleware('api.auth');
    // get all categories
    Route::get('/category', [CategoryController::class, 'getAllCategory'])->middleware('api.auth');

    // Gel all category sub sub sub cat for admin
    Route::get('/admin/product/allcategory', [CategoryController::class, 'getAllCategoryForAdmin']);

    // get all unique category
    Route::get('/unique/category', [CategoryController::class, 'getUniqueCategoryStatusActive']);


});

/// Fontend routes here

// SubSubCategory routes here
Route::group(['prefix' => 'v1'], function () {
    // Route::get('/header/category', [HomeController::class,'getHeaderCategories']);
    Route::get('/header/footer', [HomeController::class, 'getHeaderFooter']);

    // landing page all info
    Route::get('/landingpage', [HomeController::class, 'getLandingPage']);
});
// notice
Route::get('/v1/notice', [NoticeController::class, 'index']);
Route::post('/v1/notice', [NoticeController::class, 'notice']);

// home banner 
Route::post('/v1/homebanner', [HomeBannerController::class, 'store']);
Route::get('/v1/admin/homebanner', [HomeBannerController::class, 'index']);


// institution
Route::get('/v1/institution/info', [InstitutionController::class, 'getInstitution']);
Route::post('/v1/institution', [InstitutionController::class, 'storeOrUpdateInstitution']);

// techer
Route::post('/v1/teachers', [TeacherController::class, 'store']);
Route::post('/v1/teachers/{id}', [TeacherController::class, 'update']);
Route::delete('/v1/teachers/{id}', [TeacherController::class, 'destroy']);
Route::get('/v1/teachers', [TeacherController::class, 'index']);

Route::get('/v1/teacher/{id}', [TeacherController::class, 'getTeacherById']);
