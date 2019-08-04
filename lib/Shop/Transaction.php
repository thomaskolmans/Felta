<?php
namespace lib\Shop;

use lib\Helpers\UUID;
use lib\Felta;

class Transaction{

    private $sql;

    public $id;
    public $transactionid;
    public $order;
    public $method;
    public $amount;
    public $currency;
    public $state;
    public $date;

    public function __construct($id,$transactionid,$order,$method,$amount,$currency,$state,$date){
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->transactionid = $transactionid;
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

    public static function create($transactionid,$order,$method,$state,$date){
        $id = UUID::generate(15);
        $source = Payment::getSource($transactionid);
        $amount = $source["amount"];
        $currency = $source["currency"];
        return new Transaction($id,$transactionid,$order,$method,$amount,$currency,$state,$date);
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
            $this->transactionid,
            $this->order,
            $this->method,
            $this->amount,
            $this->currency,
            $this->state,
            $this->date->format("Y-m-d H:i:s")
        ]);
    }

    public function delete(){
        $this->sql->delete("shop_transaction",["id" => $id]);
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

    public static function getLatest($from,$until){
        return Felta::getInstance()->getSQL()->query()->select()->from("shop_transaction")->where(["state" => 4])->orderBy("date")->desc()->limit($from, $until)->execute();
    }
}

?>