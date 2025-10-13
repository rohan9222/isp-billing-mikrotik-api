<?php

namespace Database\Seeders;

use App\Models\RouterList;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

use App\Models\SiteSetting;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {       
        SiteSetting::create([
            'site_name' => 'FCNET24',
            'site_title' => 'Friends Communication Network Limited',
        ]);
        // User::factory(10)->withPersonalTeam()->create();
        RouterList::create([
            'router_name' => 'Test Router',
            'ip_address' => '157.119.186.254',
            'username' => 'saiful',
            'password' => 'fc223',
            'action' => 'connected',
            'ssh_port' => '8076',
        ]);
        User::factory()->withPersonalTeam()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            SuperAdminSeeder::class,
            // DefaultSettingsTableSeeder::class,
            // ProductSeeder::class,
        ]);
    }
}