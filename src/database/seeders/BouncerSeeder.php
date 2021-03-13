<?php

namespace Database\Seeders;

use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Video;
use App\Models\Quiz;

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
