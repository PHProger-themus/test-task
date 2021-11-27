<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Point;
use App\Models\Scooter;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class); // Predefined users
        User::factory(9)->create(); // Another users
        Point::factory(10)->create();
        Scooter::factory(10)->create();
        Order::factory(15)->create();
    }
}
