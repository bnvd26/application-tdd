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

    private function orderTickets($concert, $params)
    {
        return $this->json('POST', '/concerts/' . $concert->id . '/orders', $params);
    }

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
       $response = $this->orderTickets($concert, [
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

    /**
     * @test
     */
    public function email_is_required_to_purchase_tickets()
    {
        $this->withoutMiddleware();

        $paymentGateway = new FakePaymentGateway;

        // Arrange
        // Create a concert
        $concert = factory(Concert::class)->create();

        // Act
        // Purchase concert tickets
        $response = $this->orderTickets($concert , [
            'ticket_quantity' => 3,
            'payment_token' => $paymentGateway->getValidTestToken()
        ]);

       $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function email_must_be_valid_to_purchase_tickets()
    {
        $this->withoutMiddleware();

        $paymentGateway = new FakePaymentGateway;

        $concert = factory(Concert::class)->create();

        $response = $this->orderTickets($concert, [
           'email' => 'not-an-email-address',
           'ticket_quantity' => 3,
           'payment_token' => $paymentGateway->getValidTestToken()
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function ticket_quantity_is_required_to_purchase_ticket()
    {
        $this->withoutMiddleware();

        $paymentGateway = new FakePaymentGateway;

        $concert = factory(Concert::class)->create();

        $response = $this->orderTickets($concert, [
            'email' => 'john@example.com',
            'payment_token' => $paymentGateway->getValidTestToken()
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function ticket_quantity_must_be_at_least_1_to_purchase_tickets()
    {
        $this->withoutMiddleware();

        $paymentGateway = new FakePaymentGateway;

        $concert = factory(Concert::class)->create();

        $response = $this->orderTickets($concert, [
            'ticket_quantity' => 0,
            'email' => 'john@example.com',
            'payment_token' => $paymentGateway->getValidTestToken()
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function payment_token_is_required()
    {
        $this->withoutMiddleware();

        $paymentGateway = new FakePaymentGateway;

        $concert = factory(Concert::class)->create();

        $response = $this->orderTickets($concert, [
            'ticket_quantity' => 1,
            'email' => 'john@example.com',
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('message', $response->decodeResponseJson());

    }
}
