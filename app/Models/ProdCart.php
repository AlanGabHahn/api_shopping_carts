<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_prod',
        'id_cart',
        'name_prod',
        'quanty',
        'value'
    ];
}
