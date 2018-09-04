$(document).ready(function(){
    window.first = true;
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
    $(".switcher").on("change",function(){
        if(this.checked){
            $.ajax({
                url: document.location.protocol+"//"+getDomain()+"/felta/status",
                type: "POST",
                data: {
                    online: 1
                }
            });
        }else{
            $.ajax({
                url: document.location.protocol+"//"+getDomain()+"/felta/status",
                type: "POST",
                data: {
                    online: 0
                }
            });
        }
    });
    var ONE_HOUR = 60 * 60 * 1000;
    var hours = 24;
    var date = new Date;
    var date2 = new Date(date.getTime() - (ONE_HOUR * hours))
    $.ajax({
        url: "/felta/statistics/total/today",
        type: "GET",
        success: function(response){
            $.ajax({
                url: "/felta/statistics/unique/today",
                type: "GET",
                success: function(response2){
                    var values = JSON.parse(response);
                    var values2 = JSON.parse(response2);

                    var labels = [];
                    var dataset = [];
                    var dataset2 = [];
                    var upcomingvalue = 0;
                    var upcomingvalue2 = 0;
                    for(var i = 0; i <= hours; i++) {
                        var d = new Date(date2.getTime() + ONE_HOUR * i);
                        labels.push(d.getHours()+":00");

                        if(values[upcomingvalue] != undefined){
                            var uvalue = new Date(values[upcomingvalue][0]);
                            if(sameDay(uvalue,d) && uvalue.getHours() === d.getHours()){
                                dataset.push(values[upcomingvalue][1]);
                                upcomingvalue++;
                            }else{
                                dataset.push(0);
                            }       
                        }else{
                            dataset.push(0);
                        }
                        if(values2[upcomingvalue2] != undefined){
                            var uvalue = new Date(values2[upcomingvalue2][0]);
                            if(sameDay(uvalue,d) && uvalue.getHours() === d.getHours()){
                                dataset2.push(values[upcomingvalue2][1]);
                                upcomingvalue2++;
                            }else{
                                dataset2.push(0);
                            }       
                        }else{
                            dataset2.push(0);
                        }

                    }
                    var ctx = document.getElementById("visitors");
                    ctx.height = 350;
                    var visitors = new Chart(ctx,{
                        type: 'line',
                        data:{
                            labels: labels,
                            datasets:[{
                                borderColor: "#2196F3",
                                backgroundColor: "#2196F3",
                                pointBackgroundColor: "#f1f1f1",
                                label: "Unique views",
                                data: dataset2
                            },{
                                borderColor: "#003258",
                                backgroundColor: "#003258",
                                pointBackgroundColor: "#f1f1f1",
                                label: "Total views",
                                data: dataset
                            }]
                        },
                        options: { 
                            legend: {
                                labels: {
                                    fontColor: "#f1f1f1",
                                }
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        fontColor: "#f1f1f1",
                                        beginAtZero: true
                                    }
                                }],
                                xAxes: [{
                                    ticks: {
                                        fontColor: "#f1f1f1",
                                    }
                                }]
                            }
                        }
                    });
                }
            });
        }
    });

});
function sameDay(d1, d2) {
  return d1.getFullYear() === d2.getFullYear() &&
    d1.getMonth() === d2.getMonth() &&
    d1.getDate() === d2.getDate();
}
function checkServer(){
    ifServerOnline(function(){
        $("#website_is").removeClass("offline");
        $("#website_is").addClass("online");
        $("#website_status").text("online");
        if(first){
            console.log("hey?");
            first = false;
            $(".switcher").prop("checked",true);
        }
    },
    function (){
        $("#website_is").removeClass("online");
        $("#website_is").addClass("offline");
        $("#website_status").text("offline");
        if(first){
            first = false;
            $(".switcher").prop("checked",false);
        }
    });
    var tid = setTimeout(checkServer,2000);
}
function ifServerOnline(ifOnline, ifOffline){
    $.ajax({
        url: document.location.protocol+"//"+getDomain()+"/felta/status",
        type: "GET",
        success: function(response){
            var status = JSON.parse(response);
            if(status["online"] > 0){
                ifOnline && ifOnline.constructor == Function && ifOnline();
            }else{
                ifOffline && ifOffline.constructor == Function && ifOffline();
            }
        }
    });       
}

