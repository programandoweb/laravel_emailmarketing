<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super = User::create([
            'email' => 'apirest@pagoslocales.com',
            'name'  => 'Jorge',
            'surnames'  => 'Méndez',
            'avatar'=>'uploads/seeder/avatar.jpg',
            'password' => \Hash::make('password'),
        ]);
    }
}
