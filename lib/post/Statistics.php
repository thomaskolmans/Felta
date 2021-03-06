<?php

namespace lib\post;

use lib\Felta;

class Statistics
{

    public $sql;

    public function __construct()
    {
        $this->sql = Felta::getInstance()->sql;
        // $this->add();
    }

    public function add()
    {
        $now = new \DateTime();
        $ip = $_SERVER["REMOTE_ADDR"];
        if (!isset($_SESSION["visit_session"])) {
            $this->sql->insert("visitors_total", [0, $ip, $now->format("Y-m-d H:i:s")]);
            $_SESSION["visit_session"] = true;
        }
        if (!$this->sql->exists("visitors_unique", ["ip" => $ip])) {
            $this->sql->insert("visitors_unique", [0, $ip, $now->format("Y-m-d H:i:s")]);
        }
        $details = json_decode(file_get_contents("https://extreme-ip-lookup.com/json/"));
        if ($details->lon != 0) {
            $this->sql->insert("visitors_total_location", [
                0,
                $ip,
                $details->lat,
                $details->lon,
                $now->format("Y-m-d H:i:s")
            ]);
            if (!$this->sql->exists("visitors_unique_location", ["ip" => $ip])) {
                $this->sql->insert("visitors_unique_location", [
                    0,
                    $ip,
                    $details->lat,
                    $details->lon,
                    $now->format("Y-m-d H:i:s")
                ]);
            }
        }
    }

    public function getUniqueVisitors()
    {
        $this->sql->count("visitors_unique", []);
    }

    public function getTotalVisitors()
    {
        return $this->sql->count("visit_total", []);
    }
}
