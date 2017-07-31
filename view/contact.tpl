<!DOCTYPE html>
<html>
<head>
    <title>Contact</title>
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="stylesheet" href="fonts/font-awesome.min.css" />
    <link rel="stylesheet" href="fonts/font-awesome.css" />
    <link rel="icon" href="images/berrie_logo2.png" type="image/png"></link>
    <script src="jquery-1.11.3.min.js"></script>
    <script src="main.js"> </script>
    <script src="contact.js"></script>
</head>
<body>
    <div class="container">
        <include>parts/header.tpl</include>
        <include>parts/navbar.tpl</include>
        <div class="item">
            <edit><h2 edit="contact_header"> Contact </h2></edit>
            <div class="contact">
                <form method='post' id="form">
                    <div class="input-group">
                        <input type="text" name="email" id="email">
                        <label> Email </label>
                    </div>
                    <div class="input-group">
                        <input type="text" name="subject" id="subject">
                        <label> Onderwerp </label>
                    </div>
                    <div class="input-group">
                        <textarea name="message" id="message"></textarea>
                        <label> Message </label>
                    </div>
                    <input type="submit" name="contact">
                </form>
            </div>
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