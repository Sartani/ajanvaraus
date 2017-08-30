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

    $("#main").load("views/reservation_list.html", function () {
        //alert("Load was performed.");
        console.log("kvaak"); 
    });
}
 function NaytaEtusivu(){

    $("#main").load("views/kirjaudu.html", function () {
        //alert("Load was performed.");
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