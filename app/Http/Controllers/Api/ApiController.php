<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;


use App\Models\Prod;
use App\Models\Cart;
use App\Models\ProdCart;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $array = ['error' => ''];

        $array['list'] = Cart::all();

        return $array;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $array = ['error' => ''];

        $carts_all = Cart::all();

        //caso a tabela Cart estiver vazia, criará um novo carrinho
        if(empty($carts_all->id)){

          $data = Cart::create([
                'id'        => 1,
                'situacion' => 'AB', //situação aberta, FD para fechada
                'value_tot' => 0,
                'user_id'   => 1
            ]);

        }

         //validar as informações
        $rules = [
            'prod_id'   => 'required',
            'quanty'    => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            $array['error'] = $validator->messages();
            return $array;
        }

        $prod_id    = $request->input('prod_id');
        $quanty     = $request->input('quanty');

        $prod = Prod::find($prod_id);

        $prodcart = new ProdCart();
        $prodcart->prod_id     = $prod_id;
        $prodcart->cart_id     = $data->id;
        $prodcart->quanty      = $quanty;
        $prodcart->value       = $prod->value * $quanty;
        $prodcart->save();

        return $array;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $array = ['error' => ''];

        //validar as informações
        $rules = [
            'quanty'    => 'min:1'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            $array['error'] = $validator->messages();
            return $array;
        }

        $quanty   = $request->input('quanty');


        $prodcart = ProdCart::where('prod_id', '=', "{$id}");
        // $prod = Prod::find($prodcart);

        if($prodcart){

            $prodcart->update($quanty);


        }else{
            $array['error'] = 'Produto'.$id.'não existe';
        }


                return $array;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $array = ['error' => ''];

        $prodcart = ProdCart::where('produto_id', '=', "{$id}");
        $prodcart->delete();

        $array['msg'] = 'Registros deletados com sucesso';

        return $array;
    }
}
