<?php
namespace lib\shop;

use lib\helpers\UUID;
use lib\Felta;

class Transaction{

    private $sql;

    public $id;
    public $transactionId;
    public $order;
    public $method;
    public $amount;
    public $currency;
    public $state;
    public $date;

    public function __construct($id,$transactionId,$order,$method,$amount,$currency,$state,$date){
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->transactionId = $transactionId;
        $this->order = $order;
        $this->method = $method;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->state = $state;
        $this->date = $date;
    }

    public static function get($id){
        $result = Felta::getInstance()->getSQL()->select("*","shop_transaction",["id" => $id])[0];
        return new Transaction(
            $id,
            $result["transactionid"],
            $result["order"],
            $result["method"],
            $result["amount"],
            $result["currency"],
            $result["state"],
            new \DateTime($result["date"])
        );
    }

    public static function getFromSource($source){
        if(Felta::getInstance()->getSQL()->exists("shop_transaction",["transactionid" => $source["id"]])){
            $result = Felta::getInstance()->getSQL()->select("*","shop_transaction",["transactionid" => $source["id"]])[0];
            return new Transaction(
                $result["id"],
                $result["transactionid"],
                $result["order"],
                $result["method"],
                $result["amount"],
                $result["currency"],
                $result["state"],
                new \DateTime($result["date"])
            ); 
        }
        return null;

    }

    public static function create($transactionId,$order,$method,$state,$date){
        $id = UUID::generate(15);
        $source = Payment::getSource($transactionId);
        $amount = $source["amount"];
        $currency = $source["currency"];
        return new Transaction($id,$transactionId,$order,$method,$amount,$currency,$state,$date);
    }

    public static function exists($id){
        return Felta::getInstance()->getSQL()->exists("shop_transaction",["id" => $id]);
    }

    public static function getWeekTransactions(){
        return Felta::getInstance()->getSQL()->execute("SELECT SUM(amount),date FROM shop_transaction WHERE date >= NOW() - INTERVAL 7 DAY GROUP BY day(date) ORDER BY date");
    }

    public function save(){
        $this->sql->insert("shop_transaction",[
            $this->id,
            $this->transactionId,
            $this->order,
            $this->method,
            $this->amount,
            $this->currency,
            $this->state,
            $this->date->format("Y-m-d H:i:s")
        ]);
    }

    public function delete(){
        $this->sql->delete("shop_transaction",["id" => $this->id]);
    }

    public function update(){
        $this->sql->update("transactionid","shop_transaction",["id" => $this->id],$this->transactionid);
        $this->sql->update("order","shop_transaction",["id" => $this->id],$this->order);
        $this->sql->update("method","shop_transaction",["id" => $this->id],$this->method);
        $this->sql->update("amount","shop_transaction",["id" => $this->id],$this->amount);
        $this->sql->update("currency","shop_transaction",["id" => $this->id],$this->currency);
        $this->sql->update("state","shop_transaction",["id" => $this->id],$this->state);
        $this->sql->update("date","shop_transaction",["id" => $this->id],$this->date->format("Y-m-d H:i:s"));
    }

    public function expose(){
        $exposed = get_object_vars($this);
        unset($exposed["sql"]);
        return $exposed;
    }

    public static function getLatest($from,$until){
        return Felta::getInstance()->getSQL()->query()->select()->from("shop_transaction")->where(["state" => 4])->orderBy("date")->desc()->limit($from, $until)->execute();
    }

	public function getId(){
		return $this->id;
	}
	
	public function setId($id){
		$this->id = $id;
		return $this;
	}
	
	public function getTransactionId(){
		return $this->transactionId;
	}
	
	public function setTransactionId($transactionId){
		$this->transactionId = $transactionId;
		return $this;
	}

	public function getOrder(){
		return $this->order;
	}
	
	public function setOrder($order){
		$this->order = $order;
		return $this;
	}

	public function getMethod(){
		return $this->method;
	}
	
	public function setMethod($method){
		$this->method = $method;
		return $this;
	}
	
	public function getAmount(){
		return $this->amount;
	}
	
	public function setAmount($amount){
		$this->amount = $amount;
		return $this;
	}
	
	public function getCurrency(){
		return $this->currency;
	}
	
	public function setCurrency($currency){
		$this->currency = $currency;
		return $this;
	}

	public function getState(){
		return $this->state;
	}
	
	public function setState($state){
		$this->state = $state;
		return $this;
	}

	public function getDate(){
		return $this->date;
	}
	
	public function setDate($date){
		$this->date = $date;
		return $this;
	}
	
}
?>