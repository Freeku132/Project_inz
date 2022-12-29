<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TeachersTableTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp() :void
    {
        $this->appUrl = env('APP_URL');
        parent::setUp();
        $this->artisan('db:seed');
    }

    /**
     * @test
     */
    public function guest_can_see_teachers_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/teachers')
                ->waitFor('div')
                ->assertSee('All Teachers')
                ->assertSeeLink('teacher');
        });
    }
    /**
     * @test
     */
    public function auth_user_can_see_teachers_page()
    {
        $user = User::create([
            'name' => 'user',
            'email'=> 'user@user.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);

        $this->browse(function (Browser $browser) use($user) {
            $browser->loginAs($user)
                ->visit('/teachers')
                ->waitFor('div')
                ->assertSee('All Teachers')
                ->assertSeeLink('teacher');
        });
    }

    /**
     * @test
     */
    public function user_can_search_teacher_by_name()
    {
        $user = User::create([
            'name' => 'user',
            'email'=> 'user@user.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);

        $this->browse(function (Browser $browser) use($user) {
            $browser->loginAs($user)
                ->visit('/teachers')
                ->waitFor('div')
                ->type('input[id="search"]', 'teacher')
                ->pause(1000)
                ->assertSeeLink('teacher')
                ->type('input[id="search"]', 'null')
                ->pause(1000)
                ->assertDontSee('teacher');
        });
    }

    /**
     * @test
     */
    public function user_can_visit_teacher_page()
    {
        $user = User::create([
            'name' => 'user',
            'email'=> 'user@user.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);

        $this->browse(function (Browser $browser) use($user) {
            $browser->loginAs($user)
                ->visit('/teachers')
                ->waitFor('div')
                ->clickLink('teacher')
                ->pause(1000)
                ->assertPathIs('/teachers/4');
        });
    }


}
