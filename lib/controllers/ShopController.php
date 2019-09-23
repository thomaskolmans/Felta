<?php
namespace lib\controllers;

use lib\Felta;

use lib\shop\Shop;
use lib\shop\Shoppingcart;
use lib\shop\order\Order;
use lib\shop\product\Product;
use lib\shop\product\ProductVariant;
use lib\shop\product\Attribute;
use lib\shop\order\Customer;
use lib\shop\Transaction;
use lib\shop\TransactionState;
use lib\shop\Payment;

use lib\helpers\UUID;
use lib\filesystem\File;
use lib\helpers\Input;

class ShopController {

	public static function PUBLIC_KEY(){

	}

    /**
     * Shop items
     */
	public static function ADD_ITEM(){
        $name = Input::clean("name");
        $slug = Input::clean("slug");
        $category = Input::clean("category");
        $shortDescription = Input::clean("short_description");
		$description = Input::clean("description");
		$image = null;
		$product = Product::create($name, $slug, $category, $shortDescription, $description, $image, true);

        $productVariants = [];
        $variants = $_POST["variants"];

        foreach($variants as $variant) {
            $variantId = UUID::generate(20);
            $images = [];
            if (array_key_exists("images", $variant)) {
                $images = $variant["images"];
            }

            $attributes = [];
            if (array_key_exists("attributes", $variant)) {
                foreach($variant["attributes"] as $attribute) {
                    $attributes[] = Attribute::create($variantId, $attribute["name"], $attribute["value"]);
                }
            }
 
            $productVariant = new ProductVariant(
                $variantId,
                $product->getId(),
                $variant["variant_name"],
                str_replace([",","."],["",""], $variant["amount"]),
                $variant["currency"],
                $variant["sizeWidth"],
                $variant["sizeHeight"],
                $variant["sizeDepth"],
                $variant["weight"],
                $images,
                $attributes,
                $variant["quantity"],
                []
            );
            $productVariants[] = $productVariant;
        }

		$product->setVariants($productVariants);
        $product->save();
        echo json_encode(["success" => "Item has succesfully added"]);
        Header("location: /felta/shop/products");
	}

	public static function UPDATE_ITEM(){
        $id = $_POST["id"];
        $product = Product::get($id);

        $name = Input::clean("name");
        $slug = Input::clean("slug");
        $category = Input::value("category");
        $shortDescription = Input::clean("short_description");
		$description = Input::clean("description");

        $product->setName($name);
        $product->setSlug($slug);
        $product->setCategory($category);
        $product->setShortDescription($shortDescription);
        $product->setDescription($description);

        $productVariants = [];
        $variants = $_POST["variants"];

        foreach($variants as $variant) {
            $variantId = UUID::generate(20);
            $images = [];
            if (array_key_exists("images", $variant)) {
                $images = $variant["images"];
            }

            $attributes = [];
            if (array_key_exists("attributes", $variant)) {
                foreach($variant["attributes"] as $attribute) {
                    $attributes[] = Attribute::create($variantId, $attribute["name"], $attribute["value"]);
                }
            }
 
            $productVariant = new ProductVariant(
                $variantId,
                $product->getId(),
                $variant["variant_name"],
                str_replace([",","."],["",""], $variant["amount"]),
                $variant["currency"],
                $variant["sizeWidth"],
                $variant["sizeHeight"],
                $variant["sizeDepth"],
                $variant["weight"],
                $images,
                $attributes,
                $variant["quantity"],
                []
            );
            $productVariants[] = $productVariant;
        }

        $product->setVariants($productVariants);
        $product->update();
        echo json_encode(["success" => "Item has succesfully updated"]);
        Header("location: /felta/shop/products");
	}

	public static function DELETE_ITEM($id){
        $product = Product::get($id);
        $product->delete();
        echo json_encode(["success" => "Item has succesfully deleted"]);
        Header("Location: /felta/shop/products");
	}

    /**
     * Images
     */
    public static function UPLOAD_IMAGE(){
        $uid = UUID::generate(20);
        $file =  new File($_FILES["picture"],null);
        $file->setExtension("png");
        $file->setName($uid);
        $file->upload(null);
        echo json_encode(["success" => "Image has been succesfully added", "url" => $file->getRelativeDest(),"uid"=> $uid]);
    }

    public static function DELETE_IMAGE(){
        $url = $_POST["url"];
        Shop::deleteImage($url);
        echo json_encode(["success" => "Image has been succesfully deleted"]);
    }

    /**
     * Settings
     */

    /**
     * Category
     */
    public static function GET_CATEGORIES(){
        echo json_encode(Shop::getInstance()->getCategories());
    }

	public static function ADD_CATEGORY(){
        Shop::getInstance()->addCategory($_POST["category"]);
        echo json_encode(["success" => "Category has succesfully been added"]);
        header("Location: /felta/shop/categories");
    }
    
    public static function DELETE_CATEGORY(){
        Shop::getInstance()->deleteCategory($_POST["category"]);
        echo json_encode(["success" => "Category has succesfully deleted"]);
    }
    
    /**
     * Promotions
     */
    public static function UPDATE_ADDRESS(){
        Shop::getInstance()->updateShopAddress(
            htmlspecialchars($_POST["street"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["number"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["zipcode"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["city"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["country"], ENT_QUOTES, 'UTF-8')
        );
    }

    public static function UPDATE_SETTINGS(){
        Shop::getInstance()->updateSettings(
            htmlspecialchars($_POST["url"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["btw"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars(boolval($_POST["exclbtw"]), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars(boolval($_POST["shipping"]), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars(boolval($_POST["freeshipping"]), ENT_QUOTES, 'UTF-8')
        );
    }

    public static function UPDATE_SHIPPING(){
        Shop::getInstance()->updateShipping(
            Shop::doubleToInt($_POST["amount"]),
            htmlspecialchars($_POST["ipp"], ENT_QUOTES, 'UTF-8')
        );
    }

    /**
     * Shoppingcart
     */
    public static function CREATE_SHOPPINGCART(){
        echo json_encode(Shoppingcart::create()->getId());
    }

    public static function GET_SHOPPINGCART() {
        $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
        $shoppingcart->pull();
        echo json_encode(["items" => $shoppingcart->getItems()]);
    }

    public static function ADD_ITEM_SHOPPINGCART(){
        $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
        $shoppingcart->set($_POST["item"], $_POST["quantity"]);
        $shoppingcart->save();
        echo json_encode(
            [
            "action" => "add",
            "product" => ProductVariant::get($_POST["item"])->expose(),
            "quantity" => $_POST["quantity"],
            "amount" => $shoppingcart->getTotalAmount()
            ]
        );
    }

    public static function UPDATE_ITEM_SHOPPINGCART(){
        $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
        $shoppingcart->update($_POST["item"], $_POST["quantity"]);
        
        echo json_encode(
            [
            "action" => "update",
            "product" => ProductVariant::get($_POST["item"])->expose(),
            "quantity" => $_POST["quantity"],
            "amount" => $shoppingcart->getTotalAmount()
            ]
        );
    }

    public static function DELETE_ITEM_SHOPPINGCART(){
        $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
        $shoppingcart->delete($_POST["item"]);
        echo json_encode(
            [
            "action" => "delete",
            "product" => ProductVariant::get($_POST["item"])->expose(), 
            "amount" => $shoppingcart->getTotalAmount()
            ]
        );
    }

    /**
     * Wishlist
     */
    public static function CREATE_WISHLIST(){
        echo json_encode(Shoppingcart::create()->getId());
    }

    public static function ADD_ITEM_WISHLIST(){
        $shoppingcart = new Shoppingcart($_COOKIE["WID"]);
        $shoppingcart->set($_POST["item"],$_POST["quantity"]);
        $shoppingcart->save();
        echo json_encode(["amount" => $shoppingcart->getTotalAmount()]);
    }

    public static function UPDATE_ITEM_WISHLIST(){
        $shoppingcart = new Shoppingcart($_COOKIE["WID"]);
        $shoppingcart->update($_POST["item"],$_POST["quantity"]);
        echo json_encode(["amount" => $shoppingcart->getTotalAmount()]);
    }

    public static function DELETE_ITEM_WISHLIST(){
        $shoppingcart = new Shoppingcart($_COOKIE["WID"]);
        $shoppingcart->delete($_POST["item"]);
        echo json_encode(["amount" => $shoppingcart->getTotalAmount()]);
    }

    /**
     * Create sources
     */
    public static function CREATE_SOURCE_IDEAL(){
        $order = Order::get($_POST["oid"]);
        $url = Felta::getInstance()->settings->get("website_url")."/felta/shop/return";
        echo json_encode($order->toSource("ideal", "eur", $url, array("ideal" => array("bank" => $_POST["bank"]))));
    }

    /**
     * Transaction
     */
    public static function CREATE_TRANSACTION(){
        $customer = Customer::create(
            htmlspecialchars($_POST["firstname"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["lastname"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["email"],ENT_QUOTES,'UTF-8'),
            htmlspecialchars($_POST["street"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["number"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["zipcode"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["city"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["country"], ENT_QUOTES, 'UTF-8'),
            false,
            false,
            false
        );
        $customer->save();
        $order = Order::createFromShoppingcart(new Shoppingcart($_COOKIE["SCID"]),$customer->id);
        $order->save();
        header("Location: /pay/".$order->getId());
    }

    public static function MOLLIE_TRANSACTION() {
        $order = Order::get($_POST["oid"]);
        $tid = UUID::generate(15);
        $payment = Shop::getInstance()->getMollie()->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => Shop::intToDoubleSeperator($order->getTotalAmount(), ".")
            ],
            "description" => "A cleanheating payment",
            "redirectUrl" => Felta::getInstance()->settings->get("website_url")."/shop/return/". $tid,
            "webhookUrl"  => Felta::getInstance()->settings->get("website_url")."/felta/shop/mollie/". $tid,
            "method" => $_POST["method"]
        ]);

        $transaction = new Transaction(
            $tid,
            htmlspecialchars($payment->id, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["oid"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars("ideal", ENT_QUOTES, 'UTF-8'),
            $order->getTotalAmount(),
            "EUR",              
            htmlspecialchars(0, ENT_QUOTES, 'UTF-8'),
            new \DateTime()
        );
        $transaction->save();
        echo json_encode(["transaction" => $transaction, "checkoutUrl" => $payment->getCheckoutUrl()]);
    }

    public static function CHECK_MOLLIE_PAYMENT(){
        $transaction = Transaction::get($_POST["tid"]);
        if ($transaction != null) {
            $payment = Shop::getInstance()->getMollie()->payments->get($transaction->transactionid);
            if ($payment->isPaid() && $transaction->state != TransactionState::COMMITTED){
                //Set order to paid
                $order = Order::get($transaction->order);
                $order->paid();

                //Set transaction to paid
                $transaction->state = TransactionState::COMMITTED;
                $transaction->update();

                //Clear shoppingcart
                $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
                $shoppingcart->destroy();
                echo json_encode(["success" => true]);
                return true;
            }
        }
        echo json_encode(["success" => false]);
    }

	public static function TRANSACTION(){
        $transaction = Transaction::create(
            htmlspecialchars($_POST["sid"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["oid"], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($_POST["method"], ENT_QUOTES, 'UTF-8'),                
            htmlspecialchars($_POST["state"], ENT_QUOTES, 'UTF-8'),
            new \DateTime()
        );
        $transaction->save();
        echo json_encode($transaction);
    }
    
    public static function WEEK_TRANSACTIONS(){
        echo json_encode(Transaction::getWeekTransactions());
    }

    /** Charge */
	public static function CHARGE(){
        $source = Payment::getSource($_POST["source"]);
        $orderid = $_POST["oid"];
        $order = Order::get($orderid);
        $amount = $order->getTotalAmount();
        $method = htmlspecialchars($_POST["method"], ENT_QUOTES, 'UTF-8');
        $currency = "eur";
        $payment = new Payment($orderid,$source,$method,$amount,$currency,"");
        $payment->pay();
        echo json_encode($payment);

	}

	public static function CHARGE_SOURCE(){
		echo json_encode(Payment::chargeFromSource($_POST["source"]));
	}

	public static function WEBHOOK(){
        $input = @file_get_contents("php://input");
        $json = json_decode($input,true);
        echo json_encode(Payment::chargeFromSource($json["data"]["object"]));
    }
    
    public static function MOLLIE_WEBHOOK($tid){
        $transaction = Transaction::get($tid);
        if ($transaction != null) {
            $payment = Shop::getInstance()->getMollie()->payments->get($transaction->transactionid);
            if ($payment->isPaid() && $transaction->state != TransactionState::COMMITTED){
                //Set order to paid
                $order = Order::get($transaction->order);
                $order->paid();

                //Set transaction to paid
                $transaction->state = TransactionState::COMMITTED;
                $transaction->update();

                //Clear shoppingcart
                $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
                $shoppingcart->destroy();
                echo json_encode(["success" => true]);
                return true;
            }
        }
        echo json_encode(["success" => false]);
    }
}
?>