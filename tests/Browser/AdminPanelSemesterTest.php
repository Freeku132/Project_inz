<?php

namespace Tests\Browser;

use App\Models\FreeDay;
use App\Models\Semester;
use App\Models\User;
use App\Models\WeekDesignation;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminPanelSemesterTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp() :void
    {
        $this->appUrl = env('APP_URL');
        parent::setUp();
        $this->artisan('db:seed');
    }

    /** @test */
    public function admin_can_set_new_semester()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'admin',
            'email'=> 'admin@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 1
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/adminPanel/semester')
                ->waitFor('div')
                ->click('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(1) > button')
                ->waitFor('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(2) > div > div')
                ->type('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(2) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-4.p-4 > input:nth-child(2)', '2022/2023')
                ->type('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(2) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-4.p-4 > input:nth-child(4)', '01.10.2022')
                ->type('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(2) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-4.p-4 > input:nth-child(6)', '20.02.2023')
                ->click('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(2) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-4.p-4 > button')
                ->click('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(2) > div > div > div.text-right > button')
                ->pause(1000)
                ->assertSee('Week: 40 - 2022-09-26')
                ->assertSee('Week: 8 - 2023-02-20');
        });
        $this->assertDatabaseHas('semesters', ['name'=>'2022/2023', 'start_date' =>'2022-10-01' , 'end_date' => '2023-02-20', 'active' => '1'])
        ->assertDatabaseHas('week_designations', ['week_number'=>'40', 'start_date'=>'2022-09-26', 'designation' => 'Null']);
    }

    /** @test */
    public function admin_can_see_errors_message_on_new_semester_form()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'admin',
            'email'=> 'admin@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 1
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/adminPanel/semester')
                ->waitFor('div')
                ->click('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(1) > button')
                ->waitFor('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(2) > div > div')
                ->click('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(2) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-4.p-4 > button')
                ->pause(1000)
                ->assertSee('The years field is required.')
                ->assertSee('The start date field is required.')
                ->assertSee('The end date field is required.');
        });
    }

    /** @test */
    public function admin_can_set_week_designations()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'admin',
            'email'=> 'admin@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 1
        ]);

        $oldSemester = Semester::query()->where('active' , '1');
        $oldSemester->update([
            'active' => '0'
        ]);

        $semester = Semester::create([
            'id' => 5,
            'name' => '2022/2023_test',
            'start_date' => '2022-10-01',
            'end_date' => '2023-02-20',
            'active' => '1'
        ]);

        WeekDesignation::create([ 'week_number' => '40', 'designation' => 'Null', 'start_date' => '2022-09-26', 'semester_id' => $semester->id]);

        WeekDesignation::create(['week_number' => '41', 'designation' => 'Null', 'start_date' => '2022-10-10', 'semester_id' => $semester->id]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/adminPanel/semester')
                ->waitFor('div')
                ->select('#app > div > div.border-l-2.w-full.border-default > div > div > div.grid.grid-cols-1.md\:grid-cols-3.bg-page.rounded-xl.m-4.p-4 > div:nth-child(1) > div > select','B')
                ->select('#app > div > div.border-l-2.w-full.border-default > div > div > div.grid.grid-cols-1.md\:grid-cols-3.bg-page.rounded-xl.m-4.p-4 > div:nth-child(2) > div > select', 'A')
                ->pause(1000)
                ->assertSeeIn('#app > div > div.border-l-2.w-full.border-default > div > div > div.grid.grid-cols-1.md\:grid-cols-3.bg-page.rounded-xl.m-4.p-4 > div:nth-child(1) > div > select','B')
                ->assertSeeIn('#app > div > div.border-l-2.w-full.border-default > div > div > div.grid.grid-cols-1.md\:grid-cols-3.bg-page.rounded-xl.m-4.p-4 > div:nth-child(2) > div > select', 'A');
        });

        $this->assertDatabaseHas('week_designations', ['week_number' => '40', 'designation' => 'B'])
            ->assertDatabaseHas('week_designations', ['week_number' => '41', 'designation' => 'A']);
    }

    /** @test */
    public function admin_can_add_free_days()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'admin',
            'email'=> 'admin@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 1
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/adminPanel/semester')
                ->waitFor('div')
                ->type('#app > div > div.border-l-2.w-full.border-default > div > div > div.flex.flex-col.bg-page.rounded-xl.m-4.p-4.space-y-1 > div > input', '01-01-2023')
                ->click('#app > div > div.border-l-2.w-full.border-default > div > div > div.flex.flex-col.bg-page.rounded-xl.m-4.p-4.space-y-1 > div > button')
                ->pause(1000);
        });

        $this->assertDatabaseHas('free_days', ['name' => 'free', 'date' => '2023-01-01']);
    }

    /** @test */
    public function admin_can_delete_free_days()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'admin',
            'email'=> 'admin@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 1
        ]);

        FreeDay::create([
            'name' => 'free',
            'semester_id' => '1',
            'date' => '2023-01-01'
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/adminPanel/semester')
                ->waitFor('div')
                ->click('#app > div > div.border-l-2.w-full.border-default > div > div > div.flex.flex-col.bg-page.rounded-xl.m-4.p-4.space-y-1 > div.flex.flex-row.space-x-2 > button')
                ->waitFor('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(4) > div > div')
                ->click('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(4) > div > div > div.flex.flex-row.justify-between.mx-auto.w-3\/4.md\:w-1\/2 > button.text-right.rounded.px-2.bg-red-500')
                ->pause(1000);
        });

        $this->assertDatabaseMissing('free_days', ['name' => 'free', 'date' => '2023-01-01']);

    }

}
