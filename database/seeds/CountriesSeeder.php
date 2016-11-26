<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;
use UHacWeb\Models\Country;

class CountriesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reader = Reader::createFromPath(__DIR__ . '/../data/countries.csv');
        $results = $reader->fetchAssoc(0);
        foreach ($results as $row) {
            Country::create([
                'id'        => $row['id'],
                'name'      => $row['name'],
                'code'      => $row['code'],
                'latitude'  => ( ! empty($row['latitude'])) ? $row['latitude'] : null,
                'longitude' => ( ! empty($row['longitude'])) ? $row['longitude'] : null,
            ]);
        }
    }

}
