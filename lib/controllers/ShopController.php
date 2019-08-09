<?php
namespace lib\controllers;

use lib\Felta;

use lib\shop\Shop;
use lib\shop\Shoppingcart;
use lib\shop\Promotion;
use lib\shop\order\Order;
use lib\shop\order\OrderStatus;
use lib\shop\product\Product;
use lib\shop\product\ProductVariant;
use lib\shop\order\Customer;
use lib\shop\order\CustomerAddress;
use lib\shop\Transaction;
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
        $catagory = Input::clean("category");
        $shortDescription = Input::clean("short_description");
		$description = Input::clean("description");
		$image = null;
		$product = Product::create($name, $slug, $catagory, $shortDescription, $description, $image, true);

        $productVariants = [];
        $variants = $_POST["variants"];

        foreach($variants as $variant) {
            $images = [];
            if (array_key_exists("images", $variant)) {
                $images = $variant["images"];
            }

            $attributes = [];
            if (array_key_exists("images", $variant)) {
                foreach($variant["attributes"] as $attribute) {
                    $attributes = Attribute::create($attribute["name"], $attribute["value"]);
                }
            }
 
            $productVariant = ProductVariant::create(
                $product->getId(),
                $variant["variant_name"],
                str_replace([",","."],["",""], $variant["amount"]),
                $variant["currency"],
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
	}

	public static function UPDATE_ITEM(){
        $id = $_POST["id"];
        $product = Product::get($id);

        $product->setName($_POST["name"]);
        $product->setCatagory($_POST["catagory"]);
        $product->setDescription($_POST["description"]);
        $product->update();

        $variants = 1;
        $ItemVariants = [];
        $i = 1;
        foreach($product->getVariants() as $variant){
            $amount = $_POST["amount".$i];
            $currency = $_POST["currency".$i];
            $variables = $_POST["variables".$i];
            $quantity = $_POST["quantity".$i];
            $images = isset($_POST["images"]) ? $_POST["images"] : [];
            $variant->setPrice(str_replace([",","."],["",""],$amount));
            $variant->setCurrency($currency);
            $variant->setImages($images);
            $variant->setQuantity($quantity);
            $variant->setVariables($variables);
            $variant->update();
            $i++;
        }
        $product->setVariants($ItemVariants);
        $product->update();
        echo json_encode(["success" => "Item has succesfully updated"]);
	}

	public static function DELETE_ITEM($id){
        $product = Product::get($id);
        $product->delete();
        echo json_encode(["success" => "Item has succesfully deleted"]);
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
        echo json_encode(Shop::getInstance()->getCatagories());
    }

	public static function ADD_CATEGORY(){
        Shop::getInstance()->addCatagory($_POST["catagory"]);
        echo json_encode(["success" => "Category has succesfully deleted"]);
    }
    
    public static function DELETE_CATEGORY(){
        Shop::getInstance()->deleteCatagory($_POST["catagory"]);
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

    public static function ADD_ITEM_SHOPPINGCART(){
        $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
        $shoppingcart->set($_POST["item"],$_POST["quantity"]);
        $shoppingcart->save();
        echo json_encode(["amount" => $shoppingcart->getTotalAmount()]);
    }

    public static function UPDATE_ITEM_SHOPPINGCART(){
        $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
        $shoppingcart->update($_POST["item"],$_POST["quantity"]);
        echo json_encode(["amount" => $shoppingcart->getTotalAmount()]);
    }

    public static function DELETE_ITEM_SHOPPINGCART(){
        $shoppingcart = new Shoppingcart($_COOKIE["SCID"]);
        $shoppingcart->delete($_POST["item"]);
        echo json_encode(["amount" => $shoppingcart->getTotalAmount()]);
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
        echo json_encode($order->toSource("ideal","eur",$url, array("ideal" => array("bank" => $_POST["bank"]))));
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
        header("Location: /felta/shop/pay/".$order->getId());
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
}
?>