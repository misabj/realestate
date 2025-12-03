<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'key'   => 'bottom1',
                'title' => 'Featured: Porto Montenegro Exclusive Offers',
                'link'  => null,
            ],
            [
                'key'   => 'bottom2',
                'title' => 'New Listings: Seafront Apartments',
                'link'  => null,
            ],
            [
                'key'   => 'bottom3',
                'title' => 'Subscribe for Luxury Offers & Updates',
                'link'  => null,
            ],
        ];

        foreach ($data as $item) {
            Banner::firstOrCreate(
                ['key' => $item['key']],
                $item
            );
        }
    }
}