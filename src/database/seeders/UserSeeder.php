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

        // This generates a student user with student roles
        // Do not change this specific role
        $user->assign('student');
    }
}
