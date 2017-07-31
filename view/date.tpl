<!DOCTYPE html>
<html>
<head>
    <title>Agenda</title>
    <link rel="stylesheet" type="text/css" href="/main.css">
    <link rel="stylesheet" href="/fonts/font-awesome.min.css" />
    <link rel="stylesheet" href="/fonts/font-awesome.css" />
    <link rel="icon" href="images/berrie_logo.png" type="image/png"></link>
    <script src="/jquery-1.11.3.min.js"></script>
    <script src="/main.js"> </script>
</head>
<body>
    <div class="container">
        <include>parts/header.tpl</include>
        <include>parts/navbar.tpl</include>
        <div class="item">
            <?php
            
            use lib\Modules\PostMe\Agenda;
            use lib\Api\Input;

            $agenda = new Agenda();
            if(!Input::get("id")){
                Redirect::to("/agenda");
            }
            
            echo 
            "<h2>". $agenda->getItem(Input::get("id"))['title']." </h2> 
            <h3>".$agenda->getItem(Input::get("id"))['description']." </h3>
            <div class='text'> <h1> <b> Datum: </b> </h1> ".$agenda->getItem(Input::get("id"))['date']." <br><br><hr>  ".$agenda->getItem(Input::get("id"))['content']." </div>";
            ?>
        </div>
        <div class="profile">
            <edit><img src="images/logo.png" edit="profile_image"/></edit>
            <h2> Berrie Kolmans </h2>
            <div class="story">
                <edit><p edit="short_me">Tell a little bit about yourself, short.</p></edit>
                <a href="/berrie"><button> Lees meer </button></a>
            </div>
        </div>
        <include>footer.tpl</include>
</body>
</html>