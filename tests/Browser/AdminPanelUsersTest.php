<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminPanelUsersTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp() :void
    {
        $this->appUrl = env('APP_URL');
        parent::setUp();
        $this->artisan('db:seed');
    }

    /** @test */
    public function admin_can_add_new_student()
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
                ->visit('/adminPanel/users')
                ->waitFor('div')
                ->click('#app > div > div.border-l-2.w-full.border-default > div.mt-20.w-2\/3.mx-auto.text-default > div > div > button')
                ->waitFor('#app > div > div:nth-child(5) > div > div')
                ->type('#app > div > div:nth-child(5) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-2.p-2 > input:nth-child(2)', 'user')
                ->type('#app > div > div:nth-child(5) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-2.p-2 > input:nth-child(4)', 'user@test.pl')
                ->type('#app > div > div:nth-child(5) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-2.p-2 > input:nth-child(6)', '12qwaszx')
                ->select('#app > div > div:nth-child(5) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-2.p-2 > select', '3')
                ->click('#app > div > div:nth-child(5) > div > div > div.flex.flex-row.justify-between.w-3\/4.md\:w-1\/2.mx-auto > button.text-right.rounded.px-2.bg-green-600')
                ->waitFor('div.Vue-Toastification__toast-body')
                ->assertSee('User has been added')
                ->pause(1000)
            ;
        });
        $this->assertDatabaseHas('users', ['email'=>'user@test.pl', 'role_id'=>'3']);
    }

    /** @test */
    public function admin_can_add_new_teacher()
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
                ->visit('/adminPanel/users')
                ->waitFor('div')
                ->click('#app > div > div.border-l-2.w-full.border-default > div.mt-20.w-2\/3.mx-auto.text-default > div > div > button')
                ->waitFor('#app > div > div:nth-child(5) > div > div')
                ->type('#app > div > div:nth-child(5) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-2.p-2 > input:nth-child(2)', 'teacher')
                ->type('#app > div > div:nth-child(5) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-2.p-2 > input:nth-child(4)', 'teacher@test.pl')
                ->type('#app > div > div:nth-child(5) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-2.p-2 > input:nth-child(6)', '12qwaszx')
                ->select('#app > div > div:nth-child(5) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-2.p-2 > select', '2')
                ->click('#app > div > div:nth-child(5) > div > div > div.flex.flex-row.justify-between.w-3\/4.md\:w-1\/2.mx-auto > button.text-right.rounded.px-2.bg-green-600')
                ->waitFor('div.Vue-Toastification__toast-body')
                ->assertSee('User has been added')
                ->pause(1000)
            ;
        });
        $this->assertDatabaseHas('users', ['email'=>'teacher@test.pl', 'role_id'=>'2']);
    }

    /** @test */
    public function admin_can_edit_user()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'admin',
            'email'=> 'admin@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 1
        ]);
        $user1 = User::create([
            'id' => 6,
            'name' => 'user',
            'email'=> 'user@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/adminPanel/users')
                ->waitFor('div')
                ->click('#app > div > div.border-l-2.w-full.border-default > div.mt-20.w-2\/3.mx-auto.text-default > table > tbody > tr:nth-child(4) > td.p-2.md\:border.md\:border-default.text-left.block.sm\:space-y-1.md\:table-cell > button.text-right.rounded.mr-3.px-2.bg-blue-500')
                ->waitFor('#app > div > div:nth-child(3) > div > div')
                ->type('#app > div > div:nth-child(3) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-2.p-2 > input:nth-child(2)', 'edit_user')
                ->type('#app > div > div:nth-child(3) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-2.p-2 > input:nth-child(4)', 'edit_user@test.pl')
                ->type('#app > div > div:nth-child(3) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-2.p-2 > input:nth-child(6)', '12qwaszx')
                ->select('#app > div > div:nth-child(3) > div > div > div.flex.flex-col.bg-page.rounded-xl.m-2.p-2 > select', '2')
                ->click('#app > div > div:nth-child(3) > div > div > div.flex.flex-row.justify-between.mx-auto.w-3\/4.md\:w-1\/2 > button.text-right.rounded.px-2.bg-green-600')
                ->waitFor('div.Vue-Toastification__toast-body')
                ->assertSee('User has been edited')
                ->pause(1000)
            ;
        });
        $this->assertDatabaseHas('users', ['email'=>'edit_user@test.pl', 'role_id'=>'2', 'name' => 'edit_user']);
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'admin',
            'email'=> 'admin@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 1
        ]);
        $user1 = User::create([
            'id' => 6,
            'name' => 'user',
            'email'=> 'user@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 3
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/adminPanel/users')
                ->waitFor('div')
                ->click('#app > div > div.border-l-2.w-full.border-default > div.mt-20.w-2\/3.mx-auto.text-default > table > tbody > tr:nth-child(4) > td.p-2.md\:border.md\:border-default.text-left.block.sm\:space-y-1.md\:table-cell > button.text-right.rounded.px-2.bg-red-500')
                ->click('#app > div > div:nth-child(4) > div > div > div.flex.flex-row.justify-between.mx-auto.w-3\/4.md\:w-1\/2 > button.text-right.rounded.px-2.bg-red-500')
                ->waitFor('div.Vue-Toastification__toast-body')
                ->assertSee('User has been deleted')
                ->pause(1000)
            ;
        });
        $this->assertDatabaseMissing('users', ['email'=>'user@test.pl', 'role_id'=>'2', 'name' => 'user']);
    }

    /** @test */
    public function admin_can_search_users_by_name_or_email()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'admin',
            'email'=> 'admin@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 1
        ]);

        $this->browse(function (Browser $browser) use($user) {
            $browser->loginAs($user)
                ->visit('/adminPanel/users')
                ->waitFor('div')
                ->type('#app > div > div.border-l-2.w-full.border-default > div.mt-20.w-2\/3.mx-auto.text-default > div > div > input', 'student1')
                ->pause(1000)
                ->assertSee('student1@student.pl')
                ->type('#app > div > div.border-l-2.w-full.border-default > div.mt-20.w-2\/3.mx-auto.text-default > div > div > input', 'student1@student.pl')
                ->pause(1000)
                ->assertSee('student1')
                ->type('#app > div > div.border-l-2.w-full.border-default > div.mt-20.w-2\/3.mx-auto.text-default > div > div > input', 'null')
                ->pause(1000)
                ->assertDontSee('student1');
        });
    }


    /** @test */
    public function admin_can_change_table_page()
    {
        $user = User::create([
            'id' => 5,
            'name' => 'admin',
            'email'=> 'admin@test.pl',
            'password' => Hash::make('12qwaszx'),
            'role_id' => 1
        ]);
        User::create(['id' => 6, 'name' => 'user', 'email'=> 'user@test.pl', 'password' => Hash::make('12qwaszx'), 'role_id' => 3]);
        User::create(['id' => 7, 'name' => 'user1', 'email'=> 'user1@test.pl', 'password' => Hash::make('12qwaszx'), 'role_id' => 3]);
        User::create(['id' => 8, 'name' => 'user2', 'email'=> 'user2@test.pl', 'password' => Hash::make('12qwaszx'), 'role_id' => 3]);
        User::create(['id' => 9, 'name' => 'user3', 'email'=> 'user3@test.pl', 'password' => Hash::make('12qwaszx'), 'role_id' => 3]);
        User::create(['id' => 10, 'name' => 'user4', 'email'=> 'user4@test.pl', 'password' => Hash::make('12qwaszx'), 'role_id' => 3]);

        $this->browse(function (Browser $browser) use($user) {
            $browser->loginAs($user)
                ->visit('/adminPanel/users')
                ->waitFor('div')
                ->click('#app > div > div.border-l-2.w-full.border-default > div.flex.justify-center > div:nth-child(3) > div > a')
                ->pause(1000)
                ->assertSee('user4');
        });
    }


}
