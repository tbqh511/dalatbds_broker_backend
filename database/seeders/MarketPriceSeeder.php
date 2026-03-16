<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MarketPrice;

class MarketPriceSeeder extends Seeder
{
    public function run(): void
    {
        $month = 3;
        $year = 2026;

        $data = [
            ['area_name' => 'P. Lâm Viên',   'avg_price_m2' => 42_100_000, 'prev_price_m2' => 41_350_000],
            ['area_name' => 'Đường 3/4',       'avg_price_m2' => 35_700_000, 'prev_price_m2' => 35_880_000],
            ['area_name' => 'P. Cam Ly',       'avg_price_m2' => 28_500_000, 'prev_price_m2' => 27_860_000],
            ['area_name' => 'Phường 1',        'avg_price_m2' => 52_000_000, 'prev_price_m2' => 51_200_000],
            ['area_name' => 'P. Xuân An',      'avg_price_m2' => 18_200_000, 'prev_price_m2' => 17_900_000],
        ];

        foreach ($data as $row) {
            MarketPrice::updateOrCreate(
                ['area_name' => $row['area_name'], 'month' => $month, 'year' => $year],
                array_merge($row, ['month' => $month, 'year' => $year])
            );
        }
    }
}
