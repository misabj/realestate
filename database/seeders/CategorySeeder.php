<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::firstOrCreate(
            ['slug' => 'rent'],
            ['name' => 'Rent', 'type' => 'rent', 'description' => null]
        );

        Category::firstOrCreate(
            ['slug' => 'buy'],
            ['name' => 'buy', 'type' => 'buy', 'description' => null]
        );
    }
}