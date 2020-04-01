<?php
namespace lib\controllers;

use lib\Felta;
use lib\helpers\UUID;
use lib\filesystem\File;

class FeltaController {

	public static function GET_STATUS(){
		$felta = Felta::getInstance();
		echo $felta->getStatus();
	}

	public static function SET_STATUS(){
		$felta = Felta::getInstance();
		$online = $_POST["online"];
		$felta->setStatus($online);
		echo json_encode(["success" => "Status has successfully changed"]);
	}

	public static function GET_WEBSITE_URL(){
		$felta = Felta::getInstance();
		echo json_encode(['website_url' => $felta->settings->get('website_url')]);
	}

	public static function ADD_USER(){
		$user = Felta::getInstance()->user;
		$user->createWithPassword($_POST["username"], $_POST["password"]);
		echo json_encode(["success" => "Successfully added user"]);
	}

	public static function UPLOAD_IMAGE(){
        $uid = UUID::generate(20);
        $file =  new File($_FILES["picture"],null);
        $file->setExtension("png");
        $file->setName($uid);
        $file->upload(null);
        echo json_encode(["success" => "Image has been successfully added", "url" => $file->getRelativeDest(),"uid"=> $uid]);
    }
}

?>