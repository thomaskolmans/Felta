<?php
namespace lib\Shop\Order;

use lib\Felta;
use lib\Post\Message;
use lib\Helpers\UUID;

class Order {

    private $sql;

    public $id;
    public $customer;
    public $orderStatus;
    public $promotion;
    public $date;

    private $shopitems = [];
    
    public function __construct($id,$customer,$orderStatus,$promotion,$date,$shopitems){
        $this->sql = Felta::getInstance()->getSQL();
        $this->id = $id;
        $this->customer = $customer;
        $this->orderStatus = $orderStatus;
        $this->shopitems = $shopitems;
        $this->promotion = $promotion;
        $this->date = $date;
    }

    public static function get($id){
        $sorder = Felta::getInstance()->getSQL()->select("*","shop_order",["id" => $id])[0];
        $sitems = Felta::getInstance()->getSQL()->select("*","shop_order_item",["oid" => $id]);
        $shopitems = [];
        foreach($sitems as $item){
            $shopitems[$item["iid"]] = $item["quantity"];
        }
        return new Order(
            $id,
            $sorder["customer"],
            $sorder["orderStatus"],
            null,
            new \DateTime($sorder["order"]),
            $shopitems
        );
    }

    public static function exists($id){
        return Felta::getInstance()->getSQL()->exists("shop_order",["id" => $id]);
    }

    public static function createFromShoppingcart($cart,$customer){
        $items = $cart->pull()->getItems();
        return Order::create($customer,orderStatus::ACTIVE,null,$items);
    }

    public static function create($customer,$orderStatus,$promotion,$shopitems){
        $id = UUID::generate(8);
        $date = new \DateTime();
        return new Order($id,$customer,$orderStatus,$promotion,$date,$shopitems);
    }

    public static function getLatest($from,$until){
        return Felta::getInstance()->getSQL()->query()->select()->from("shop_order")->where("orderStatus", 1)->orderBy("order")->desc()->limit($from, $until)->execute();
    }

    public function save(){
        $this->sql->insert("shop_order",[
            $this->id,
            $this->customer,
            $this->orderStatus,
            $this->promotion,
            $this->date->format("Y-m-d H:i:s")
        ]);
        foreach($this->shopitems as $item => $quantity){
            $uid = UUID::generate(20);
            $this->sql->insert("shop_order_item",[
                $uid,
                $this->id,
                $item,
                $quantity
            ]);
        }
    }

    public function paid(){
        $message = new Message();
        $url = Felta::getInstance()->settings->get("website_url")."/felta/shop/order/".$this->id;
        $message->put("You've recieved a new order", "Yes! You've recieved a new order from your webshop. It has been succesfully paid.", $url);
        $this->orderStatus = orderStatus::PAID;
        $this->update();
    }

    public function update(){
        $this->sql->update("customer","shop_order",["id" => $this->id],$this->customer);
        $this->sql->update("orderStatus","shop_order",["id" => $this->id],$this->orderStatus);
        $this->sql->update("promotion","shop_order",["id" => $this->id],$this->promotion);
        $this->sql->update("order","shop_order",["id" => $this->id],$this->date->format("Y-m-d H:i:s"));
    }


    public function pay($method,$currency){
        $amount = $this->getTotalAmount();
    }

    public function getSubTotal(){
        $amount = 0;
        $settings = Shop::getInstance()->getSettings();
        if(boolval($settings["exclbtw"])){
            foreach($this->shopitems as $item => $quantity){
                $itemv = ShopItemVariant::get($item);
                $amount += intval($itemv->getPrice()) * $quantity;
            }
        } else {
            foreach($this->shopitems as $item => $quantity){
                $itemv = ShopItemVariant::get($item);
                $amount += intval($itemv->getPrice()) * $quantity;
            }
            $amount -= $this->getBtw($amount, true); 
        }

        return $amount;
    }

    public function getTotalAmount(){
        $amount = 0;
        $settings = Shop::getInstance()->getSettings();
        foreach($this->shopitems as $item => $quantity){
            $itemv = ShopItemVariant::get($item);
            $amount += intval($itemv->getPrice()) * $quantity;
        }
        if(boolval($settings["shipping"]) && !boolval($settings["freeshipping"])){
            $amount += $this->getShippingCost();
        }
        if(boolval($settings["exclbtw"])){
            $amount += $this->getBtw($amount, true);
        }
        return $amount;
    }

    public function getBtw($amount,$excl = false){
        $exclBtw = boolval(Shop::getInstance()->getSettings()["exclbtw"]);
        if($excl || !$exclBtw){
            return $amount - Shop::doubleToInt(round(Shop::intToDouble($amount) / ((Shop::getInstance()->getSettings()["btw"] / 100) + 1), 2));
        }else {
            return Shop::doubleToInt(round(Shop::intToDouble($amount) * (Shop::getInstance()->getSettings()["btw"] / 100),2));
        }
    }

    public function getShippingCost(){
        $items = count($this->shopitems);
        $settings = Shop::getInstance()->getShipping();
        $price = $settings["amount"];
        $ipp = $settings["ipp"];

        $amount = $price;
        $counter = 0;
        foreach($this->shopitems as $item => $quantity){
            $counter += $quantity;
            if($counter > $ipp){
                $amount += $price;
                $counter -= $ipp;
            }
        }
        return $amount;
    }

    public function toSource($method,$currency,$return_url,$other = array()){
        $default = array(
            'type' => $method,
            'amount' => $this->getTotalAmount(),
            'currency' => $currency,
            'redirect' => array('return_url' => $return_url)
        );
        $total = array_merge($default,$other);
        $source = \Stripe\Source::create($total);
        return $source;
    }

    public function toPaypal(){
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        $item1 = new Item();
        $item1->setName('Ground Coffee 40 oz')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setSku("123123")
            ->setPrice(7.5);
        $item2 = new Item();
        $item2->setName('Granola bars')
            ->setCurrency('USD')
            ->setQuantity(5)
            ->setSku("321321")
            ->setPrice(2);

        $itemList = new ItemList();
        $itemList->setItems(array($item1, $item2));

        $details = new Details();
        $details->setShipping(1.2)
            ->setTax(1.3)
            ->setSubtotal(17.50);

        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal(20)
            ->setDetails($details);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());

        $baseUrl = getBaseUrl();
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("$baseUrl/return/paypal")
            ->setCancelUrl("$baseUrl/return/paypal");

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        $request = clone $payment;

        try {
            $payment->create($apiContext);
        } catch (Exception $ex) {
        }
        $approvalUrl = $payment->getApprovalLink();
    }

    
    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
        return $this;
    }
    public function getCustomer(){
        return $this->customer;
    }
    public function setCustomer($customer){
        $this->customer = $customer;
        return $this;
    }
    public function getorderStatus(){
        return $this->orderStatus;
    }
    public function setorderStatus($orderStatus){
        $this->orderStatus = $orderStatus;
        return $this;
    }
    public function addItem(array $item){
        $shopitems[] = $item;
    }

    public function removeItem($id){
        unset($shopitems[$id]);
    }

    public function getItems(){
        return $this->shoptitems;
    }
    public function getDate(){
        return $this->date;
    }
    public function setDate($date){
        $this->date = $date;
        return $this;
    }
    public function getShopItems(){
        return $this->shopitems;
    }
    public function setShopItems($shopitems){
        $this->shopitems = $shopitems;
        return $this;
    }
}
?>