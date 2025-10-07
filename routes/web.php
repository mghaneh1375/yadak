<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\ReportController;

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


Route::get('/', [BaseController::class, 'home'])->name('root');

Route::get('home', [BaseController::class, 'home'])->name('home');

Route::get('aboutUs', [BaseController::class, 'aboutUs'])->name('aboutUs');

Route::get('contactUs', [BaseController::class, 'contactUs'])->name('contactUs');

Route::get('product/{id?}', [ProductController::class, 'product'])->name('product');

Route::get('productsInCategory/{id}', [ProductController::class, 'productsInCategory'])->name('productsInCategory');

Route::get('products/{categoryId}', [ProductController::class, 'products'])->name('products');

Route::post('doLogin', [BaseController::class, 'doLogin'])->name('doLogin');

Route::get('faq', [BaseController::class, 'faq'])->name('faq');

Route::get('login', [BaseController::class, 'login'])->name('login');

Route::get('logout', [BaseController::class, 'logout'])->name('logout');

Route::post('registry', [BaseController::class, 'registry'])->name('registry');

Route::post('forgetPass', [BaseController::class, 'forgetPass'])->name('forgetPass');

Route::post('resendActivation', [BaseController::class, 'resendActivation'])->name('resendActivation');

Route::post('doActive', [BaseController::class, 'doActive'])->name('doActive');

Route::post('search', [BaseController::class, 'search'])->name('search');

Route::group(['middleware' => ['auth', 'adminLevel']], function () {

    Route::get('config', [AdminController::class, 'config'])->name('config');

    Route::post('doConfig', [AdminController::class, 'doConfig'])->name('doConfig');

    Route::get('profile', [BaseController::class, 'profile'])->name('profile');

    Route::post('deleteOffer', [AdminController::class, 'deleteOffer'])->name('deleteOffer');

    Route::get("createOffCode", [AdminController::class, 'createOffCode'])->name('createOffCode');

    Route::post("generateOffCode", [AdminController::class, 'generateOffCode'])->name('generateOffCode');

    Route::get('offCodeReports', [AdminController::class, 'offCodeReports'])->name('offCodeReports');

    Route::get('unConfirmedOrders', [AdminController::class, 'unConfirmedOrders'])->name('unConfirmedOrders');

    Route::get('confirmedOrders', [AdminController::class, 'confirmedOrders'])->name('confirmedOrders');

    Route::get('rejectedOrders', [AdminController::class, 'rejectedOrders'])->name('rejectedOrders');

    Route::post('confirmOrder', [AdminController::class, 'confirmOrder'])->name('confirmOrder');

    Route::post('rejectOrder', [AdminController::class, 'rejectOrder'])->name('rejectOrder');

});

Route::group(['middleware' => ['auth', 'adminLevel']], function () {

    Route::get('manageCategory', [CategoryController::class, 'manageCategory'])->name('manageCategory');

    Route::post('saveSuperCategory', [CategoryController::class, 'saveSuperCategory'])->name('saveSuperCategory');

    Route::post('saveCategory', [CategoryController::class, 'saveCategory'])->name('saveCategory');

    Route::post('saveItem', [CategoryController::class, 'saveItem'])->name('saveItem');

    Route::post('removeCategory', [CategoryController::class, 'removeCategory'])->name('removeCategory');

    Route::get('commonQuestionsPanel', [AdminController::class, 'commonQuestionsPanel'])->name('commonQuestionsPanel');

    Route::post('addCommonQuestion', [AdminController::class, 'addCommonQuestion'])->name('addCommonQuestion');

    Route::post('deleteCommonQuestion', [AdminController::class, 'deleteCommonQuestion'])->name('deleteCommonQuestion');

    Route::get('faqCategories', [AdminController::class, 'faqCategories'])->name('faqCategories');

    Route::post('addFaqCategory', [AdminController::class, 'addFaqCategory'])->name('addFaqCategory');

    Route::post('deleteFaqCategory', [AdminController::class, 'deleteFaqCategory'])->name('deleteFaqCategory');

    Route::post('editFaqCategory', [AdminController::class, 'editFaqCategory'])->name('editFaqCategory');

});

Route::group(['middleware' => ['auth', 'adminLevel']], function () {

    Route::get('addProduct', [ProductController::class, 'addProduct'])->name('addProduct');
    
    Route::post('saveNewProduct', [ProductController::class, 'saveNewProduct'])->name('saveNewProduct');

    Route::post('getCategoryForProduct', [ProductController::class, 'getCategoryForProduct'])->name('getCategoryForProduct');

    Route::post('getItemForProduct', [ProductController::class, 'getItemForProduct'])->name('getItemForProduct');

    Route::post('saveExcelProduct', [ProductController::class, 'saveExcelProduct'])->name('saveExcelProduct');

    Route::get('editProduct/{id?}', [ProductController::class, 'editProduct'])->name('editProduct');

    Route::post('doEditProduct/{id}', [ProductController::class, 'doEditProduct'])->name('doEditProduct');

    Route::get('addBatchProduct', [ProductController::class, 'addBatchProduct'])->name('addBatchProduct');

    Route::post('doAddBatchProduct', [ProductController::class, 'doAddBatchProduct'])->name('doAddBatchProduct');

    Route::post('toggleShow', [ProductController::class, 'toggleShow'])->name('toggleShow');
});

Route::group(['middleware' => ['auth', 'adminLevel']], function () {

    Route::get('productReport', [ReportController::class, 'productReport'])->name('productReport');

    Route::get('most', [ReportController::class, 'most'])->name('most');

    Route::get('usersReport', [ReportController::class, 'users'])->name('usersReport');

    Route::get('sellReport/{id}/{year?}/{month?}', [ReportController::class, 'sellReport'])->name('sellReport');

    Route::get('userBookmarks/{uId}', [ReportController::class, 'userBookmarks'])->name('userBookmarks');

    Route::get('userBuys/{uId}', [ReportController::class, 'userBuys'])->name('userBuys');

    Route::post('toggleSpecial', [AdminController::class, 'toggleSpecial'])->name('toggleSpecial');

    Route::post('toggleStatusUser', [AdminController::class, 'toggleStatusUser'])->name('toggleStatusUser');

    Route::post('changeTakhfif', [ProductController::class, 'changeTakhfif'])->name('changeTakhfif');
});

Route::group(['middleware' => ['auth']], function () {

    Route::post('getItems', [BasketController::class, 'getItems'])->name('getItems');

    Route::post('checkOffCode', [BasketController::class, 'checkOffCode'])->name('checkOffCode');

    Route::get('bookmarks', [ProductController::class, 'bookmarks'])->name('bookmarks');

    Route::post('bookmark', [ProductController::class, 'bookmark'])->name('bookmark');

    Route::post('addToBasket', [BasketController::class, 'addToBasket'])->name('addToBasket');

    Route::post('removeFromBasket', [BasketController::class, 'removeFromBasket'])->name('removeFromBasket');

    Route::get('myBasket', [BasketController::class, 'myBasket'])->name('myBasket');

    Route::post('finishBuy', [BasketController::class, 'finishBuy'])->name('finishBuy');

    Route::post('addPic/{follow_code?}', [BasketController::class, 'addPic'])->name('addPic');

    Route::get('editInfo', [BaseController::class, 'editInfo'])->name('editInfo');

    Route::post('doEditInfo', [BaseController::class, 'doEditInfo'])->name('doEditInfo');

    Route::post('doActiveAgain', [BaseController::class, 'doActiveAgain'])->name('doActiveAgain');

    Route::post('resendActivationAgain', [BaseController::class, 'resendActivationAgain'])->name('resendActivationAgain');

    Route::get('trackOrders', [BasketController::class, 'trackOrders'])->name('trackOrders');

    Route::post('buyAgain', [BasketController::class, 'buyAgain'])->name('buyAgain');

    Route::get('success/{follow_code?}', [BasketController::class, 'success'])->name('success');

});

Route::get('manageSlideShow', [BaseController::class, 'manageSlideShow'])->name('manageSlideShow');

Route::post('saveSlideShow', [BaseController::class, 'saveSlideShow'])->name('saveSlideShow');