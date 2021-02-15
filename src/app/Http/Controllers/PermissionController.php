<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{   
	// create system level data for:  Role, User, Permissions 
    public function Permission()
    {   
    	$admin_permission = Permission::where('slug','create-account')->first();
		$expert_permission = Permission::where('slug', 'create-quiz')->first();

		//RoleTableSeeder.php
		$admin_role = new Role();
		$admin_role->slug = 'admin';
		$admin_role->name = 'Administrator';
		$admin_role->save();
		$admin_role->permissions()->attach($admin_permission);

		$expert_role = new Role();
		$expert_role->slug = 'expert';
		$expert_role->name = 'Expert';
		$expert_role->save();
		$expert_role->permissions()->attach($expert_permission);

		$admin_role = Role::where('slug','admin')->first();
		$expert_role = Role::where('slug', 'expert')->first();

		$createAccount = new Permission();
		$createAccount->slug = 'create-account';
		$createAccount->name = 'Create Accounts';
		$createAccount->save();
		$createAccount->roles()->attach($admin_role);

		$createQuiz = new Permission();
		$createQuiz->slug = 'create-quiz';
		$createQuiz->name = 'Create Quizzes';
		$createQuiz->save();
		$createQuiz->roles()->attach($expert_role);

		$admin_role = Role::where('slug','admin')->first();
		$expert_role = Role::where('slug', 'expert')->first();
		$admin_perm = Permission::where('slug','create-account')->first();
		$expert_perm = Permission::where('slug','create-quiz')->first();

		$admin = new User();
		$admin->name = 'Admin';
		$admin->first_name = 'AdminFirstName';
		$admin->last_name = 'AdminLastName';
		$admin->email = 'admin@gmail.com';
		$admin->password = bcrypt('password');
		$admin->save();
		$admin->roles()->attach($admin_role);
		$admin->permissions()->attach($admin_perm);

		$expert = new User();
		$expert->name = 'expert1001';
		$expert->first_name = 'Meexpert';
		$expert->last_name = 'MeLN';
		$expert->email = 'expert1001@gmail.com';
		$expert->password = bcrypt('password');
		$expert->save();
		$expert->roles()->attach($expert_role);
		$expert->permissions()->attach($expert_perm);

		return redirect()->back();
    }
}

