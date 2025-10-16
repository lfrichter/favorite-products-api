<?php

namespace Database\Seeders;

use App\Models\FavoriteProduct;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        FavoriteProduct::create([
            'user_id' => $user->id,
            'product_id' => 1,
        ]);
    }
}