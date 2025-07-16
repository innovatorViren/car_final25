<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Series;

class SeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seriesData = [
            
        ];

        foreach ($seriesData as $data) {
            $series = Series::where('type', $data['type'])->first();
            if (!$series) {
                $series = new Series();
                $series->type           = $data['type'];
                $series->prefix         = $data['prefix'];
                $series->next_number    = $data['next_number'];
                $series->suffix         = $data['suffix'];
                $series->save();
            }
        }
    }
}
