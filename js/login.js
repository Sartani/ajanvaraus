/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var request;
$("#login-form").submit(function (event) {
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
        
        //document.getElementById('login-success').style.display = 'block';
        if (response==="Onnistui"){
            alert("testi");
        }
        if(response==="Epäonnistui"){
            alert("testi2");
        }
        
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