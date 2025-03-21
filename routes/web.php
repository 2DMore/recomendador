<?php


use App\Http\Controllers\UserController;

use App\Http\Controllers\dspaceController;

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

/*Login*/
Route::post('/userlogin', [UserController::class, 'login']);

Route::post('/register', [UserController::class, 'register']);

Route::post('/logout', [UserController::class, 'logout']);

Route::get('/getUsers', [UserController::class, 'getAllUsers']);


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/usuarios',function(){
    return view('admin.index');
});

Route::get('/agregar-usuarios',function(){
    return view('admin.create-user');
});

Route::get('/upload',[dspaceController::class, 'obtenerDocumentos']);

Route::get('/login',function(){
    return view('auth.login');
});

Route::get('/listar',function(){
    return view('user.added');
});

Route::get('/nuevos',function(){
    return view('user.list');
});

Route::get('/estadisticas',function(){
    return view('user.statisticsView');
});
Route::get('/validados',function(){
    return view('user.validated');
});

Route::get('/newlist',function(){
    return view('validator.newListView');
});

Route::get('/capturados',function(){
    return view('validator.validationListView');
});

Route::get('/notfound',function(){
    return view('404');
});


//Documentos
Route::post('/nuevos/store', [UserController::class,'guardarDoc']);
Route::post('/nuevos/submit', [UserController::class,'subirMetadatos']);
//Metadatos
Route::post('/upload/metadatos', [dspaceController::class,'subirMetadatosDspace']);

