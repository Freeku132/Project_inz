<?php

namespace Tests\Browser;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EventsTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp() :void
    {
        $this->appUrl = env('APP_URL');
        parent::setUp();
        $this->artisan('db:seed');
    }

    /** @test */
    public function student_can_book_free_event()
    {
        $event = Event::create([
            'start' => Carbon::now()->hour(8)->minute(00),
            'end' => Carbon::now()->hour(10)->minute(30),
            'subject' => 'Test',
            'message' => 'Test',
            'room' => '123',
            'teacher_id' => 4,
            'class' => 1
        ]);

        $user = User::create([
            'name' => 'student',
            'email'=> 'student@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);

        $end_event = Carbon::now()->hour(8)->minute(30);

        $this->browse(function (Browser $browser) use ($user, $event, $end_event) {
            $browser->loginAs($user)
                ->visit('/teachers/4')
                ->waitFor('div')
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div > div > div.vuecal__flex.vuecal__body > div > div > div > div.vuecal__flex.vuecal__cells.week-view > div > div.vuecal__cell.vuecal__cell--today.vuecal__cell--selected.vuecal__cell--has-events > div.vuecal__flex.vuecal__cell-content > div > div')
                ->type('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div.z-50.fixed.inset-0.w-full.h-screen.flex.items-center.justify-center.bg-bg-semi-75 > div > form > input:nth-child(4)', 'Test_subject')
                ->type('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div.z-50.fixed.inset-0.w-full.h-screen.flex.items-center.justify-center.bg-bg-semi-75 > div > form > input:nth-child(6)', 'Test_level')
                ->type('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div.z-50.fixed.inset-0.w-full.h-screen.flex.items-center.justify-center.bg-bg-semi-75 > div > form > textarea', 'Test_message')
                ->select('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div.z-50.fixed.inset-0.w-full.h-screen.flex.items-center.justify-center.bg-bg-semi-75 > div > form > select:nth-child(12)', $event->start->format('Y-m-d H:i'))
                ->select('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div.z-50.fixed.inset-0.w-full.h-screen.flex.items-center.justify-center.bg-bg-semi-75 > div > form > select.bg-page.mx-5.rounded-b-md.border.border-default', $end_event->format('Y-m-d H:i'))
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div.z-50.fixed.inset-0.w-full.h-screen.flex.items-center.justify-center.bg-bg-semi-75 > div > form > div:nth-child(15) > button.bg-green-500.w-1\/4.h-10.rounded-bl-xl.mt-5.disabled\:bg-gray-600')
                ->pause(5000)
                ->assertSee('You has been successfully reserved appointment');
        });

        $this->assertDatabaseHas('events', ['start' => $event->start, 'end' => $end_event, 'student_id' => $user->id, 'class' => 2]);
        $this->assertDatabaseHas('events', ['start' => $end_event, 'end' => $event->end, 'class' => 1]);
    }

    /** @test */
    public function teacher_can_accept_booked_event_in_cal_view()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'teacher',
            'email'=> 'teacher@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 2
        ]);

        $event = Event::create([
            'start' => Carbon::now()->hour(8)->minute(00),
            'end' => Carbon::now()->hour(10)->minute(30),
            'subject' => 'Test',
            'message' => 'Test',
            'room' => '123',
            'teacher_id' => 5,
            'student_id' => 2,
            'class' => 2
        ]);


        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/teachers/5')
                ->waitFor('div')
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div > div > div.vuecal__flex.vuecal__body > div > div > div > div.vuecal__flex.vuecal__cells.week-view > div > div.vuecal__cell.vuecal__cell--today.vuecal__cell--selected.vuecal__cell--has-events > div.vuecal__flex.vuecal__cell-content > div > div')
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div.z-50.fixed.inset-0.w-full.h-screen.flex.items-center.justify-center.bg-bg-semi-75 > div > form > div.bg-green-600.rounded-t-3xl.p-2.text-center.flex.flex-col.justify-between > div > button.bg-green-700.font-bold.text-xl.px-1.rounded-md.disabled\:bg-gray-500')
                ->pause(5000)
                ->assertSee('You has been successfully change event status to accepted');
        });

        $this->assertDatabaseHas('events', ['start' => $event->start, 'end' => $event->end, 'class' => 3]);
    }
    /** @test */
    public function teacher_can_cancel_booked_event_in_cal_view()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'teacher',
            'email'=> 'teacher@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 2
        ]);

        $event = Event::create([
            'start' => Carbon::now()->hour(8)->minute(00),
            'end' => Carbon::now()->hour(10)->minute(30),
            'subject' => 'Test',
            'message' => 'Test',
            'room' => '123',
            'teacher_id' => 5,
            'student_id' => 2,
            'class' => 2
        ]);


        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/teachers/5')
                ->waitFor('div')
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div > div > div.vuecal__flex.vuecal__body > div > div > div > div.vuecal__flex.vuecal__cells.week-view > div > div.vuecal__cell.vuecal__cell--today.vuecal__cell--selected.vuecal__cell--has-events > div.vuecal__flex.vuecal__cell-content > div > div')
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div.z-50.fixed.inset-0.w-full.h-screen.flex.items-center.justify-center.bg-bg-semi-75 > div > form > div.bg-green-600.rounded-t-3xl.p-2.text-center.flex.flex-col.justify-between > div > button.relative.mr-5.bg-red-600.font-bold.text-xl.px-1.rounded-md.disabled\:bg-gray-500')
                ->pause(5000)
                ->assertSee('You has been successfully change event status to cancelled');
        });

        $this->assertDatabaseHas('events', ['start' => $event->start, 'end' => $event->end, 'class' => 4]);
    }
}
