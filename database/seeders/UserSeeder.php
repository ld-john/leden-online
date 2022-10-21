<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'firstname' => 'Link',
            'lastname' => 'Testadmin',
            'email' => 'testadmin@linkdigital.co.uk',
            'password' => bcrypt('test'),
            'created_at' => now(),
            'updated_at' => now(),
            'role' => 'admin',
            'phone' => '1234567890',
            'company_id' => 1,
            'is_deleted' => null,
            'email_verified_at' => now(),
        ]);

        User::create([
            'firstname' => 'Link',
            'lastname' => 'Testdealer',
            'email' => 'testdealer@linkdigital.co.uk',
            'password' => bcrypt('test'),
            'created_at' => now(),
            'updated_at' => now(),
            'role' => 'dealer',
            'phone' => '1234567890',
            'company_id' => 1,
            'is_deleted' => null,
            'email_verified_at' => now(),
        ]);

        User::create([
            'firstname' => 'Link',
            'lastname' => 'Testbroker',
            'email' => 'testbroker@linkdigital.co.uk',
            'password' => bcrypt('test'),
            'created_at' => now(),
            'updated_at' => now(),
            'role' => 'broker',
            'phone' => '1234567890',
            'company_id' => 1,
            'is_deleted' => null,
            'email_verified_at' => now(),
        ]);
    }
}
