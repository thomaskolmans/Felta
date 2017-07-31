$(document).ready(function(){
    $("#email").focusout(function(){
        val = $(this).val();
        if(!validEmail(val)){
            error($(this), "Invalid email");
            return false;
        }
        pos($(this));
    });
    $("#subject").focusout(function(){
        val = $(this).val();
        if(val == ""){
            error($(this),"Fill in the subject");
            return false;
        }
        pos($(this));
    });
    $("#message").focusout(function(){
        val = $(this).val();
        if(val == ""){
            error($(this), "Fill in your message");
            $(this).css("border-bottom", "1px solid red")
            return false;
        }
        $(this).css("border-bottom","1px solid green");
    });
    $("#form").submit(function(e){
        e.preventDefault();
        email = $("#email").val();
        if(!validEmail(email)){
            error($("#email"), "Invalid email");
            return false;
        }
        pos($("#email"));        
        subject = $("#subject").val();
        if(val == ""){
            error($("#subject"), "You need to fill in the subject");
            return false;
        }
        pos($("#subject"));

        message = $(this).val();
        if(val == ""){
            error($(message), "Fill in your message");
            $(message).css("border-bottom", "1px solid red")
            return false;
        }
        $(message).css("border-bottom","1px solid green");

        $.post("Nytrix/view/email.php",{email: $("#email").val(),subject: $("#subject").val(),message:$("#message").val()})
        .done(function(response){
            $(".succes").remove();
            $(".failure").remove();
            if(response  == "succes"){
                $("#form").append("<div class='succes'>Thank you for your email</div>");
                $("#form")[0].reset();
            }else{
                $("#form").append("<div class='failure'>Something went wrong with sending the email!</div>");
            }
        });
    })

});

function error(elm,text){
    $(elm).css("border-bottom","1px solid red");
    $(elm).next().next().remove();
    $(elm).next().after("<div class='errormessage'>" + text + "</div>");
}

function pos(elm){
    $(elm).css("border-bottom","1px solid green");
    $(elm).next().next().remove();
}

function validEmail($email){
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($email);
}