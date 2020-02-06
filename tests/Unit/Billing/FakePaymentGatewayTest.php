<?php


namespace Tests\Unit\Billing;


use App\Billing\FakePaymentGateway;
use App\Billing\PaymentFailedException;
use Tests\TestCase;

class FakePaymentGatewayTest extends TestCase
{
    /**
     * @test
     */
    public function charges_with_a_valid_payment_token_are_successful()
    {
        $paymentGateway = new FakePaymentGateway;

        $paymentGateway->charge(3250, $paymentGateway->getValidTestToken());

        $this->assertEquals(3250, $paymentGateway->totalCharges());
    }

    /**
     * @test
     */
    public function charges_with_an_invalid_payment_token_fails()
    {
        try {
            $paymentGateway = new FakePaymentGateway;

            $paymentGateway->charge(2500, 'invalid-payment-token');

        } catch (PaymentFailedException $e) {

            return;
        }

        $this->fail();

    }
}
