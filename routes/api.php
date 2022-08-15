<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiController;

//POST /cart/create = criar o carrinho de compras
Route::post('/cart/create', [ApiController::class, 'createCart']);
//POST /cart/prod = inserir um produto no carrinho de compras
Route::post('/cart/prod', [ApiController::class, 'create']);
//GET /carts = listar todos os carrinhos de compras
Route::get('/carts', [ApiController::class, 'index']);
//GET /cart/{id} = listar somente 1 carrinho de compras
Route::get('/cart/{id}', [ApiController::class, 'show']);
//PUT /cart/{id} = atualizar o carrinho de compras
Route::put('/cart/{id}', [ApiController::class, 'update']);
//DELETE /cart/delete = deletar um item do carrinho de compras
Route::delete('/cart/delete/{id}', [ApiController::class, 'destroy']);
//PUT /cart/finalizar/{id} = finalizar o pedido do carrinho
Route::put('/cart/finalizar/{id}', [ApiController::class, 'finalizar']);

