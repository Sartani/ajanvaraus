var date = new Date();
var d = date.getDate();
var y = date.getFullYear();
var m = date.getMonth();
var m = m + 1;
$(function() {
    $('input[name="DateTimeRange"]').daterangepicker({
    "showWeekNumbers": true,
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
    "starDate": ""+y+"-"+m+"-"+d+"",
    "endDate": ""+y+"-"+m+"-"+d+""
}, function(start, end, label) {
  console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
});
});




$( document ).ready(function() {

$('#FromWhen').timepicker({
    'step': 15,
    useSelect: 'TRUE' ,
    'showDuration':"false",
    'minTime': '08:00',

});

$('#ToWhen').timepicker({
    'step': 15,
    useSelect: 'TRUE' ,
    'showDuration':"false",
    'minTime': '08:00',

});

$('#TimesBeforeBreak .time').timepicker({
 timeFormat: 'H:i ',
    'step': 15,
    useSelect: 'TRUE' ,
    'showDuration':"false"


});



var TimesBeforeBreak = document.getElementById('TimesBeforeBreak');
var timeOnlyDatepair = new Datepair(TimesBeforeBreak);
var TimesAfterBreak = document.getElementById('TimesAfterBreak');
var TimesAfterBreak = new Datepair(TimesAfterBreak);


});

$('#NeedBreak').change(function () {
    if (this.checked) {
        $('#BreakFromWhen').timepicker({
            'step': 15,
            useSelect: 'TRUE',
            'showDuration': "false",
            'minTime': '08:00'

        });
        $('#BreakToWhen').timepicker({
            'step': 15,
            useSelect: 'TRUE',
            'showDuration': "false",
            'minTime': '08:00'

        });
        $('#TimesAfterBreak .time').timepicker({
 timeFormat: 'H:i ',
    'step': 15,
    useSelect: 'TRUE' ,
    'showDuration':"false",


});
        document.getElementById('TimesAfterBreakId').style.display = 'block';
    }
    if (!this.checked) {

        document.getElementById('TimesAfterBreakId').style.display = 'none';
    }
});

