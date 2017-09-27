/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 

$('#ReserveTime').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var aika = button.data('time-to-reserve') // Extract info from data-* attributes
  var day = button.data('day');
  var date = button.data('date');
  var dateobject = new Date(date);
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  var localday = moment(dateobject).format('DD.MM.');
  $("#PassCalendarName").val($("#CalendarName").text());
  $('#ReserveOnDate').val(date);
  $('#TimeToReserve').val(aika);
  modal.find('.modal-title').text('Varaa aika ' + day + 'na ' + localday + ' klo ' + aika);
})

function ReserveButtonConfirm() {
    $(document).ready(function () {
        document.getElementById("alert-reserve-success").style.display = 'block';
        document.getElementById("reserve-button-confirm").disabled = true;
        console.log("bah");
    });

}

function ReserveButtonClose() {
    $(document).ready(function () {
        document.getElementById("reserve-button-confirm").disabled = false;
        document.getElementById("alert-reserve-success").style.display = 'none';
    });
}

$("#reserve-time-form").submit(function (event) {
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

        //document.getElementById('login-success').style.display = 'block';
        if (response === "Onnistui") {

            NaytaKalenterit();
        }
        if (response === "Epäonnistui") {
            alert("Väliaikainen kirjautuminen epäonnistui ilmoitus");
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