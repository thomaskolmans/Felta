$(document).ready(function(){
    $(".listbutton").on("click",function(){
        if($(".left").hasClass("active")){
            $(".left").removeClass("active");
        }else{
            $(".left").addClass("active");
        }
    });
    $("#website").on("click",function(){
        document.location = 'http://'+ getDomain();
    });
    $("[href]").each(function() {
        if (this.href == window.location.href) {
            $(this).addClass("active");
        }
    });
});
function getDomain(){
    if(document.domain.length){
        var parts = document.domain.replace(/^(www\.)/,"").split('.');
        while(parts.length > 2){
            var subdomain = parts.shift();
        }
        var domain = parts.join('.');
        return domain.replace(/(^\.*)|(\.*$)/g, "");
    }
    return '';
}