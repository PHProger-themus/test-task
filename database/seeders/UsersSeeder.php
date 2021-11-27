<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Владимир',
                'email' => 'vlad@gmail.com',
                'password' => Hash::make('admin'),
                'role' => 1
            ],
            [
                'name' => 'Дмитрий',
                'email' => 'dmitro@gmail.com',
                'password' => Hash::make('manager'),
                'role' => 2
            ],
            [
                'name' => 'Алексей',
                'email' => 'alex@mail.ru',
                'password' => Hash::make('user'),
                'role' => 0
            ],
        ];

        DB::table('users')->insert($data);
    }
}
