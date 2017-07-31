<!DOCTYPE html>
<html>
<head>
    <title>Agenda</title>
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="stylesheet" href="fonts/font-awesome.min.css" />
    <link rel="stylesheet" href="fonts/font-awesome.css" />
    <link rel="icon" href="images/berrie_logo2.png" type="image/png"></link>
    <script src="jquery-1.11.3.min.js"></script>
    <script src="main.js"> </script>

</head>
<body>
    <div class="container">
        <include>parts/header.tpl</include>
        <include>parts/navbar.tpl</include>
        <div class="item" id="#agenda">
            <h2 class="center"> Agenda </h2>
            <?php
            use lib\Post\Agenda;

            $agenda = new Agenda();
            foreach($agenda->getAll() as $date){
			    $datetime = (new \Datetime($date['date']))->format("d-m-Y H:i");
                echo "<a href='/date/id/".$date["id"]."'>
                <div class='agenda'>
                    <div class='title'> ".$date['title']." </div>
                    <div class='date'>".$datetime."</div>
                    <div class='description'> ".$date['description']."</div>
                </div></a>";
            }
            ?>
        </div>
                <div class="profile">
            <edit class="image"><img edit="profile_image"/></edit>
            <h2> Berrie Kolmans </h2>
            <div class="story">
                <edit><p edit="short_me">Tell a little bit about yourself, short.</p></edit>
                <a href="/berrie"><button> Lees meer </button></a>
            </div>
        </div>
        <include>parts/footer.tpl</include>
</body>
</html>