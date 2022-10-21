<?php

use Illuminate\Database\Seeder;
use App\User;

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
            'lastname' => 'Admin',
            'email' => 'testadmin@linkdigital.co.uk',
            'password' => bcrypt('test'),
            'created_at' => now(),
            'updated_at' => now(),
            'company_id' => 1,
            'role' => 'admin',
            'phone' => '07423080324',
            'is_deleted' => null,
            'email_verified_at' => now(),
        ]);

        User::create([
            'firstname' => 'Link',
            'lastname' => 'Broker',
            'email' => 'testbroker@linkdigital.co.uk',
            'password' => bcrypt('test'),
            'created_at' => now(),
            'updated_at' => now(),
            'company_id' => 1,
            'role' => 'broker',
            'phone' => '07423080324',
            'is_deleted' => null,
            'email_verified_at' => now(),
        ]);

        User::create([
            'firstname' => 'Link',
            'lastname' => 'Dealer',
            'email' => 'testdealer@linkdigital.co.uk',
            'password' => bcrypt('test'),
            'created_at' => now(),
            'updated_at' => now(),
            'company_id' => 1,
            'role' => 'dealer',
            'phone' => '07423080324',
            'is_deleted' => null,
            'email_verified_at' => now(),
        ]);
    }
}
