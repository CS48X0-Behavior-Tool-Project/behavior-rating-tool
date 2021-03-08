<?php

namespace Database\Seeders;

use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Video;

class BouncerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bouncer::allow('admin')->to(['delete', 'edit', 'create', 'view'], [User::class, Video::class, Quiz::class]);
        Bouncer::allow('admin')->to('export-users');
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
