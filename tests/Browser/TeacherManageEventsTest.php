<?php

namespace Tests\Browser;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TeacherManageEventsTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp() :void
    {
        $this->appUrl = env('APP_URL');
        parent::setUp();
        $this->artisan('db:seed');
    }

    /** @test */
    public function teacher_can_declare_new_appointment_date()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'teacher_test',
            'email'=> 'teacher@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 2
        ]);
        $user1 = User::create([
            'id' => 6,
            'name' => 'admin_test',
            'email'=> 'admin@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 1
        ]);

        $this->browse(function (Browser $browser) use ($user, $user1) {
            $browser
                ->loginAs($user1)
                ->visit('/adminPanel/semester')
                ->waitFor('div')
                ->click('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(1) > button')
                ->waitFor('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(2) > div > div')
                ->type('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(2) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-4.p-4 > input:nth-child(2)', 'test_2022/2023')
                ->type('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(2) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-4.p-4 > input:nth-child(4)', '01.10.2022')
                ->type('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(2) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-4.p-4 > input:nth-child(6)', '20.02.2023')
                ->click('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(2) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-4.p-4 > button')
                ->click('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(2) > div > div > div.text-right > button')
                ->pause(1000)
                ->select('#app > div > div.border-l-2.w-full.border-default > div > div > div.grid.grid-cols-1.md\:grid-cols-3.bg-page.rounded-xl.m-4.p-4 > div:nth-child(1) > div > select', 'A')
                ->select('#app > div > div.border-l-2.w-full.border-default > div > div > div.grid.grid-cols-1.md\:grid-cols-3.bg-page.rounded-xl.m-4.p-4 > div:nth-child(2) > div > select', 'B')
                ->pause(1000)
                ->logout();


            $this->assertDatabaseHas('semesters', ['name' => 'test_2022/2023']);


            $browser
                ->loginAs($user)
                ->visit('/teachers/5')
                ->waitFor('@events-cal')
                ->click('#app > div > div > div.md\:w-1\/4.md\:fixed.left-1.z-40 > div > button')
                ->waitFor('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div:nth-child(1) > div > div > div')
                ->select('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div:nth-child(1) > div > div > form > div > select:nth-child(2)', '1')
                ->type('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div:nth-child(1) > div > div > form > div > input:nth-child(4)', '8:00')
                ->type('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div:nth-child(1) > div > div > form > div > input:nth-child(6)', '10:30')
                ->select('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div:nth-child(1) > div > div > form > div > select:nth-child(8)', 'A/B')
                ->type('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div:nth-child(1) > div > div > form > div > input:nth-child(10)', '102')
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div:nth-child(1) > div > div > form > div > button')
                ->pause(1000);

        });
        $this->assertDatabaseHas('events', ['start' => '2022-10-03 08:00:00', 'end' => '2022-10-03 10:30:00', 'room' => '102']);
    }

    /** @test */
    public function teacher_can_check_appointment_list()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'teacher_test',
            'email'=> 'teacher@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 2
        ]);



        $this->browse(function (Browser $browser) use ($user) {

            $browser
                ->loginAs($user)
                ->visit('/teachers/5')
                ->waitFor('div')
                ->click('#app > div > div > div.md\:w-1\/4.md\:fixed.left-1.z-40 > div > a')
                ->waitFor('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div.overflow-x-auto.relative.m-5.rounded-xl > table > tr.text-default.uppercase.bg-page > th:nth-child(1)')
                ->assertSee('APPOINTMENT DATE');



        });
    }
    /** @test */
    public function teacher_can_see_events_with_busy_and_accepted_classes_on_appointment_list()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'teacher_test',
            'email'=> 'teacher@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 2
        ]);

        $event1 = Event::create([
            'start' => Carbon::now()->format('Y-m-d H:i'),
            'end' => Carbon::now()->addMinutes(15)->format('Y-m-d H:i'),
            'subject' => 'test',
            'student_info' => 'test',
            'message' =>'test',
            'room' => '102',
            'teacher_id' => $user->id,
            'student_id' => 3,
            'class' => 2
        ]);
        $event2 = Event::create([
            'start' => Carbon::now()->addMinutes(45)->format('Y-m-d'),
            'end' => Carbon::now()->addMinutes(60)->format('Y-m-d'),
            'subject' => 'test',
            'student_info' => 'test',
            'message' =>'test',
            'room' => '102',
            'teacher_id' => $user->id,
            'student_id' => 3,
            'class' => 3
        ]);



        $this->browse(function (Browser $browser) use ($user, $event1, $event2) {

            $browser
                ->loginAs($user)
                ->visit('/teachers/5/events')
                ->waitFor('div')
                ->assertSee($event1->start)
                ->assertSee($event2->start)
                ->pause(1000);

        });
    }

    /** @test */
    public function teacher_can_accept_booked_event()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'teacher_test',
            'email'=> 'teacher@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 2
        ]);

        $event1 = Event::create([
            'start' => Carbon::now()->format('Y-m-d H:i'),
            'end' => Carbon::now()->addMinutes(15)->format('Y-m-d H:i'),
            'subject' => 'test',
            'student_info' => 'test',
            'message' =>'test',
            'room' => '102',
            'teacher_id' => $user->id,
            'student_id' => 3,
            'class' => 2
        ]);



        $this->browse(function (Browser $browser) use ($user, $event1) {

            $browser
                ->loginAs($user)
                ->visit('/teachers/5/events')
                ->waitFor('div')
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div.overflow-x-auto.relative.m-5.rounded-xl > table > tr.uppercase.border.border-default.text-default.busy > th:nth-child(5) > div > button')
                ->waitFor('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div.overflow-x-auto.relative.m-5.rounded-xl > table > div > div > div')
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div.overflow-x-auto.relative.m-5.rounded-xl > table > div > div > div > div.text-center.space-x-8 > button.p-1.rounded.bg-green-600.disabled\:bg-gray-500')
                ->waitFor('div > div.Vue-Toastification__progress-bar', 10)
                ->assertSee($event1->start)
                ->assertSee('ACCEPTED')
                ->pause(1000);

        });

        $this->assertDatabaseHas('events', ['start' => $event1->start, 'end' => $event1->end, 'class' => 3]);
    }
/** @test */
    public function teacher_can_cancel_booked_event()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'teacher_test',
            'email'=> 'teacher@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 2
        ]);

        $event1 = Event::create([
            'start' => Carbon::now()->format('Y-m-d H:i'),
            'end' => Carbon::now()->addMinutes(15)->format('Y-m-d H:i'),
            'subject' => 'test',
            'student_info' => 'test',
            'message' =>'test',
            'room' => '102',
            'teacher_id' => $user->id,
            'student_id' => 3,
            'class' => 2
        ]);



        $this->browse(function (Browser $browser) use ($user, $event1) {

            $browser
                ->loginAs($user)
                ->visit('/teachers/5/events')
                ->waitFor('div')
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div.overflow-x-auto.relative.m-5.rounded-xl > table > tr.uppercase.border.border-default.text-default.busy > th:nth-child(5) > div > button')
                ->waitFor('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div.overflow-x-auto.relative.m-5.rounded-xl > table > div > div > div')
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div.overflow-x-auto.relative.m-5.rounded-xl > table > div > div > div > div.text-center.space-x-8 > button.p-1.rounded.bg-red-600.mt-3.disabled\:bg-gray-500')
                ->waitFor('div > div.Vue-Toastification__progress-bar', 10)
                ->assertDontSee($event1->start)
                ->pause(1000);

        });

        $this->assertDatabaseHas('events', ['start' => $event1->start, 'end' => $event1->end, 'class' => 4]);
    }

/** @test */
    public function teacher_can_cancel_accepted_event()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'teacher_test',
            'email'=> 'teacher@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 2
        ]);

        $event1 = Event::create([
            'start' => Carbon::now()->format('Y-m-d H:i'),
            'end' => Carbon::now()->addMinutes(15)->format('Y-m-d H:i'),
            'subject' => 'test',
            'student_info' => 'test',
            'message' =>'test',
            'room' => '102',
            'teacher_id' => $user->id,
            'student_id' => 3,
            'class' => 3
        ]);



        $this->browse(function (Browser $browser) use ($user, $event1) {

            $browser
                ->loginAs($user)
                ->visit('/teachers/5/events')
                ->waitFor('div')
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div.overflow-x-auto.relative.m-5.rounded-xl > table > tr.uppercase.border.border-default.text-default.accepted > th:nth-child(5) > div > button')
                ->waitFor('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div.overflow-x-auto.relative.m-5.rounded-xl > table > div > div > div')
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div.overflow-x-auto.relative.m-5.rounded-xl > table > div > div > div > div.text-center.space-x-8 > button.p-1.rounded.bg-red-600.mt-3.disabled\:bg-gray-500')
                ->waitFor('div > div.Vue-Toastification__progress-bar', 10)
                ->assertDontSee($event1->start)
                ->pause(1000);

        });

        $this->assertDatabaseHas('events', ['start' => $event1->start, 'end' => $event1->end, 'class' => 4]);
    }

}
