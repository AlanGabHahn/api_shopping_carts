<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_carts', function (Blueprint $table) {
            $table->id();

            //id do produto chave estrangeira com a tabela prod
            $table->bigInteger('prod_id')->unsigned();
            $table->foreign('prod_id')->references('id')->on('prods');
            //id do carrinho chave estrangeira com a tabela cart
            $table->biginteger('cart_id')->unsigned();
            $table->foreign('cart_id')->references('id')->on('carts');
            //valor do produto
            $table->decimal('value', 12, 2)->nullable();
            //quantidade do produto
            $table->string('quanty', 20)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prodcart');
    }
};
