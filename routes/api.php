<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
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
Route::get('test', function (Request $request){
    return $request;
});


// Методы для регистрации/авторизации пользователей.
Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function (){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('logout', [AuthController::class, 'logout']);

    // Метод для получения списка заказов авторизированного пользователя.

    Route::get('orders', [OrderController::class, 'index']);
});

// Метод для получения дерева категорий.
Route::get('categories', [CategoryController::class, 'index']);

// Метод для получения товаров. Должен поддерживать фильтрацию по категории/категориям любого уровня, а также по цене и дополнительным характеристикам. Значения фильтров должны валидироваться.
Route::get('products', [ProductController::class, 'index']);

// Метод для получения товара по slug.
Route::get('products/{slug}', [ProductController::class, 'getBySlug']);

// Методы для работы с корзиной (добавление товара, редактирование количества товара/товаров, удаление товара).

    // Создать корзину (для гостя или авторизованного)
    Route::post('cart', [CartController::class, 'store']);

    // Добавить товар в корзину
    Route::post('cart/item', [CartItemController::class, 'store']);

    // Редактировать товар в корзине
    Route::post('cart/item/{id}', [CartItemController::class, 'update']);

    // Удалить товар в корзине
    Route::delete('cart/item/{id}', [CartItemController::class, 'destroy']);


// Метод для оформления заказа.

    // Создать заказ (для гостя или авторизованного), вытянуть контакты и корзину
    Route::post('order', [OrderController::class, 'store']);





