function NaytaKirjautuminen(){

    $("#main").load("views/kirjaudu.html", function () {
        //alert("Load was performed.");
    });
}

function NaytaRekisterointi(){

    $("#main").load("views/register.html", function () {
        //alert("Load was performed.");
    });
}

function NaytaKalenterit(){

    $("#main").load("views/reservation_calendar_list.php", function () {
        //alert("Load was performed.");
    });
}
 function NaytaEtusivu(){

    $("#main").load("views/index.php", function () {
        //alert("Load was performed.");
    });
}

function ShowCalendar(calendar){

    $("#main").load("views/reservation_calendar.php",{"calendar" : calendar}, function () {
         //alert("Load was performed.");
    });
}
function ShowLuoKalenteri(){
    $("#main").load("views/luo_kalenteri.html", function () {
    });
}
var request;
$("#register-form").submit(function (event) {
    event.preventDefault();
    if (request) {
        request.abort();
    }
    var $form = $(this);
    var $inputs = $form.find("input, select, button, textarea");
    var serializedData = $form.serialize();
    
    $inputs.prop("disabled", true);
    request = $.ajax({
        url: $form.attr('action'),
        type: "post",
        data: serializedData
    });
    console.log("request: " + request);
    request.done(function (response, textStatus, jqXHR) {
        
        document.getElementById('register-success').style.display = 'block';
        
          console.log($form.attr('action'));
          console.log("serializedData:" + serializedData);
        console.log("textstatus: " + textStatus);
       console.log("Response: " + response);
     
    });
    request.fail(function (jqXHR, textStatus, errorThrown) {
        console.error(
                "The following error occurred: " +
                textStatus, errorThrown
                );
    });
    request.always(function () {
        $inputs.prop("disabled", false);
    });

});