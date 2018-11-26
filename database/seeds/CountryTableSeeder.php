<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Rinvex\Country\CountryLoader;
use Smartville\Domain\Countries\Models\Country;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->truncate();

        $countries = CountryLoader::countries();

        collect($countries)->each(function ($defCountry, $code) {
            $country = \country($code);

            Country::create([
                'code' => $country->getIsoAlpha2(),
                'name' => $country->getName(),
                'dial_code' => !isset($defCountry['calling_code']) ? null : $defCountry['calling_code'],
            ]);
        });
    }
}
