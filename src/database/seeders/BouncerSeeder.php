<?php

namespace Database\Seeders;

use Bouncer;
use Illuminate\Database\Seeder;
use App\Models\User;

class BouncerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bouncer::allow('admin')->to('delete-users');
        $adminUser = $this->createAdminAccount();

        $adminUser->assign('admin');
    }

    /**
     * Create the default admin user
     *
     * @return User
     */
    private function createAdminAccount()
    {
        return User::create([
            'name' => env('ADMIN_USER_NAME'),
            'first_name' => env('ADMIN_USER_NAME'),
            'last_name' => env('ADMIN_USER_NAME'),
            'email' => env('ADMIN_USER_EMAIL'),
            'email_verified_at' => now(),
            'password' => env('ADMIN_USER_PASSWORD')
        ]);
    }
}
