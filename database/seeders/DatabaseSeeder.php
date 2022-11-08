<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Event;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $role1 = Role::factory()->create([
            'id' => 1,
            'name' => 'admin'
        ]);
        $role2 = Role::factory()->create([
            'id' => 2,
            'name' => 'teacher'
        ]);
        $role3 = Role::factory()->create([
            'id' => 3,
            'name' => 'student'
        ]);

        $user1 = User::factory()->create([
            'name' => 'admin',
            'password' => '$2y$10$lnHMRaXDApRVMHLqgKlWb.p7ciXt0m0nZC7Go05N8e7hFt5dXoTP2',
            'email' => 'admin@admin.pl',
            'role_id' => $role2
        ]);
        $user2 = User::factory()->create([
            'name' => 'student1',
            'password' => '$2y$10$lnHMRaXDApRVMHLqgKlWb.p7ciXt0m0nZC7Go05N8e7hFt5dXoTP2',
            'email' => 'student1@student.pl',
            'role_id' => $role3
        ]);
        $user3 = User::factory()->create([
            'name' => 'student2',
            'password' => '$2y$10$lnHMRaXDApRVMHLqgKlWb.p7ciXt0m0nZC7Go05N8e7hFt5dXoTP2',
            'email' => 'student2@student.pl',
            'role_id' => $role3
        ]);

        Event::factory()->create([
            'start' => '2022-10-10 8:00',
            'end' => '2022-10-10 9:30',
            'class' => 'free',
            'teacher_id' => $user1,
        ]);
        Event::factory()->create([
            'start' => '2022-10-11 10:00',
            'end' => '2022-10-11 11:30',
            'class' => 'free',
            'teacher_id' => $user1,
        ]);
        Event::factory()->create([
            'start' => '2022-10-12 9:00',
            'end' => '2022-10-12 10:30',
            'class' => 'free',
            'teacher_id' => $user1,
        ]);

    }
}
