<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
        ]);

        for ($i=0; $i<1000; $i++){
            $test = new CustomerFactory(1000);
            $data = $test->make();
            $chunk = $data->chunk(500);
            foreach ($chunk as $item){
                Customer::insert($item->toArray());
            }
        }
    }
}
