<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewConcertListingTest extends TestCase
{
    /**
     * @test
     */
    function user_can_view_a_concert_listing()
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
        $this->visit('/concerts/'.$concert->id);

        // Assert
        //  See the concert details
        $this->see('The Red Chord');
        $this->see('with Animosity and Letghracy');
        $this->see('December 13, 2016 8:00pm');
        $this->see('32.50');
        $this->see('The Mosh Pit');
        $this->see('123 Example Lane');
        $this->see('Laraville, PHP 17916');
        $this->see('For tickets, call (555) 555-555');
    }
}
