$(document).ready(function(){
    $('.input-group input').on('input blur',function(){
        var text_val = $(this).val();
        if(text_val === "") {
            $(this).removeClass('has-value');
        } else {
            $(this).addClass('has-value');
        }
    });
    $('.input-group input').focusout(function(){
        var text_val = $(this).val();
        if(text_val === "") {
            $(this).removeClass('has-value');
        } else {
            $(this).addClass('has-value');
        }
    });
    /* on click */
    $("#login").on("submit",function(e){
        e.preventDefault();
        $this = $(this);
        var username = $("#login_username").val();
        var password = $("#login_password").val();
        var remember = $("#remember").is(":checked");
        $.ajax({
            url: "/felta/login",
            type: "POST",
            data: {username: username, password:  password, remember: remember},
            success: function(data){
                data = $.parseJSON(data.trim());
                if(data['logged_in'] == true){
                    window.location.replace("/");
                }else{
                    $(".error").remove();
                    $("#login_password").val("");
                    $this.append("<div class='error'>"+data['message']+"</div>");
                }
            }
        });
        return false;
    });
});