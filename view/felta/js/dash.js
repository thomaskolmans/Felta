$(document).ready(function(){
    var tid = setTimeout(checkServer(),3000);
    $(".new-language").hide();
    $("#new_language").on('click',function(){
        $(".new-language").fadeIn();
    });
    $('.remove').on('click',function(){
        lang = $(this).prop("lang");
        $(this).parent().remove();
        $.ajax({
            url: "/felta/language/"+lang,
            type: "DELETE"
        });
    });
});
function checkServer(){
    ifServerOnline(function(){
        $("#website_is").removeClass("offline");
        $("#website_is").addClass("online");
        $("#website_status").text("online");
    },
    function (){
        $("#website_is").removeClass("online");
        $("#website_is").addClass("offline");
        $("#website_status").text("offline");
    });
    var tid = setTimeout(checkServer,2000);
}
function ifServerOnline(ifOnline, ifOffline){
    var last = document.getElementById("last");
    if(last != null){
        last.remove();
    }
    var img = document.body.appendChild(document.createElement("img"));
    img.onload = function()
    {
        ifOnline && ifOnline.constructor == Function && ifOnline();
    };
    img.onerror = function()
    {
        ifOffline && ifOffline.constructor == Function && ifOffline();
    };
    img.src = "http://"+getDomain()+"/view/felta/images/test.jpg";
    img.style.display = "none";
    img.id = "last";        
}

