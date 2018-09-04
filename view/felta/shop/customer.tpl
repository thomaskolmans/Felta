<html>
    <head>
       <title>Felta | Customer</title>
       <link href="/felta/stylesheets/all.css" rel="stylesheet">
       <link href="/felta/js/quill/quill.snow.css" rel="stylesheet">
       <link rel="icon" href="/felta/images/black.png" type="image/png" />
       <link rel="stylesheet" href="/felta/fonts/font-awesome.min.css" />
       <link rel="stylesheet" href="/felta/fonts/font-awesome.css" />
       <script src="/felta/js/jquery-1.11.3.min.js"></script>
       <script src="/felta/js/Chart.min.js"></script>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body> 
        <?php
            use lib\Shop\Customer;
            use lib\Felta;
            
            if(isset($_GET["cid"]) && Felta::getInstance()->user->hasSession()){
                $cid = $_GET["cid"];
                if(Customer::exists($cid)){
                    $customer = Customer::get($cid);
                    $address = $customer->address;
                    ?>
                    <div class="main container no-top settings">
                        <h1>Customer <i>#<?php echo $cid; ?></i></h1>
                        <table class="bill big">
                            <tr>
                                <th>Name:</th>
                                <td><?php echo $customer->firstname." ".$customer->lastname; ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo $customer->email; ?></td>
                            </tr>
                            <tr><td colspan="2"><h2>Address</h2></td></tr>
                            <tr>
                                <th>Street:</th>
                                <td><?php echo $address->getStreet(); ?></td>
                            </tr>
                            <tr>
                                <th>Number:</th>
                                <td><?php echo $address->getNumber(); ?></td>
                            </tr>
                            <tr>
                                <th>Zipcode:</th>
                                <td><?php echo $address->getZipcode(); ?></td>
                            </tr>
                            <tr>
                                <th>City:</th>
                                <td><?php echo $address->getCity(); ?></td>
                            </tr>
                            <tr>
                                <th>Country:</th>
                                <td><?php echo $address->getCountry(); ?></td>
                            </tr>
                        </table>
                    </div>
                    <?php
                }
            }
        
        ?>
    </body>
</html>