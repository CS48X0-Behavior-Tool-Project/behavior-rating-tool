<?php

namespace Database\Seeders;

use Bouncer;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()
            ->create();

        $user->assign('admin');
    }
}
