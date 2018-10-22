<html>
    <head>
       <title>Felta | Transaction</title>
       <link href="/felta/stylesheets/shop.css" rel="stylesheet">
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
            use lib\Shop\Transaction;
            use lib\Shop\Shop;
            use lib\Felta;
            
            if(isset($_GET["tid"])){
                $tid = $_GET["tid"];
                if(Transaction::exists($tid)){
                    $transaction = Transaction::get($tid);
                    ?>
                    <div class="main container no-top settings">
                        <h1>Transaction <i>#<?php echo $tid; ?></i></h1>
                        <table class="bill big">
                            <tr>
                                <th>Amount:</th>
                                <td>€<?php echo Shop::intToDouble($transaction->amount); ?></td>
                            </tr>
                            <tr>
                                <th>Order:</th>
                                <td><?php echo '<a href="/felta/shop/order/'.$transaction->order.'">#'.$transaction->order; ?></a></td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td><?php echo $transaction->date->format("M d Y | H:i"); ?></td>
                            </tr>
                            <tr>
                                <th>Method:</th>
                                <td><?php echo $transaction->method; ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td><?php 
                                    switch($transaction->state){
                                        case 0: echo "Active"; break;
                                        case 1: echo "In progress"; break;
                                        case 2: echo "Failed"; break;
                                        case 3: echo "Aborted"; break;
                                        case 4: echo "Successfull"; break;
                                    } 
                                    ?></td>
                            </tr>
                        </table>
                    </div>
                    <?php
                }
            }
        
        ?>
    </body>
</html>