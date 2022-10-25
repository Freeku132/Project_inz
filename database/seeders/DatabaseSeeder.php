<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Event;
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
        // \App\Models\User::factory(10)->create();

        Event::factory()->create([
            'start' => '2022-10-10 8:00',
            'end' => '2022-10-10 9:30',
            'class' => 'free'
        ]);
        Event::factory()->create([
            'start' => '2022-10-11 10:00',
            'end' => '2022-10-11 11:30',
            'class' => 'free'
        ]);
        Event::factory()->create([
            'start' => '2022-10-12 9:00',
            'end' => '2022-10-12 10:30',
            'class' => 'free'
        ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
