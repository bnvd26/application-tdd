<?php


namespace App\Http\Controllers;


use App\Billing\FakePaymentGateway;
use App\Billing\PaymentGateway;
use App\Models\Concert;


class ConcertOrdersController extends Controller
{

    public function store($concert)
    {
        $paymentGateway = new FakePaymentGateway;

        $concert = Concert::find($concert);

        $paymentGateway->charge(request('ticket_quantity') * $concert->ticket_price, request('payment_token'));

        $concert->orderTickets(request('email'), request('ticket_quantity'));

        return response()->json([], 201);
    }
}
