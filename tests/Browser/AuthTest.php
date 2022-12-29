<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


class AuthTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp() :void
    {
        $this->appUrl = env('APP_URL');
        parent::setUp();
        $this->artisan('db:seed');
//        foreach (static::$browsers as $browser) {
//            $browser->driver->manage()->deleteAllCookies();
//        }
    }


    /**
     * @test
     */
    public function guest_can_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->waitFor('div')
                ->type('input[id="name"]', 'user')
                ->type('input[id="email"]', 'user@user.pl')
                ->type('input[id="password"]', '12qwaszx')
                ->type('input[id="password_confirmation"]', '12qwaszx')
                ->click('button[id="register"]')
                ->pause(1000)
                ->assertDontSee('Register')
                ->logout()
//                ->click('button[id="dropdown_user"]')
//                ->click('button[id="logout"]')
            ;
        });

        $this->assertDatabaseHas('users', ['email'=>'user@user.pl']);
    }

    /**
     * @test
     */
    public function guest_can_login()
    {

        User::create([
            'name' => 'user',
            'email'=> 'user@user.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('div')
                ->type('input[id="email"]', 'user@user.pl')
                ->type('input[id="password"]', '12qwaszx')
                ->click('button[id="login"]')
                ->pause(1000)
                ->assertDontSee('Register');
        });
        $this->assertDatabaseHas('users', ['email'=>'user@user.pl'])
        ;
    }

    /**
     * @test
     */
    public function user_can_logout()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'user',
            'email'=> 'user@user.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);

        $this->browse(function (Browser $browser) use($user) {
            $browser->loginAs($user)
                ->visit('/')
                ->waitFor('div')
                ->click('@dropdown_user')
                ->click('button[id="logout"]')
                ->pause(1000)
                ->assertGuest();
        });
    }

    /**
     * @test
     */
    public function guest_can_not_logout()
    {

        $user = User::create([
            'id' => 5,
            'name' => 'user',
            'email'=> 'user@user.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user)
                ->visit('/')
                ->waitFor('div')
                ->logout()
                ->visit('/')
                ->assertDontSee('user')
                ->assertGuest();
        });
    }

}
