<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;

class LanguageTest extends DuskTestCase
{
    /**
     * @test
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitFor('div')
                ->assertSee('Consultations')
                ->click('button[id="language"]')
                ->click('button[id="pl"]')
                ->pause(1000)
                ->assertSee('Konsultacje');
        });
    }
}
