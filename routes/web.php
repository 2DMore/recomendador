<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/usuarios',function(){
    return view('admin.index');
});

Route::get('/agregar-usuarios',function(){
    return view('admin.create-user');
});

Route::get('/login',function(){
    return view('auth.login');
});

Route::get('/added',function(){
    return view('user.added');
});

Route::get('/listado',function(){
    return view('user.list');
});

Route::get('/estadisticas',function(){
    return view('user.statisticsView');
});
Route::get('/listar',function(){
    return view('user.validated');
});

Route::get('/newlist',function(){
    return view('validator.newListView');
});

Route::get('/vallist',function(){
    return view('validator.validationListView');
});

Route::get('/notfound',function(){
    return view('404');
});

