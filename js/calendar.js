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
  reservabletimelength();
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

$("#leftarrow").click(function() {

   $("#main").load("views/reservation_calendar.php",{"calendar" : $("#CalendarName").text(), "change_week" : $("#mindateback").val()}, function () {
    });
});
$("#rightarrow").click(function() {

   $("#main").load("views/reservation_calendar.php",{"calendar" : $("#CalendarName").text(), "change_week" : $("#mindateforward").val()}, function () {
    });
});
$("#printview").click(function() {

   $("#main").load("views/reservation_calendar.php",{"calendar" : $("#CalendarName").text(), "print_view" : "true"}, function () {
    });
});


var date = new Date();
var d = date.getDate();
var y = date.getFullYear();
var m = date.getMonth();
var m = m + 1;
$(function() {
    $('input[name="DateTimeRangeSelect"]').daterangepicker({
    "showWeekNumbers": true,
    "singleDatePicker": true,
    "locale": {
       
        "format": "YYYY-MM-DD",
        "separator": " ",
        "applyLabel": "Valitse",
        "cancelLabel": "Peruuta",
        "fromLabel": "Mistä",
        "toLabel": "Mihin",
        "customRangeLabel": "Custom",
        "weekLabel": "vk",
        "daysOfWeek": [
            "su",
            "ma",
            "ti",
            "ke",
            "to",
            "pe",
            "la"
        ],
        "monthNames": [
            "tammi",
            "helmi",
            "maalis",
            "huhti",
            "touko",
            "kesä",
            "heinä",
            "elo",
            "syys",
            "loka",
            "marras",
            "joulu"
        ],
        "firstDay": 1
    },
    "showCustomRangeLabel": false,
    "minDate": ""+y+"-"+m+"-"+d+"",
    "maxDate": $("#MaxDate").val(),
    "starDate": ""+y+"-"+m+"-"+d+"",
   }, function(start) {
  console.log('New date selected: ' + start.format('YYYY-MM-DD'));
  $("#main").load("views/reservation_calendar.php",{"calendar" : $("#CalendarName").text(), "change_week" : start.format('YYYY-MM-DD')}, function () {
    });
});
});
function reservabletimelength() {

    if ($('#ReservableTimeLength').val() == '30') {
        var timetoreserve = moment($('#TimeToReserve').val(), "HH:mm").add(15, 'minutes').format('HH:mm');
        $('#reserve-time-form').append(' <input type="hidden" id="TimeToReserve2" name="TimeToReserve2" value="' + timetoreserve + '">');
    }



    if ($('#ReservableTimeLength').val() == '45') {
        var timetoreserve = moment($('#TimeToReserve').val(), "HH:mm").add(15, 'minutes').format('HH:mm');
        $('#reserve-time-form').append(' <input type="hidden" id="TimeToReserve2" name="TimeToReserve2" value="' + timetoreserve + '">');
        var timetoreserve2 = moment($('#TimeToReserve').val(), "HH:mm").add(30, 'minutes').format('HH:mm');
        $('#reserve-time-form').append(' <input type="hidden" id="TimeToReserve3" name="TimeToReserve3" value="' + timetoreserve2 + '">');
    }
}
