$(function() {
    $('input[name="DateTimeRange"]').daterangepicker({
    "showWeekNumbers": true,
    "timePicker": true,
    "timePicker24Hour": true,
    "timePickerIncrement": 15,
    "locale": {
        "format": "DD/MM/YYYY h:mm",
        "separator": " - ",
        "applyLabel": "Apply",
        "cancelLabel": "Cancel",
        "fromLabel": "From",
        "toLabel": "To",
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
    "startDate": "09/09/2017",
    "endDate": "15/09/2017"
}, function(start, end, label) {
  console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
});
    
});


$(function() {
    $('input[name="daterange"]').daterangepicker();
});

