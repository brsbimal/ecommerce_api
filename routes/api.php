<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\ShopController;
use App\Http\Controllers\API\V1\AdminBlogController;
use App\Http\Controllers\API\V1\FrontendController;
use App\Http\Controllers\API\V1\AuthController;


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

// admin related API
Route::group(['middleware' => ['auth:api']], function(){
    Route::get('/get-all-categories', [CategoryController::class,'getCategories']);
    Route::Post('/category/create', [CategoryController::class,'createCategory']);
    Route::post('/get-category-by-id/{id}', [CategoryController::class,'getCategoryById']);
    Route::Post('/category/{id}/update', [CategoryController::class,'updateCategory']);
    Route::Post('/category/{id}/delete', [CategoryController::class,'deleteCategory']);


    Route::get('/get-all-products', [ProductController::class,'getProducts']);
    Route::Post('/product/create', [ProductController::class,'createProduct']);
    Route::post('/get-product-by-id/{id}', [ProductController::class,'getProductById']);
    Route::Post('/product/{id}/update', [ProductController::class,'updateProduct']);
    Route::Post('/product/{id}/delete', [ProductController::class,'deleteProduct']);


    Route::get('/blogs',[AdminBlogController::class,'getBlogs'])->name('blog');
    Route::post('/blog',[AdminBlogController::class,'createBlog'])->name('blog.create');
    Route::get('/get-blog-by-id/{id}',[AdminBlogController::class,'getBlogById'])->name('blog.byId');
    Route::post('/blog/update',[AdminBlogController::class,'updateBlog'])->name('blog.update');
    Route::post('/blog/delete',[AdminBlogController::class,'deleteBlog'])->name('blog.delete');
    Route::post('/upload-blog-image',[AdminBlogController::class,'uploadBlogImage'])->name('blog.uploadimage');

});


Route::post('/user/create', [AuthController::class, 'createUser']); //user and customer registration.
Route::post('/user/update', [AuthController::class, 'updateUser']); //user and customer update.
Route::post('/user/delete', [AuthController::class, 'deleteUser']); //user and customer delete.
Route::post('/user/login', [AuthController::class, 'loginUser']);

// Route::middleware('auth:api')->group(function (){

//     // Route::middleware(['scopes:*'])->group( function()
//     // {
//         Route::get('/users', [AuthController::class, 'getUsers']);

//     // });

//     Route::middleware('scope:customer')->group( function()
//     {
//         Route::get('/customers', [AuthController::class, 'getUsers']);

//     });
// });

Route::group(['middleware' => ['auth:api']], function(){

    Route::get('/users', [AuthController::class, 'getUsers']);

});

Route::group(['middleware' => ['auth:api']], function(){

    Route::get('/customers', [AuthController::class, 'getUsers']);


});



// Frontend Related API

Route::get('/get-categories', [ShopController::class,'getCategories']);
Route::get('/get-categories-by-product-count', [ShopController::class,'getCategoriesByProductCount']);
Route::get('/get-products', [ShopController::class,'getProducts']);
Route::get('/get-product-details/{id}', [ShopController::class,'getProductDetails']);

//blogs related 
Route::get('/get-blogs',[FrontendController::class,'getBlogs']);