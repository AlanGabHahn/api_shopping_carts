<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Prod;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateProdSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Prod::create([
            'name'      => 'teclado',
            'quanty'    => '10',
            'value'     => '5'
        ]);
        Prod::create([
            'name'      => 'fone',
            'quanty'    => '15',
            'value'     => '13'
        ]);
        Prod::create([
            'name'      => 'placa',
            'quanty'    => '51',
            'value'     => '23'
        ]);
        Prod::create([
            'name'      => 'microfone',
            'quanty'    => '25',
            'value'     => '13'
        ]);
        Prod::create([
            'name'      => 'monitor',
            'quanty'    => '7',
            'value'     => '17'
        ]);
        User::create([
            'name'      => 'Alan',
            'email'     => 'alan@email.com',
            'password'  => 'admin123'
        ]);
        User::create([
            'name'      => 'Gabriel',
            'email'     => 'gabriel@email.com',
            'password'  => 'admin123'
        ]);
        User::create([
            'name'      => 'Peeter',
            'email'     => 'peeter@email.com',
            'password'  => 'admin123'
        ]);
    }
}
