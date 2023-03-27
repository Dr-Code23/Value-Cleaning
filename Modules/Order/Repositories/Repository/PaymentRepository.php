<?php

namespace Modules\Order\Repositories\Repository;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Order\Entities\Transaction;
use Modules\Order\Repositories\Interfaces\PaymentRepositoryInterface;
use Modules\Order\Traits\payment;
use Stripe\StripeClient;
use Modules\Order\Entities\Order;


class PaymentRepository implements PaymentRepositoryInterface
{
    use payment;

    public function makePayment($data)

    {
        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $token = $stripe->tokens->create([
            'card' => [
                'number' => $data->number,
                'exp_month' => $data->exp_month,
                'exp_year' => $data->exp_year,
                'cvc' => $data->cvc,
            ],
        ]);

        return $this->storePayment($token);
    }

    public function allPayment()
    {
        try {


            $transaction = Transaction::where('user_id', Auth::id())->pluck('customer_id')->first();
            $stripe = new StripeClient(env('STRIPE_SECRET'));
            $cards = $stripe->customers->allSources(
                $transaction,
                ['object' => 'card', 'limit' => 7]
            // you can remove the limit key to get all the cards
            );
            return $cards;
        } catch (Exception $e) {
            Log::error(
                'Failed to fetch user cards',
                ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]
            );
            return false;
        }
    }

    public function checkoutPayment($data)
    {
        try {
            $order = Order::where('id', $data->order_id)->first();
            $stripe = new StripeClient(env('STRIPE_SECRET'));
            $charge = $stripe->charges->create([
                'card' => $data['token'],
                'customer' => $data['customer_id'],
                'currency' => 'USD',
                'amount' => ($order->total_price * 100),
                'description' => "New Payment Received from mobile app",
                'metadata' => [
                    "order_id" => $data->order_id,
                ],
            ]);
            if ($charge->status == 'succeeded') {
                $data = ['transaction_id' => $charge->id];
                $order['payment_status'] = 'credit';
                $order['status'] = 'finished';
                $order->update();
                return ['statusCode' => 200,
                    'status' => true, 'message' => 'Transaction Success', 'data' => $data];
            } else {
                return ['statusCode' => 400,
                    'status' => false, 'message' => "Error Processing Transaction", 'data' => []];
            }
        } catch (Exception $e) {
            return ['statusCode' => 400,
                'status' => false, 'message' => "Error Processing Transaction", 'data' => []];
        }
    }

    public function deletePayment($data)
    {
        try {
            $stripe = new StripeClient(env('STRIPE_SECRET'));
            $cards = $stripe->customers->deleteSource(
                $data['customer_id'],
                $data['token_id'],
                []
            );
            return $cards;
        } catch (Exception $e) {
            Log::error(
                'Failed to delete user cards',
                ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]
            );
            return false;
        }
    }
}






