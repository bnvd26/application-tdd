<?php


namespace Tests\Feature;


use App\Billing\FakePaymentGateway;
use App\Billing\PaymentGateway;
use App\Models\Concert;
use App\Models\Order;
use App\Models\Ticket;
use Tests\TestCase;

class PurchaseTicketsTest extends TestCase
{
    /**
     * @test
     */
    public function customer_can_purchase_concert_tickets()
    {
        $this->withoutMiddleware();

        $paymentGateway = new FakePaymentGateway;

        // Arrange
        // Create a concert
        $concert = factory(Concert::class)->create(['ticket_price' => 3250]);

        // Act
        // Purchase concert tickets
        $response = $this->json('POST', '/concerts/' . $concert->id . '/orders' , [
            'email' => 'john@example.com',
            'ticket_quantity' => 3,
            'payment_token' => $paymentGateway->getValidTestToken()
        ]);

        // Assert

        $paymentGateway->charges[] = $concert->ticket_price * request('ticket_quantity');

        $response->assertStatus(201);

        // Make sure the customer was charged the correct amount
        $this->assertEquals(9750, $paymentGateway->totalCharges());

        // Make sur that an order exists for this customer
        $order = Order::where('email', 'john@example.com')->first();

        $ticket = Ticket::where('order_id', $order->id)->get();

        $this->assertNotNull($order);

        $this->assertEquals(3,  $ticket->count());
    }
}
