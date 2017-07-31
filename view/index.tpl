<!DOCTYPE html>
<html>
<head>
    <title>Tropator</title>
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="stylesheet" href="fonts/font-awesome.min.css" />
    <link rel="stylesheet" href="fonts/font-awesome.css" />
    <link rel="icon" href="images/berrie_logo2.png" type="image/png"></link>
    <script src="jquery-1.11.3.min.js"></script>
    <script src="main.js"> </script>
    <script>

    </script>
</head>
<body>
    <div class="container">
        <include>parts/header.tpl</include>
        <include>parts/navbar.tpl</include>
        <div class="item">
            <edit><h2 edit="main_header1"> A header </h2></edit>
            <edit><h3 edit="main_header2"> A subheader </h3></edit>
            <edit><div class="text" edit="home_main">
                <h1> An example </h1>
Ipsam ipsam consectetur alias voluptatibus consectetur magnam. Dolores autem dolor repellendus aliquid. Et sit vel ut alias totam eos illo. Esse eaque dignissimos exercitationem illum quia odio possimus enim. Quasi nesciunt tempora commodi quisquam possimus.
Sed mollitia consequatur sequi similique. Et eveniet quia id dicta. Minus maxime quam inventore praesentium temporibus blanditiis. A minus id sit aut.<br><br>
Libero voluptatem sit dolorum deleniti minima rerum. A ut aut qui maiores. Ex qui dolorem autem occaecati vero.
Fugit quam adipisci deleniti ut vitae nemo. Ut cumque perspiciatis ea neque dicta hic ipsam illo. Sit nihil tempore soluta atque voluptate. Ullam adipisci alias voluptas quis. Incidunt nesciunt libero maxime. Consequatur quibusdam voluptates ut.
Blanditiis quam quae veritatis qui quo autem. Ratione accusamus incidunt voluptate eligendi. Dolorum rerum voluptas culpa nobis. Nihil accusamus voluptas id. Voluptatem minima eum alias sit quis qui minus. Mollitia voluptate dolorem omnis rem.
            </div></edit>
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