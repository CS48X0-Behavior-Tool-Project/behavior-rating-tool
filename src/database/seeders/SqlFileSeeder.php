<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SqlFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // run this seeder manually using this command:  sudo docker-compose exec brt php artisan db:seed --class=SqlFileSeeder
        $path = public_path('sql/data.sql');
        $sql = file_get_contents($path);
        \DB::unprepared($sql);
    }
}
