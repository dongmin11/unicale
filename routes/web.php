<?php

use App\Http\Composer\HelloComposer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\memberController;
use App\Http\Controllers\categoryController;


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

Route::get('/', function () {
    return view('welcome');
});



route::get('showCalender',[CalenderController::class,'showCalender'])->name('showCal');

route::get('preCal',[CalenderController::class,'preCal'])->name('preCal');

route::get('nextCal',[CalenderController::class,'nextCal'])->name('nextCal');

route::post('showCalender',[CalenderController::class,'addSchedule'])->name('addSchedule');

route::post('showCalender',[CalenderController::class,'search'])->name('search');

route::get('scheduleDetail',[CalenderController::class,'scheduleDetail'])->name('scheduleDetail');

route::post('scheduleDetail',[CalenderController::class,'updateSchedule'])->name('updateSchedule');

route::get('schedule_day',[CalenderController::class,'schedule_day'])->name('schedule_day');


//カテゴリ系
route::get('category',[categoryController::class,'category'])->name('category');

route::post('addCategory',[categoryController::class,'addCategory'])->name('addCategory');

route::post('updateCategory',[categoryController::class,'updateCategory'])->name('updateCategory');


//メンバー系
route::get('member',[memberController::class,'member'])->name('member');

route::post('updateMember',[memberController::class,'updateMember'])->name('updateMember');

route::post('createMember',[memberController::class,'addMember'])->name('addMember');

route::post('updateMemberAppear',[memberController::class,'updateMemberAppear'])->withoutMiddleware(['web']);