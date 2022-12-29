<?php

namespace Tests\Browser;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TeachersProfileTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp() :void
    {
        $this->appUrl = env('APP_URL');
        parent::setUp();
        $this->artisan('db:seed');
    }

    /** @test */
    public function user_can_visit_teacher_profile()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/teachers/4')
                ->waitFor('div')
                ->assertSee('teacher@teacher.pl');
        });
    }
    /** @test */
    public function user_can_see_free_event()
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


        $this->browse(function (Browser $browser) use ($event) {
            $browser->visit('/teachers/4')
                ->waitFor('@events-cal')
                ->assertSee($event->start);
        });
    }

    /** @test */
    public function guest_can_not_open_event()
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


        $this->browse(function (Browser $browser) use ($event) {
            $browser->visit('/teachers/4')
                ->waitFor('@events-cal')
                ->assertSee($event->start)
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div > div > div.vuecal__flex.vuecal__body > div > div > div > div.vuecal__flex.vuecal__cells.week-view > div > div.vuecal__cell.vuecal__cell--selected.vuecal__cell--has-events > div > div > div')
                ->waitFor('div.Vue-Toastification__toast-body')
                ->assertSee('Only logged in users can book an appointment');
        });
    }


    /** @test */
    public function auth_user_can_open_free_event()
    {
        $user = User::create([
            'name' => 'user',
            'email'=> 'user@user.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);

        $event = Event::create([
            'start' => Carbon::now()->hour(8)->minute(00),
            'end' => Carbon::now()->hour(10)->minute(30),
            'subject' => 'Test',
            'message' => 'Test',
            'room' => '123',
            'teacher_id' => 4,
            'class' => 1
        ]);


        $this->browse(function (Browser $browser) use ($event, $user) {
            $browser
                ->loginAs($user)
                ->visit('/teachers/4')
                ->waitFor('@events-cal')
                ->assertSee($event->start)
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div > div > div.vuecal__flex.vuecal__body > div > div > div > div.vuecal__flex.vuecal__cells.week-view > div > div.vuecal__cell.vuecal__cell--selected.vuecal__cell--has-events > div > div > div')
                ->assertSeeAnythingIn('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div.z-50.fixed.inset-0.w-full.h-screen.flex.items-center.justify-center.bg-bg-semi-75 > div > form');
        });
    }

    /** @test */
    public function user_can_not_open_event_booked_by_other_user()
    {
        $user = User::create([
            'name' => 'user',
            'email'=> 'user@user.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);
        $user1 = User::create([
            'name' => 'user2',
            'email'=> 'user2@user.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);

        $event = Event::create([
            'start' => Carbon::now()->hour(8)->minute(00),
            'end' => Carbon::now()->hour(10)->minute(30),
            'subject' => 'Test',
            'message' => 'Test',
            'room' => '123',
            'teacher_id' => 4,
            'student_id' => $user1->id,
            'class' => 2
        ]);


        $this->browse(function (Browser $browser) use ($event, $user) {
            $browser
                ->loginAs($user)
                ->visit('/teachers/4')
                ->waitFor('@events-cal')
                ->assertSee($event->start)
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div > div > div.vuecal__flex.vuecal__body > div > div > div > div.vuecal__flex.vuecal__cells.week-view > div > div.vuecal__cell.vuecal__cell--selected.vuecal__cell--has-events > div > div > div')
                ->waitFor('div.Vue-Toastification__toast-body')
                ->assertSee('The appointment has already been booked');
        });
    }

    /** @test */
    public function user_can_not_open_accepted_event_booked_by_other_user()
    {
        $user = User::create([
            'name' => 'user',
            'email'=> 'user@user.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);
        $user1 = User::create([
            'name' => 'user2',
            'email'=> 'user2@user.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);

        $event = Event::create([
            'start' => Carbon::now()->hour(8)->minute(00),
            'end' => Carbon::now()->hour(10)->minute(30),
            'subject' => 'Test',
            'message' => 'Test',
            'room' => '123',
            'teacher_id' => 4,
            'student_id' => $user1->id,
            'class' => 3
        ]);


        $this->browse(function (Browser $browser) use ($event, $user) {
            $browser
                ->loginAs($user)
                ->visit('/teachers/4')
                ->waitFor('@events-cal')
                ->assertSee($event->start)
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div > div > div.vuecal__flex.vuecal__body > div > div > div > div.vuecal__flex.vuecal__cells.week-view > div > div.vuecal__cell.vuecal__cell--selected.vuecal__cell--has-events > div > div > div')
                ->waitFor('div.Vue-Toastification__toast-body')
                ->assertSee('The appointment has already been booked');
        });
    }
    /** @test */
    public function user_can_not_open_cancelled_event()
    {
        $user = User::create([
            'name' => 'user',
            'email'=> 'user@user.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);


        $event = Event::create([
            'start' => Carbon::now()->hour(8)->minute(00),
            'end' => Carbon::now()->hour(10)->minute(30),
            'subject' => 'Test',
            'message' => 'Test',
            'room' => '123',
            'teacher_id' => 4,
            'class' => 4
        ]);


        $this->browse(function (Browser $browser) use ($event, $user) {
            $browser
                ->loginAs($user)
                ->visit('/teachers/4')
                ->waitFor('@events-cal')
                ->assertSee($event->start)
                ->click('#app > div > div > div.border-l-2.border-default.pl-2.mt-8.min-h-screen.md\:mt-0.font-semibold.md\:w-3\/4 > div > div > div > div > div > div > div.vuecal__flex.vuecal__body > div > div > div > div.vuecal__flex.vuecal__cells.week-view > div > div.vuecal__cell.vuecal__cell--selected.vuecal__cell--has-events > div > div > div')
                ->waitFor('div.Vue-Toastification__toast-body')
                ->assertSee('The appointment has already been booked');
        });
    }
}
