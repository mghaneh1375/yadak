<?php

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

use Illuminate\Support\Facades\Route;

Route::get('/', ['as' => 'root', 'uses' => 'BaseController@home']);

Route::get('home', ['as' => 'home', 'uses' => 'BaseController@home']);

Route::get('aboutUs', ['as' => 'aboutUs', 'uses' => 'BaseController@aboutUs']);

Route::get('contactUs', ['as' => 'contactUs', 'uses' => 'BaseController@contactUs']);

Route::get('product/{id?}', ['as' => 'product', 'uses' => 'ProductController@product']);

Route::get('productsInCategory/{id}', ['as' => 'productsInCategory', 'uses' => 'ProductController@productsInCategory']);

Route::get('products/{categoryId}', ['as' => 'products', 'uses' => 'ProductController@products']);

Route::post('doLogin', ['as' => 'doLogin', 'uses' => 'BaseController@doLogin']);

Route::get('faq', ['as' => 'faq', 'uses' => 'BaseController@faq']);

Route::get('login', ['as' => 'login', 'uses' => 'BaseController@login']);

Route::get('logout', ['as' => 'logout', 'uses' => 'BaseController@logout']);

Route::post('registry', ['as' => 'registry', 'uses' => 'BaseController@registry']);

Route::post('forgetPass', ['as' => 'forgetPass', 'uses' => 'BaseController@forgetPass']);

Route::post('resendActivation', ['as' => 'resendActivation', 'uses' => 'BaseController@resendActivation']);

Route::post('doActive', ['as' => 'doActive', 'uses' => 'BaseController@doActive']);

Route::post('search', ['as' => 'search', 'uses' => 'BaseController@search']);

Route::group(['middleware' => ['auth', 'adminLevel']], function () {

    Route::get('config', ['as' => 'config', 'uses' => 'AdminController@config']);

    Route::post('doConfig', ['as' => 'doConfig', 'uses' => 'AdminController@doConfig']);

    Route::get('profile', ['as' => 'profile', 'uses' => 'BaseController@profile']);

    Route::post('deleteOffer', ['as' => 'deleteOffer', 'uses' => 'AdminController@deleteOffer']);

    Route::get("createOffCode", array('as' => 'createOffCode', 'uses' => 'AdminController@createOffCode'));

    Route::post("generateOffCode", array('as' => 'generateOffCode', 'uses' => 'AdminController@generateOffCode'));

    Route::get('offCodeReports', ['as' => 'offCodeReports', 'uses' => 'AdminController@offCodeReports']);

    Route::get('unConfirmedOrders', ['as' => 'unConfirmedOrders', 'uses' => 'AdminController@unConfirmedOrders']);

    Route::get('confirmedOrders', ['as' => 'confirmedOrders', 'uses' => 'AdminController@confirmedOrders']);

    Route::get('rejectedOrders', ['as' => 'rejectedOrders', 'uses' => 'AdminController@rejectedOrders']);

    Route::post('confirmOrder', ['as' => 'confirmOrder', 'uses' => 'AdminController@confirmOrder']);

    Route::post('rejectOrder', ['as' => 'rejectOrder', 'uses' => 'AdminController@rejectOrder']);

});

Route::group(['middleware' => ['auth', 'adminLevel']], function () {

    Route::get('manageCategory', ['as' => 'manageCategory', 'uses' => 'CategoryController@manageCategory']);

    Route::post('saveSuperCategory', ['as' => 'saveSuperCategory', 'uses' => 'CategoryController@saveSuperCategory']);

    Route::post('saveCategory', ['as' => 'saveCategory', 'uses' => 'CategoryController@saveCategory']);

    Route::post('saveItem', ['as' => 'saveItem', 'uses' => 'CategoryController@saveItem']);

    Route::post('removeCategory', ['as' => 'removeCategory', 'uses' => 'CategoryController@removeCategory']);

    Route::get('commonQuestionsPanel', ['as' => 'commonQuestionsPanel', 'uses' => 'AdminController@commonQuestionsPanel']);

    Route::post('addCommonQuestion', ['as' => 'addCommonQuestion', 'uses' => 'AdminController@addCommonQuestion']);

    Route::post('deleteCommonQuestion', ['as' => 'deleteCommonQuestion', 'uses' => 'AdminController@deleteCommonQuestion']);

    Route::get('faqCategories', ['as' => 'faqCategories', 'uses' => 'AdminController@faqCategories']);

    Route::post('addFaqCategory', ['as' => 'addFaqCategory', 'uses' => 'AdminController@addFaqCategory']);

    Route::post('deleteFaqCategory', ['as' => 'deleteFaqCategory', 'uses' => 'AdminController@deleteFaqCategory']);

    Route::post('editFaqCategory', ['as' => 'editFaqCategory', 'uses' => 'AdminController@editFaqCategory']);

});

Route::group(['middleware' => ['auth', 'adminLevel']], function () {

    Route::get('addProduct', ['as' => 'addProduct', 'uses' => 'ProductController@addProduct']);
    
    Route::post('saveNewProduct', ['as' => 'saveNewProduct', 'uses' => 'ProductController@saveNewProduct']);

    Route::post('getCategoryForProduct', ['as' => 'getCategoryForProduct', 'uses' => 'ProductController@getCategoryForProduct']);

    Route::post('getItemForProduct', ['as' => 'getItemForProduct', 'uses' => 'ProductController@getItemForProduct']);

    Route::post('saveExcelProduct', ['as' => 'saveExcelProduct', 'uses' => 'ProductController@saveExcelProduct']);

    Route::get('editProduct/{id?}', ['as' => 'editProduct', 'uses' => 'ProductController@editProduct']);

    Route::post('doEditProduct/{id}', ['as' => 'doEditProduct', 'uses' => 'ProductController@doEditProduct']);

    Route::get('addBatchProduct', ['as' => 'addBatchProduct', 'uses' => 'ProductController@addBatchProduct']);

    Route::post('doAddBatchProduct', ['as' => 'doAddBatchProduct', 'uses' => 'ProductController@doAddBatchProduct']);

    Route::post('toggleShow', ['as' => 'toggleShow', 'uses' => 'ProductController@toggleShow']);
});

Route::group(['middleware' => ['auth', 'adminLevel']], function () {

    Route::get('productReport', ['as' => 'productReport', 'uses' => 'ReportController@productReport']);

    Route::get('most', ['as' => 'most', 'uses' => 'ReportController@most']);

    Route::get('usersReport', ['as' => 'usersReport', 'uses' => 'ReportController@users']);

    Route::get('sellReport/{id}/{year?}/{month?}', ['as' => 'sellReport', 'uses' => 'ReportController@sellReport']);

    Route::get('userBookmarks/{uId}', ['as' => 'userBookmarks', 'uses' => 'ReportController@userBookmarks']);

    Route::get('userBuys/{uId}', ['as' => 'userBuys', 'uses' => 'ReportController@userBuys']);

    Route::post('toggleSpecial', ['as' => 'toggleSpecial', 'uses' => 'AdminController@toggleSpecial']);

    Route::post('toggleStatusUser', ['as' => 'toggleStatusUser', 'uses' => 'AdminController@toggleStatusUser']);

    Route::post('changeTakhfif', ['as' => 'changeTakhfif', 'uses' => 'ProductController@changeTakhfif']);
});

Route::group(['middleware' => ['auth']], function () {

    Route::post('getItems', ['as' => 'getItems', 'uses' => 'BasketController@getItems']);

    Route::post('checkOffCode', ['as' => 'checkOffCode', 'uses' => 'BasketController@checkOffCode']);

    Route::get('bookmarks', ['as' => 'bookmarks', 'uses' => 'ProductController@bookmarks']);

    Route::post('bookmark', ['as' => 'bookmark', 'uses' => 'ProductController@bookmark']);

    Route::post('addToBasket', ['as' => 'addToBasket', 'uses' => 'BasketController@addToBasket']);

    Route::post('removeFromBasket', ['as' => 'removeFromBasket', 'uses' => 'BasketController@removeFromBasket']);

    Route::get('myBasket', ['as' => 'myBasket', 'uses' => 'BasketController@myBasket']);

    Route::post('finishBuy', ['as' => 'finishBuy', 'uses' => 'BasketController@finishBuy']);

    Route::post('addPic/{follow_code?}', ['as' => 'addPic', 'uses' => 'BasketController@addPic']);

    Route::get('editInfo', ['as' => 'editInfo', 'uses' => 'BaseController@editInfo']);

    Route::post('doEditInfo', ['as' => 'doEditInfo', 'uses' => 'BaseController@doEditInfo']);

    Route::post('doActiveAgain', ['as' => 'doActiveAgain', 'uses' => 'BaseController@doActiveAgain']);

    Route::post('resendActivationAgain', ['as' => 'resendActivationAgain', 'uses' => 'BaseController@resendActivationAgain']);

    Route::get('trackOrders', ['as' => 'trackOrders', 'uses' => 'BasketController@trackOrders']);

    Route::post('buyAgain', ['as' => 'buyAgain', 'uses' => 'BasketController@buyAgain']);

    Route::get('success/{follow_code?}', ['as' => 'success', 'uses' => 'BasketController@success']);

});

Route::get('manageSlideShow', ['as' => 'manageSlideShow', 'uses' => 'BaseController@manageSlideShow']);

Route::post('saveSlideShow', ['as' => 'saveSlideShow', 'uses' => 'BaseController@saveSlideShow']);