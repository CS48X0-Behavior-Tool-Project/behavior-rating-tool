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

        $this->createRolePermission();
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
                'name' => env('ADMIN_USER_NAME'),
                'first_name' => env('ADMIN_USER_NAME'),
                'last_name' => env('ADMIN_USER_NAME'),
                'email' => env('ADMIN_USER_EMAIL'),
                'email_verified_at' => now(),
                'password' => env('ADMIN_USER_PASSWORD')
            ]);
        }
        return $user;
    }

    private function createRolePermission() {

        Bouncer::allow('student')->to('view-profile');
        Bouncer::allow('student')->to('edit-profile');
        Bouncer::allow('student')->to('review-my-quizzes');  
        Bouncer::allow('student')->to('conduct-quizzes');

        Bouncer::allow('expert')->to('view-profile');
        Bouncer::allow('expert')->to('edit-profile');
        Bouncer::allow('expert')->to('create-quizzes');
        Bouncer::allow('expert')->to('update-quizzes');
        Bouncer::allow('expert')->to('review-my-quizzes');  
        Bouncer::allow('expert')->to('conduct-quizzes');

        Bouncer::allow('TA')->to('view-users-page');
        Bouncer::allow('TA')->to('create-users');
        Bouncer::allow('TA')->to('edit-users');
        Bouncer::allow('TA')->to('import-users');
        Bouncer::allow('TA')->to('export-users');
        Bouncer::allow('TA')->to('view-profile');
        Bouncer::allow('TA')->to('edit-profile');
        Bouncer::allow('TA')->to('create-quizzes');
        Bouncer::allow('TA')->to('update-quizzes');
        Bouncer::allow('TA')->to('review-my-quizzes');      // review page by a student
        Bouncer::allow('TA')->to('review-quizzes');         // review for all students
        Bouncer::allow('TA')->to('export-student-quizzes');
        Bouncer::allow('TA')->to('conduct-quizzes');

        Bouncer::allow('admin')->to('view-users-page');
        Bouncer::allow('admin')->to('create-users');
        Bouncer::allow('admin')->to('edit-users');
        Bouncer::allow('admin')->to('delete-users');
        Bouncer::allow('admin')->to('import-users');
        Bouncer::allow('admin')->to('export-users');
        Bouncer::allow('admin')->to('view-profile');
        Bouncer::allow('admin')->to('edit-profile');
        Bouncer::allow('admin')->to('create-quizzes');
        Bouncer::allow('admin')->to('update-quizzes');
        Bouncer::allow('admin')->to('delete-quizzes');
        Bouncer::allow('admin')->to('review-my-quizzes');
        Bouncer::allow('admin')->to('review-quizzes');
        Bouncer::allow('admin')->to('export-student-quizzes');
        Bouncer::allow('admin')->to('conduct-quizzes');
    
    }
}
