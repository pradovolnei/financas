<?php

use Illuminate\Support\Facades\Route;

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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get( '/', 'FinancaController@consultation' );

Route::post( 'novo', 'FinancaController@novoCadastro' );
Route::post( 'edit', 'FinancaController@edit' );

Route::get( 'delete', 'FinancaController@excluir' );
Route::get( 'deleteall', 'FinancaController@excluirTodos' );