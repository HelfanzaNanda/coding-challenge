<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin', 'user'];
		foreach ($roles as $key => $role) {
			User::create([
				'name' => $role,
				'email' => $role .'@gmail.com',
				'password' => Hash::make('password'),
				'role' => $role
			]);
		}
    }
}
