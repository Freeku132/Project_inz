<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminPanelTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp() :void
    {
        $this->appUrl = env('APP_URL');
        parent::setUp();
        $this->artisan('db:seed');
    }


    /** @test */
    public function guest_can_not_see_admin_panel()
    {
        $this->browse(function (Browser $browser) {
            $browser->assertGuest()
                ->visit('/adminPanel')
                ->assertSee('THIS ACTION IS UNAUTHORIZED.');
        });
    }
    /** @test */
    public function student_can_not_see_admin_panel()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'user',
            'email'=> 'user@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/adminPanel')
                ->assertSee('THIS ACTION IS UNAUTHORIZED.');
        });
    }
    /** @test */
    public function teacher_can_not_see_admin_panel()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'teacher',
            'email'=> 'teacher@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 2
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/adminPanel')
                ->assertSee('THIS ACTION IS UNAUTHORIZED.');
        });
    }
    /** @test */
    public function admin_can_see_admin_panel()
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
                ->visit('/adminPanel')
                ->waitFor('div')
                ->assertSee('Hello, here you can manage system');
        });
    }
    /** @test */
    public function admin_can_see_manage_users_panel()
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
                ->visit('/adminPanel')
                ->waitFor('div')
                ->click('#app > div > div.lg\:w-1\/6.md\:w-1\/5.shadow-3xl.p-2.space-y-2 > div:nth-child(2) > div > button')
                ->clickLink('Manage users')
                ->assertSee('All Teachers')
                ->pause(1000);
        });
    }
    /** @test */
    public function admin_can_see_manage_semester_panel()
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
                ->visit('/adminPanel')
                ->waitFor('div')
                ->click('#app > div > div.lg\:w-1\/6.md\:w-1\/5.shadow-3xl.p-2.space-y-2 > div:nth-child(4) > div > button')
                ->clickLink('Semester configuration')
                ->waitFor('#app > div > div.border-l-2.w-full.border-default > div > div')
                ->assertSee('Set new semester')
                ->assertSeeAnythingIn('#app > div > div.border-l-2.w-full.border-default > div > div > div:nth-child(1) > button')
                ->pause(1000);
        });
    }
    /** @test */
    public function admin_can_see_admin_panel_link()
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
                ->visit('/')
                ->waitFor('div')
                ->assertSee('Admin Panel')
                ->clickLink('Admin Panel');
        });
    }
    /** @test */
    public function guest_can_not_see_admin_panel_link()
    {
        $this->browse(function (Browser $browser) {
            $browser->assertGuest()
                ->visit('/')
                ->waitFor('div')
                ->assertDontSee('Admin Panel');
        });
    }
    /** @test */
    public function student_and_teacher_can_not_see_admin_panel_link()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'teacher',
            'email'=> 'teacher@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);
        $user1 = User::create([
            'id' => 5,
            'name' => 'student',
            'email'=> 'student@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 2
        ]);

        $this->browse(function (Browser $browser) use ($user, $user1) {
            $browser->loginAs($user)
                ->visit('/')
                ->waitFor('div')
                ->assertDontSee('Admin Panel')
                ->logout()
                ->loginAs($user1)
                ->visit('/')
                ->waitFor('div')
                ->assertDontSee('Admin Panel');
        });
    }
}
