<?php

namespace Modules\Order\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Transaction;
use Stripe\StripeClient;

trait payment
{


    public function storePayment($token)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $customer_id=Transaction::where('user_id', Auth::id())->pluck('customer_id')->first();
        if(!isset($customer_id)){
            $email = User::where('id', Auth::id())->pluck('email')->first();
            $customer = $stripe->customers->create([
                'email' => $email,
                'description' => 'customer ' . $email,
            ]);

            Transaction::create([
                'customer_id' => $customer->id,
                'token_id' => $token->id,
                'user_id' => Auth::id(),
            ]);

            $card_response = $stripe->customers->createSource(
                $customer['id'],
                [
                    'source' =>$token['id'],
                ]
            );

            return $card_response;
        }else{
            $card_response = $stripe->customers->createSource(
                $customer_id,
                [
                    'source' =>$token['id'],
                ]
            );

            return $card_response;
        }
    }




}
