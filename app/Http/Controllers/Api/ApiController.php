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

        $array['list'] = $data = ['data' => Cart::all()];

        return response()->json($array);
    }

    public function createCart(request $Request){

            $array = ['error' => ''];

            $carts_all = Cart::all();

            foreach($carts_all as $carts){

                if($carts->status == 'aberto'){

                    $array['error'] = 'Para criar um novo carrinho, é necessário finalizar o anterior';

                    return response()->json($array);
                }
            }

            $data = Cart::create([
                'id'        => 1,
                'status' => 'aberto', //situação aberta, FD para fechada
                'value_tot' => 0,
                'user_id'   => 1
            ]);
            $array['list'] = 'Carrinho'.$data.'criado com sucesso!';

            return response()->json($array);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $array = ['error' => ''];

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

        //criar na tabela
        $carts_all = Cart::all();

        foreach($carts_all as $carts){
            $cart_where = Cart::where('status', 'aberto')->orderby('id', 'desc')->first();
            if($cart_where){
                $data = Cart::find($carts->id);
            }else{
                $array['error'] = 'É necessário abrir um carrinho para continuar';
                return $array;
            }
        }

        $prod = Prod::find($prod_id);

        $prodcart = new ProdCart();
        $prodcart->prod_id     = $prod_id;
        $prodcart->cart_id     = $data->id;
        $prodcart->quanty      = $quanty;
        $prodcart->name_prod   = $prod->name;
        $prodcart->value       = $prod->value * $quanty;
        $prodcart->save();

        return response()->json($prodcart);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $array = ['error' => ''];

        $data = Cart::find($id);

        //validação para ver se existe o id passado
        if($data){

            $prods = ProdCart::all();
            $prodcart = 0;
            foreach($prods as $prod){
                if($prod->cart_id == $id){
                    $prodcart += $prod['value'];
                }
            }

            if($data->status == 'aberto'){


                $data->value_tot    = $prodcart;
                $data->save();

                $data_prod = ProdCart::where('cart_id', "{$id}")
                    ->get();

                $array['list'] = [
                    'Carrinho: '.$data,
                    'Produtos: '.$data_prod
                ];

            }else{
                $data_prod = ProdCart::where('cart_id', "{$id}")
                    ->get();

                $array['list'] = [
                    'Carrinho: '.$data,
                    'Produtos: '.$data_prod
                ];
            }

        }else{
            $array['error'] = 'Carrinho não existe ou não encontrado';
        }

        return response()->json($array);
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

        //aplicar a atualização do produto
        $carts_all = Cart::all();

        //validação para só permitir alterar o item com o carrinho aberto
        foreach($carts_all as $carts){
            $cart_where = Cart::where('status', 'aberto')->orderby('id', 'desc')->first();
            if($cart_where){
                $data = Cart::find($carts->id);
            }else{
                $array['error'] = 'Carrinho fechado, não é permitido a alteração do iten';
                return $array;
            }
        }

        $data = ProdCart::find($id);
        $prod = Prod::find($data->prod_id);

        if($data){

            $data->quanty   = $quanty;
            $data->value    = $prod->value * $quanty;
            $data->save();

            $array['list'] = $data.'atualizados com sucesso!';
        }else{
            $array['error'] = 'Item'.$id.'não encontrado';
        }



               return response()->json($data);
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

        $carts_all = Cart::all();

        //validação para só permitir deletar o item com o carrinho aberto
        foreach($carts_all as $carts){
            $cart_where = Cart::where('status', 'aberto')->orderby('id', 'desc')->first();
            if($cart_where){
                $data = Cart::find($carts->id);
            }else{
                $array['error'] = 'Carrinho fechado, não é permitido deletar o item';
                return $array;
            }
        }
        $prodcart = ProdCart::where('prod_id', '=', "{$id}")->delete();

        $array['msg'] = 'Registros deletados com sucesso';

        return response()->json($array);
    }
    public function finalizar(Request $request, $id){
        $array = ['error' => ''];

        $status = 'fechado'; //status fechado

        $data = Cart::find($id);

        //validação para ver se existe o id passado
        if($data){

            //código para buscar o valor total
            $prods = ProdCart::all();
            $prodcart = 0;
            foreach($prods as $prod){
                if($prod->cart_id == $id){
                    $prodcart += $prod['value'];
                }

            }
            //verifica se o status do carrinho está em aberto, caso contrario exibe a mensagem
            if($data->status == 'aberto'){


                $data->status       = $status;
                $data->value_tot    = $prodcart;
                $data->save();

                $data_prod = ProdCart::where('cart_id', "{$id}")
                    ->get();

                $array['list'] = [
                    'Carrinho: '.$data,
                    'Produtos: '.$data_prod
                ];

            }else{
                $array['error'] = 'O Carrinho '.$data->id.' já está finalizado';
            }

        }else{
            $array['error'] = 'Carrinho não existe ou não encontrado';
        }

        return response()->json($array);
    }
}
