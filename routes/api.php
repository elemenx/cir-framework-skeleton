<?php

use Elemenx\CirFrameworkSkeleton\CirFrameworkSkeleton;
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

$guard = config('cir_framework_skeleton.guard.auth', null);
Route::middleware([$guard ? 'auth:' . $guard : 'auth'])->group(function () {
    Route::as('session')->get('session', 'SessionController@session');

    Route::resource('module', 'ModuleController');
    Route::resource('module.field', 'FieldController');
    Route::get('module/{module}/field_sequence_list', ['as' => 'module.field_sequence_list', 'uses' => 'ModuleController@fieldSequenceList']);
    Route::resource('resource', 'ResourceController');
    Route::resource('strategy', 'StrategyController');
    Route::resource('menu', 'MenuController');
    Route::middleware(CirFrameworkSkeleton::getAdminMiddlewares())->group(function () {
        Route::resource('setting_category', 'SettingCategoryController', ['except' => ['index']]);
        Route::post('setting_category/sequence', ['as' => 'setting_category.sequence', 'uses' => 'SettingCategoryController@sequence']);
        Route::resource('setting_item', 'SettingItemController', ['except' => ['index']]);
        Route::post('setting_item/sequence', ['as' => 'setting_item.sequence', 'uses' => 'SettingItemController@sequence']);
        Route::post('module/{module}/copy', ['as' => 'module.copy', 'uses' => 'ModuleController@copy']);
        Route::post('module/sequence', ['as' => 'module.sequence', 'uses' => 'ModuleController@sequence']);
        Route::post('module/{module}/field/sequence', ['as' => 'module.field.sequence', 'uses' => 'FieldController@sequence']);
        Route::post('menu/sequence', ['as' => 'menu.sequence', 'uses' => 'MenuController@sequence']);
        Route::get('resource/{resource}/field', ['as' => 'resource.field', 'uses' => 'ResourceController@fieldList']);
        Route::get('resource/{resource}/data', ['as' => 'resource.data', 'uses' => 'ResourceController@data']);
        Route::get('setting', 'SettingController@show');
        Route::post('setting', 'SettingController@setSetting');
    });
});
