<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AppLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages  = [
            [
                'code' => 'en',
                'name' => 'English',
                'created_by' => 1,  
                'updated_by' => 1,  
                'deleted_by' => 1,
            ],
            [
                'code' => 'se',
                'name' => 'Swidish',
                'created_by' => 1,  
                'updated_by' => 1,  
                'deleted_by' => 1,
            ],
            [
                'code' => 'np',
                'name' => 'Nepali',
                'created_by' => 1,  
                'updated_by' => 1,  
                'deleted_by' => 1,
            ]
        ];
        foreach($languages as $language){
            DB::table('app_languages')->insert([
                'code' => $language['code'],
                'name' => $language['name'],
                'created_by' => $language['created_by'],
                'updated_by' => $language['updated_by'],
                'deleted_by' => $language['deleted_by'],
            ]);
        }
    }
}
