<?php
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];
    if(mail("berrie@kolmans.com", $subject, $message,"From: ".$email)){
        echo "succes";
    }else{
        echo "failure";
    }
?>