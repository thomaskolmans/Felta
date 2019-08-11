<?php
namespace lib\shop;

use lib\helpers\UUID;

class Payment{

    private $oid;
    private $source;
    private $amount;
    private $currency;
    private $method;
    private $description;

    public function __construct($oid,$source,$method,$amount,$currency,$description){
        $this->oid = $oid;
        $this->source = $source;
        $this->method = $method;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->description = $description;
    }

    public static function webhook($id){
        self::chargeFromSource($id);
    }

    public static function mollieW

    public function pay(){
        switch($this->method){
            case "paypal":
                $this->charge();
            break;
            case "creditcard":
            case "card":
                $order = Order::get($this->oid);
                $orderCustomer = Customer::get($order->getCustomer());
                $customer = \Stripe\Customer::create(array(
                  "email" => $orderCustomer->email,
                  "source" => $this->source["id"],
                ));
                $transaction = new Transaction(
                    UUID::generate(20),
                    $this->source["id"],
                    $this->oid,
                    $this->method,
                    $this->amount,
                    $this->currency,
                    TransactionState::PCOMMITED, 
                    new \DateTime()
                );
                $transaction->save();
                $this->charge($customer);
                return $transaction;
            break;
            case "ideal":
                $this->charge();
            break;
            case "mollie":
                $this->mollieCharge();
            break;
        }
    }

    public function mollieCharge(){
        $payment = $mollie->payments->create([
            "amount" => [
                "currency" => $this->currency,
                "value" => Shop::intToDoubleSeperator($this->amount)
            ],
            "description" => $this->description,
            "redirectUrl" => Felta::getInstance()->settings->get("website_url")."/felta/shop/return",
            "webhookUrl"  => Felta::getInstance()->settings->get("website_url")."/felta/shop/mollie-webhook/",
        ]);
    }

    public function charge($customer = null){
        $transaction = Transaction::getFromSource($this->source);
        if($transaction->state < TransactionState::COMMITTED){
            if($customer !== null){
                $charge = \Stripe\Charge::create([
                  "amount" => $this->amount,
                  "currency" => $this->currency,
                  "source" => $this->source["id"],
                  "customer" => $customer
                ]);
            }else{
                $charge = \Stripe\Charge::create([
                  "amount" => $this->amount,
                  "currency" => $this->currency,
                  "source" => $this->source["id"],
                ]);
            }
            //Set order to paid
            $order = Order::get($transaction->order);
            $order->paid();

            //Set transaction to paid
            $transaction->state = TransactionState::COMMITTED;
            $transaction->update();

            //Clear shoppingcart
            $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
            $shoppingcart->destroy();

            return $charge;
        }
        return null;
    }

    public static function getSource($source){
        return \Stripe\Source::retrieve($source);
    }

    public static function chargeFromSource($source){
        $transaction = Transaction::getFromSource($source);   
        if($transaction !== null){
            $payment = new Payment(
                $transaction->transactionid,
                $source,
                $transaction->method,
                $transaction->amount,
                $transaction->currency,
                ""
            );
            $payment->pay();       
        }
        return ["success" => false,"error" => "Source does not exist"];
    }
}