<?php

use Illuminate\Database\Seeder;
use App\Models\General\AdministrativeRegion;
use App\Models\General\Country;
use App\Models\General\LevelOne;
use App\Models\General\LevelTwo;
use App\Models\General\LevelThree;
use App\Models\General\LevelFour;

class RegionsSeeder extends Seeder
{
    //$countries = DB::table('countries')->whereIn('id', [25, 93, 101, 131, 159, 177, 186,])->get();
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Administrative Regions
        $rbotswana = new AdministrativeRegion;
        $rbotswana->country_id = 25;
        $rbotswana->level_one = 'District';
        $rbotswana->level_two = 'Sub-District';
        $rbotswana->save();

        $rkenya = new AdministrativeRegion;
        $rkenya->country_id = 93;
        $rkenya->level_one = 'County';
        $rkenya->level_two = 'Sub-County';
        $rkenya->level_three = 'Location';
        $rkenya->save();

        $rliberia = new AdministrativeRegion;
        $rliberia->country_id = 101;
        $rliberia->level_one = 'County';
        $rliberia->level_two = 'District';
        $rliberia->save();

        $rnigeria = new AdministrativeRegion;
        $rnigeria->country_id = 131;
        $rnigeria->level_one = 'State';
        $rnigeria->level_two = 'Local Government Area';
        $rnigeria->save();

        $rghana = new AdministrativeRegion;
        $rghana->country_id = 69;
        $rghana->level_one = 'Province';
        $rghana->level_two = 'District';
        $rghana->level_three = 'Chiefdom';
        $rghana->save();

        $rtanzania = new AdministrativeRegion;
        $rtanzania->country_id = 177;
        $rtanzania->level_one = 'Mkoa';
        $rtanzania->level_two = 'Wilaya';
        $rtanzania->level_three = 'Kata';
        $rtanzania->level_four = 'Kijiji/Mtaa';
        $rtanzania->save();

        $ruganda = new AdministrativeRegion;
        $ruganda->country_id = 186;
        $ruganda->level_one = 'District';
        $ruganda->level_two = 'County';
        $ruganda->level_three = 'Sub-County';
        $ruganda->save();

        $rivorycoast = new AdministrativeRegion;
        $rivorycoast->country_id = 88;
        $rivorycoast->level_one = 'District';
        $rivorycoast->level_two = 'Sub-District';
        $rivorycoast->save();

        $rmozambique = new AdministrativeRegion;
        $rmozambique->country_id = 124;
        $rmozambique->level_one = 'District';
        $rmozambique->level_two = 'Sub-District';
        $rmozambique->save();

        $rzambia = new AdministrativeRegion;
        $rzambia->country_id = 197;
        $rzambia->level_one = 'District';
        $rzambia->level_two = 'Sub-District';
        $rzambia->save();
    }
}
