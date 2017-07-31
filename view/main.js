if(document.domain.length){
    var parts = document.domain.replace(/^(www\.)/,"").split('.');
    while(parts.length > 2){
        var subdomain = parts.shift();
    }
    var domain = parts.join('.');
    document.domain =  domain.replace(/(^\.*)|(\.*$)/g, "");
}
$(document).ready(function(){
    cycleImages(
        ".slider",
        10000,
        Array(
            "images/_MG_3378.jpg",
            "images/_MG_3391.jpg"
            ),
        Array(
            "",
            "",
            ""
    ));
    $(".back").on("click",function(){
        $('html,body').animate({
            scrollTop: 0
        }, 1000);
    });
    if($(window).width() <= 650){
        $("li").css("width",$(window).width());
    }
    $(window).on('resize', function(){
        if($(window).width() <= 650){
            $("li").css("width",$(window).width());
        }
    });
    $("#navbutton").click(function(){
        if($("ul").is(":visible")){
            $(".nav ul").fadeOut();
        }else{
            $("ul").fadeIn();
        }
    });
});

        
function cycleImages(wrapper, timeout, images, texts) {

    imagecount = 0;
    nextimage = 1;
    image = 1;
    $(wrapper).append("<div id='picture' style='display:none' />");
    $(wrapper).append("<div id='picture' style='display:none'/>");
    $("#picture").css("background", "url(" + images[imagecount] + ")  ");

    $("#picture").css("z-index", 3);
    $("#picture").show();
    $(wrapper + " p").text("" + texts[imagecount] );
    $("#picture").next().css("background", "url(" + images[nextimage] + ") ");
    $("#picture").next().css("z-index", 1);
    $("#picture").css("background-size","cover");
    $("#picture").css("-webkit-background-size","cover");
    $("#picture").css("-o-background-size","cover");
    $("#picture").css("-moz-background-size","cover");
    $("#picture").next().css("background-size","cover");
    $("#picture").next().css("-webkit-background-size","cover");
    $("#picture").next().css("-o-background-size","cover");
    $("#picture").next().css("-moz-background-size","cover");
    var tid = setInterval(mycode, timeout);
    
    function mycode() {
        if (imagecount == images.length) {
            imagecount = 0;
            nextimage = 1;
        }
        if(imagecount == images.length - 1){
            nextimage = 0;
        }
        
        if (image == 1) {
            $("#picture").next().fadeIn();
            $("#picture").fadeOut(1500);
            $("#picture").css("background", "url(" + images[imagecount] + ") ");
            $("#picture").next().css("background", "url(" + images[nextimage] + ")");
            $(wrapper + " p").text(texts[nextimage]);
            $("#picture").css("z-index", 3);
            $("#picture").next().css("z-index", 1);
            $("#picture").css("background-size","cover");
            $("#picture").css("-webkit-background-size","cover");
            $("#picture").css("-o-background-size","cover");
            $("#picture").css("-moz-background-size","cover");
            $("#picture").next().css("background-size","cover");
            $("#picture").next().css("-webkit-background-size","cover");
            $("#picture").next().css("-o-background-size","cover");
            $("#picture").next().css("-moz-background-size","cover");
            image = 2;
        } else {
            $("#picture").fadeIn();
            $("#picture").next().fadeOut(1500);
            $("#picture").next().css("background", "url(" + images[imagecount] + ")");
            $(wrapper + " p").text(texts[nextimage]);
            $("#picture").css("background", "url(" + images[nextimage] + ") ");
            $("#picture").css("z-index", 1);
            $("#picture").next().css("z-index", 3);
            $("#picture").css("background-size","cover");
            $("#picture").css("-webkit-background-size","cover");
            $("#picture").css("-o-background-size","cover");
            $("#picture").css("-moz-background-size","cover");
            $("#picture").next().css("background-size","cover");
            $("#picture").next().css("-webkit-background-size","cover");
            $("#picture").next().css("-o-background-size","cover");
            $("#picture").next().css("-moz-background-size","cover");
            image = 1;
        }
        
        nextimage++;
        imagecount++;
    }
}