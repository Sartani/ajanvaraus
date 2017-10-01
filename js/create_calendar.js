
var request;
$("#create-calendar-form").submit(function (event) {
    event.preventDefault();
    if (request) {
        request.abort();
    }
    var $form = $(this);
    var $inputs = $('#form').find(':input');
    var serializedData = $form.serialize();
    
    $inputs.prop("disabled", true);
    request = $.ajax({
        url: $form.attr('action'),
        type: "post",
        data: serializedData
    });
    console.log("request: " + request);
    request.done(function (response, textStatus, jqXHR) {
    $("#CreateCalendarAlerts").html(response);

        
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
