<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleUser=Role::firstOrCreate(['name'=>'user']);
        $roleAgent=Role::firstOrCreate(['name'=>'agent']);
        $roleAdmin=Role::firstOrCreate(['name'=>'admin']);

        $agent = \App\Models\User::firstOrCreate(
            ['email' => 'agent@clearit.com'],
            [
                'name' => 'Agent User',
                'password' => bcrypt('123456'),
            ]
        );
        $agent->assignRole($roleAgent);

        $user = \App\Models\User::firstOrCreate(
            ['email' => 'user@clearit.com'],
            [
                'name' => 'Normal User',
                'password' => bcrypt('123456'),
            ]
        );
        $user->assignRole($roleUser);
        
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@clearit.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('123456'),
            ]
        );
        $admin->assignRole($roleAdmin);

        // Crear tickets usando los usuarios existentes
        Ticket::factory()->count(3)->create(['created_by' => $user->id, 'assigned_agent_id' => null]);
        Ticket::factory()->count(2)->create(['created_by' => $user->id, 'assigned_agent_id' => $agent->id, 'status' => 'in_progress']);
    }
}
