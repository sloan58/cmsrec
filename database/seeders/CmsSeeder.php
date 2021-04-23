<?php

namespace Database\Seeders;

use Exception;
use App\Models\Cms;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $jsonSeeds = json_decode(file_get_contents(base_path('/privateSeeder.json')));
            Cms::create([
                'name' => $jsonSeeds->cms->name,
                'host' => $jsonSeeds->cms->host,
                'username' => $jsonSeeds->cms->username,
                'password' => $jsonSeeds->cms->password
            ]);
        } catch(Exception $e) {
            Cms::create([
                'name' => 'DevCMS1',
                'host' => '10.10.10.10',
                'username' => 'admin',
                'password' => 'password'
            ]);
        }
    }
}
