<?php

namespace Database\Seeders;

use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = $this->createAdminAccount();
        $admin->assign('admin');
    }

    /**
     * Create the default admin user
     *
     * @return User
     */
    private function createAdminAccount()
    {
        $user = User::where('email', env('ADMIN_USER_EMAIL'))->first();
        if (is_null($user)) {
            return User::create([
                'first_name' => env('ADMIN_USER_NAME'),
                'last_name' => env('ADMIN_USER_NAME'),
                'email' => env('ADMIN_USER_EMAIL'),
                'email_verified_at' => now(),
                'password' => env('ADMIN_USER_PASSWORD')
            ]);
        }
        return $user;
    }
}
