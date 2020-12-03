<?php
namespace lib\shop\order;

use \DateTime;
use \Exception;
use lib\Felta;
use lib\shop\Shop;
use lib\shop\Promotion;
use lib\shop\product\ProductVariant;
use lib\post\Message;
use lib\helpers\UUID;
use lib\helpers\Email;

class Order {

    private $sql;

    public $id;
    public $customer;
    public $orderStatus;
    public $promotion;
    public $date;

    private $products = [];
    
    public function __construct($id,$customer,$orderStatus,$promotion,$date,$products){
        $this->sql = Felta::getInstance()->getSQL();
        $this->id = $id;
        $this->customer = $customer;
        $this->orderStatus = $orderStatus;
        $this->products = $products;
        $this->promotion = $promotion;
        $this->date = $date;
    }

    public static function get($id){
        $order = Felta::getInstance()->getSQL()->select("*","shop_order",["id" => $id])[0];
        $orderProducts = Felta::getInstance()->getSQL()->select("*","shop_order_product",["oid" => $id]);
        $products = [];
        foreach($orderProducts as $orderProduct){
            $products[$orderProduct["iid"]] = $orderProduct["quantity"];
        }
        return new Order(
            $id,
            $order["customer"],
            $order["orderstatus"],
            Promotion::get($order["promotion"]),
            new DateTime($order["order"]),
            $products
        );
    }

    public static function getFromTransaction($id){
        $transaction = Felta::getInstance()->getSQL()->select("*","shop_transaction",["id" => $id])[0];
        if($transaction == null) return new Order("", "", "" , "" , new DateTime(), []);
        return Order::get($transaction["order"]);
    }


    public static function exists($id){
        return Felta::getInstance()->getSQL()->exists("shop_order",["id" => $id]);
    }

    public static function createFromShoppingcart($cart,$customer){
        $items = $cart->pull()->getItems();
        return Order::create($customer,OrderStatus::ACTIVE,null,$items);
    }

    public static function create($customer,$orderStatus,$promotion,$products){
        $id = UUID::generate(8);
        $date = new \DateTime();
        return new Order($id,$customer,$orderStatus,$promotion,$date,$products);
    }
    

    public static function getLatest($from,$until){
        return Felta::getInstance()->getSQL()
            ->query()
            ->select()
            ->from("shop_order")
            ->where("orderstatus", 1)
            ->orderBy("order")
            ->desc()
            ->limit($from, $until)
            ->execute();
    }

    public function save(){
        $this->sql->insert("shop_order",[
            $this->id,
            $this->customer,
            $this->orderStatus,
            $this->promotion,
            $this->date->format("Y-m-d H:i:s")
        ]);
        foreach($this->products as $item => $quantity){
            $uid = UUID::generate(20);
            $this->sql->insert("shop_order_product",[
                $uid,
                $this->id,
                $item,
                $quantity
            ]);
        }
    }

    public function paid(){
        // Message to store owner
        $message = new Message();
        $url = Felta::getInstance()->settings->get("website_url")."/felta/shop/order/".$this->id;
        $message->put("You've received a new order", "Yes! You've received a new order from your webshop. It has been successfully paid.", $url);

        // Message to customer
        $this->customer = Customer::get($this->customer);
        $this->orderConfirmation(
            "Uw bestelling is compleet", 
            "Uw bestelling komt er zo spoedig mogelijk aan. Mocht u nog vragen hebben, neemt u gerust contact op met ons. <br><br> Uw order nummer: ".$this->id
        );

        $this->customer = $this->customer->id;
        $this->orderStatus = OrderStatus::PAID;
        $this->update();
    }

    private function orderConfirmation($title, $message) {
        $url = Felta::getInstance()->getConfig("website_url")."/order/".$this->id;
        $email = new Email();
        $email->html(true);
        $email->setSMTP();
        $email->setTo($this->customer->email);
        $email->setFrom(Felta::getConfig("smtp")["username"]);
        $email->setSubject(Felta::getInstance()->getConfig("website_name")." bestelling");
        $email->setMessage(str_replace(["{title}","{message}","{url}"], [$title,$message,$url], $email->load("emails/shop/thankyou.html")));
        $email->send();
    }

    public function update(){
        $this->sql->update("customer","shop_order",["id" => $this->id],$this->customer);
        $this->sql->update("orderstatus","shop_order",["id" => $this->id],$this->orderStatus);
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
            foreach($this->products as $item => $quantity){
                $itemv = ProductVariant::get($item);
                $amount += intval($itemv->getPrice()) * $quantity;
            }
        } else {
            foreach($this->products as $item => $quantity){
                $itemv = ProductVariant::get($item);
                $amount += intval($itemv->getPrice()) * $quantity;
            }
            $amount -= $this->getBtw($amount, true); 
        }

        return $amount;
    }

    public function getTotalAmount(){
        $amount = 0;
        $settings = Shop::getInstance()->getSettings();
        foreach($this->products as $item => $quantity){
            $itemv = ProductVariant::get($item);
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
        $items = count($this->products);
        $settings = Shop::getInstance()->getShipping();
        $price = $settings["amount"];
        $ipp = $settings["ipp"];

        $amount = $price;
        $counter = 0;
        foreach($this->products as $item => $quantity){
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
    
    public function getVariants(){
        foreach($this->products as $item => $quantity){
            $variant = ProductVariant::get($item);
            $variant->setQuantity($quantity);
            $this->variants[] = $variant;
        }
    }

    public function toPaypal(){

    }

    public function expose(){
        $this->getVariants();
        $exposed = get_object_vars($this);
        unset($exposed["sql"]);
        $exposed["variants"] = [];
        foreach($this->variants as $variant) {
            $exposed["variants"][] = $variant->expose();
        }
        return $exposed;
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
        $this->products[] = $item;
        return $this;
    }

    public function removeItem($id){
        unset($this->products[$id]);
        return $this;
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

    public function getProducts(){
        return $this->products;
    }

    public function setProducts($products){
        $this->products = $products;
        return $this;
    }
}
