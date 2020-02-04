<?php

namespace Tests\Feature;

use App\Models\Concert;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewConcertListingTest extends TestCase
{

    /**
     * @test
     */
    public function user_can_view_a_concert_listing()
    {
        // Arrange
        // Create a concert
        $concert = Concert::create([
           'title' => 'The Red Chord',
           'subtitle' => 'with Animosity and Letghracy',
           'date' => Carbon::parse('December 13, 2016 8:00pm'),
           'ticket_price' => 3250,
           'venue' => 'The Mosh Pit',
           'venue_address' => '123 Example Lane',
           'city' => 'Laraville',
           'state' => 'PHP',
           'zip' => '17916',
           'additional_information' => 'For tickets, call (555) 555-555'
        ]);

        // Act
        // View the concert listing
        $response = $this->get('/concerts/' . $concert->id);

        $response->assertStatus(200);

        // Assert
        //  See the concert details
        $response->assertSee('The Red Chord');
        $response->assertSee('with Animosity and Letghracy');
        $response->assertSee('December 13, 2016 8:00pm');
        $response->assertSee('32.50');
        $response->assertSee('The Mosh Pit');
        $response->assertSee('123 Example Lane');
        $response->assertSee('Laraville, PHP 17916');
        $response->assertSee('For tickets, call (555) 555-555');
    }
}
