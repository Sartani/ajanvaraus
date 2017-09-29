<?php

/**
 * This class will be used to generate the calendar
 *
 * @author Mikko
 */


class calendar {
    private $reservable_days=[];
    private $daysconversion = array("Mon" => "maanantai", "Tue" => "tiistai",
            "Wed" => "keskiviikko", "Thu" => "torstai", "Fri" => "perjantai",
            "Sat" => "lauantai", "Sun" => "sunnuntai");
     public function __construct() {
     

     if (isset($_POST['calendar'])) {

            require_once("mysql.php");
            $mysql = new mysql();
            $calendar = $_POST['calendar'];
            $calendaroptions = $this->SelectCalendarOptionsByName($calendar, $mysql);
            if (isset($_POST['change_week'])) {
                $this->reservable_days = $this->SelectResevableDays($calendar, $_POST['change_week'], $mysql);
            } else {
                $this->reservable_days = $this->SelectResevableDays($calendar, "", $mysql);
            }
            $this->echoheader();
        }
        if (!isset($_POST['calendar'])) {
            echo "Mikään kalenteri ei ollut valittuna, ole hyvä ja ota yhteyttä ylläpitoon";
        }
        if (isset($_SESSION['logged']) AND $_SESSION['logged'] == 'TRUE' and isset($_POST['create_calendar'])) {
            $this->GenerateCalendar('7', '15', '8', '8', $_POST['calendar'], $mysql);
            #Generoi kalenteri näkymä kirjautuneelle käyttäjälle
        } elseif (!isset($_SESSION['logged]'])) {
            $this->GenerateCalendar($calendaroptions['reservable_days'], '15', $calendaroptions['start_time'], $calendaroptions['end_time'], $_POST['calendar'], $mysql, $calendaroptions['break_start_time'], $calendaroptions['break_end_time']);
        }
    }

    private function GenerateCalendar($alloweddays, $intervals, $HourEnd, $HourStart, $calendarname, $mysql, $breakstart, $breakend) {
          $daysasnumbers = ["Mon" => "0", "Tue" => "1",
            "Wed" => "2", "Thu" => "3", "Fri" => "4",
            "Sat" => "5", "Sun" => "6"];
          
        $howmanydays = count($this->reservable_days);
        $smallestday = $this->reservable_days[0];
        $datesandweekdays = $this->GenerateWeekDaysDates($howmanydays, $smallestday, $daysasnumbers);
        $this->GenerateDays($datesandweekdays);
        $breaktimes = $this->GenerateBreakTimes($breakstart, $breakend);
        $this->GenerateHours($alloweddays, $HourEnd, $HourStart, $datesandweekdays, $intervals, $calendarname, $mysql, $daysasnumbers, $breaktimes);
        
    }

    private function GenerateDays($datesandweekdays) {
        echo '<div class="container">
                <div class="table-responsive">          
  <table class="table">
    <thead>
      <tr>';

        $day = 0;
        while ($day < 7) {
            echo "<th>" . $datesandweekdays[$day] . "</th>";
            $day = $day + 1;
        }

        echo '</tr> </thead>';
    }

    private function GenerateHours($alloweddays, $Hourstart, $HourEnd, $datesandweekdays, $intervals, $calendarname, $mysql, $daysasnumbers, $breaktimes) {
        echo '<tbody>';
        $echohour = new DateTime($Hourstart);
        $echohour = $echohour->format('G');
        $HourEnd = new DateTime($HourEnd);
        $HourEnd = $HourEnd->format('G');
        $alloweddaysarray = $this->ExplodeString("", $alloweddays);
        foreach ($alloweddaysarray as $day) {
            if (isset($daysasnumbers[$day])) {
                $allowedaysasnumbers[] = $daysasnumbers[$day];
            }
        } if (!isset($allowedaysasnumbers)) {
            die("<b>Yhtään varattavaa päivää ei ole valittuna!<b>");
        }

        while ($echohour < $HourEnd) {
            echo'<tr>';
            $tdstart = 0;

            while ($tdstart < 7) {
                $echodate = $this->ExplodeString(0, $datesandweekdays[$tdstart]);
                $echoday = $this->ExplodeString(1, $datesandweekdays[$tdstart]);
                $echohourto = $echohour + 1;
                $thisdayreservations = $this->SelectReservationsByDateAndName($calendarname, $echodate, $mysql);

                $allowed = TRUE;
                if (!in_array($tdstart, $allowedaysasnumbers)) {
                    echo'<td> <button class="btn btn-danger btn-block" type="button" data-toggle="collapse" data-target="#' . $echoday . '-collapse-' . $echohour . '" aria-expanded="false">'
                    . $echohour . ' - ' . $echohourto . '</button>'
                    . ' <div class="collapse" id="' . $echoday . '-collapse-' . $echohour . '">'
                    . '<div class="card card-body">'
                    . '<div class="btn-group-vertical btn-block" role="group" aria-label="Vertical button group">';
                    $allowed = FALSE;
                    $formattedtime = "";
                } elseif (in_array($tdstart, $allowedaysasnumbers)) {
                    echo'<td> <button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#' . $echoday . '-collapse-' . $echohour . '" aria-expanded="false">'
                    . $echohour . ' - ' . $echohourto . '</button>'
                    . ' <div class="collapse" id="' . $echoday . '-collapse-' . $echohour . '">'
                    . '<div class="card card-body">'
                    . '<div class="btn-group-vertical btn-block" role="group" aria-label="Vertical button group">';
                    $allowed = TRUE;
                }
                $this->EchoMinutes($echoday, $echohour, $intervals, $echodate, $thisdayreservations, $allowed, $breaktimes);
                echo "</div> </div> </div> </td>";
                $tdstart = $tdstart + 1;
            }
            echo '</tr>';
            $echohour = $echohour + 1;
        }
        echo'</tbody> </table> </div> </div>';
    }

    private function EchoMinutes($echoday, $echohour, $intervals, $echodate, $thisdayreservations, $allowed, $breaktimes) {
        $echominutes = '00';
        foreach ($thisdayreservations as $rows) {

            $times[] = $rows[1];
        }
        while ($echominutes <= 45) {
            $reservabletime = $echohour . ':' . $echominutes;
            $reservabletime = new DateTime($reservabletime);
            $reservabletime = $reservabletime->format('H:i:s');
            if (!in_array($reservabletime, $times, true) AND $allowed AND !in_array($reservabletime, $breaktimes)) {
                echo '<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#ReserveTime" data-day="' . $echoday . '"data-date="' . $echodate . '" data-time-to-reserve="' . $echohour . ':' . $echominutes . '">' . $echominutes . '</button>';
                } elseif (in_array ($reservabletime, $times) AND $allowed AND !in_array($reservabletime, $breaktimes)) {
                echo '<button type="button" class="btn btn-danger btn-block" " title="Varattu""' . $echoday . '"data-date="' . $echodate . '" data-time-to-reserve="' . $echohour . ':' . $echominutes . '">' . $echominutes . '</button>';
                } elseif(!$allowed OR in_array($reservabletime, $breaktimes)) {
                    echo'<button type="button" class="btn btn-danger btn-block" " title="Ei varattavissa""' . $echoday . '"data-date="' . $echodate . '" data-time-to-reserve="' . $echohour . ':' . $echominutes . '">' . $echominutes . '</button>';
                
            } else {
                echo "Jokin meni vikaan minuutteja hakiessa";
            }
            $echominutes = $echominutes + $intervals;
        }
    }

    private function WhichDay($day) {
            $days = array("maanantai", "tiistai", "keskiviikko", "torstai", "perjantai", "lauantai", "sunnuntai");
        return $days[$day];
    }
    
     private function SelectResevableDays($calendar, $when, $mysql) {
        if ($when != "") {
            if ($mysql->connectDB()) {
                $stmt = $mysql->db_connection->prepare('SELECT calendar_dates.date, calendar_dates.weekday FROM calendar_dates INNER JOIN calendar_options ON calendar_dates.calendar_id = calendar_options.calendar_id WHERE calendar_options.calendar_name = (?) AND calendar_dates.date >=(?) ORDER BY (date) ASC');
                $stmt->bind_param('ss', $calendar, $when);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows != 0) {
                    while ($row = $result->fetch_array()) {
                        $reservable_days[] = $row;
                        $dayoutfbounds = FALSE;
                        return $reservable_days;
                    }
                } else {
                    $dayoutfbounds = TRUE;
                }
            }
        } if ($when == "" OR $dayoutfbounds) {
            if ($mysql->connectDB()) {
                $stmt = $mysql->db_connection->prepare('SELECT calendar_dates.date, calendar_dates.weekday FROM calendar_dates INNER JOIN calendar_options ON calendar_dates.calendar_id = calendar_options.calendar_id WHERE calendar_options.calendar_name = (?) ORDER BY (date) ASC');
                $stmt->bind_param('s', $calendar);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows!=0){
                while ($row = $result->fetch_array()) {
                    $reservable_days[] = $row;
                }}else {
                    echo"varattavia päiviä hakiessa tapahtui virhe";
                    $reservable_days[]="";
                }

                return $reservable_days;
            }
        } else {
            echo"Jokin meni vikaan päivien valinnassa, ole hyvä ja ota yhteys ylläptioon";
        }
    }
 
    private function GenerateWeekDaysDates($howmanydays, $smallestday, $daysasnumbers) {
        $day = 0;
        $selectstarts = $daysasnumbers[$smallestday[1]];
        $substractdate = $selectstarts;
        $datesandweekdays = [];
        while ($day < $selectstarts) {
            $substracteddate = $this->SubstractDate($smallestday[0], $substractdate);
            $datesandweekdays[] = $substracteddate . " " . $this->WhichDay($day);
            $day = $day + 1;
            $substractdate = $substractdate - 1;
        }
        $howmanydays = $day + $howmanydays;
        $selector = 0;
        while ($day < $howmanydays AND $day < 7) {
            $whichday = $this->reservable_days[$selector];
            $datesandweekdays[] = $whichday[0] . " " . $this->daysconversion[$whichday[1]];
            $day = $day + 1;
            $selector = $selector + 1;
        } $adddate = $day - $selectstarts;
        while ($day < 7) {
            $addeddate = $this->AddDate($smallestday[0], $adddate);
            $whichday = $this->WhichDay($day);
            $datesandweekdays[] = $addeddate . " " . $this->WhichDay($day);
            $day = $day + 1;
            $adddate = $adddate + 1;
        }return $datesandweekdays;
    }

    private function AddDate($addaysto, $howmanydays) {
        $date = new DateTime($addaysto);
        $date->add(new DateInterval('P' . $howmanydays . 'D'));
        $addeddate = $date->format('Y-m-d');
        return $addeddate;
    }

    private function SubstractDate($substractdaysfrom, $howmanydays) {
        $date = new DateTime($substractdaysfrom);
        $date->sub(new DateInterval('P' . $howmanydays . 'D'));
        $substracteddate = $date->format('Y-m-d');
        return $substracteddate;
    }

    private function ExplodeString($slot, $StringToExplode) {
        $split = explode(" ", $StringToExplode);
        if($slot !==""){
        return $split[$slot];}
        elseif($slot===""){
            return $split;
        }
    }
    
    private function SelectReservationsByDateAndName($calendar, $date, $mysql) {
        if ($mysql->connectDB()) {
            $stmt = $mysql->db_connection->prepare('SELECT calendar_reservations.reservation_date, calendar_reservations.reservation_time, calendar_reservations.reserver_name FROM calendar_reservations INNER JOIN calendar_options ON calendar_reservations.calendar_id = calendar_options.calendar_id WHERE calendar_options.calendar_name = (?) AND calendar_reservations.reservation_date = (?) ORDER BY (reservation_time) ASC');
            $stmt->bind_param('ss', $calendar, $date);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows != 0) {
                while ($row = $result->fetch_row()) {
                    $reservations[] = $row;
                }
            } else {
                $reservations[] = null;
            }
            return $reservations;
        }
    }

    private function echoheader(){
        $smallestday = $this->reservable_days[0];
        $previousweekstart = $this->SubstractDate($smallestday[0], 7);
        $nextweekstart = $this->AddDate($smallestday[0], 7);
        $largestday = $this->reservable_days[count($this->reservable_days)-1];
        echo'<head> <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" /></head>';
        echo "<h1 id='CalendarName'>" . $_POST['calendar'] . "</h1> <p>Varattavissa 15 min aikoja</p> ";
        echo '<input type="hidden" id="MaxDate" value ="' . $largestday[0] . '"></input>' ;
          echo'<div class="row">
        <div class="col-sm-4"> <i id="leftarrow" class="fa fa-caret-square-o-left" aria-hidden="true"></i> <input type ="hidden" id="mindateback" value="'. $previousweekstart . '"></input></div>
        <div class="form-group col-sm-4"> <input type = "text"  class="form-control" id="DateTimeRangeSelect" name="DateTimeRangeSelect" readonly> </input>  </div>
        <div class="col-sm-4 text-right" ><i id="rightarrow" class="fa fa-caret-square-o-right" aria-hidden="true"></i><input type ="hidden" id="mindateforward" value="'. $nextweekstart . '"></input></div>
      </div>';
    }

    
    private function SelectCalendarOptionsByName($calendarname, $mysql) {
        if ($mysql->connectDB()) {
            $stmt = $mysql->db_connection->prepare('SELECT * FROM calendar_options WHERE calendar_name = (?)');
            $stmt->bind_param('s', $calendarname);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $options = $result->fetch_assoc();
            } else {
                $options[] = null;
                echo "Jokin meni pieleen";
            }
            return $options;
        } else {
            echo "Tietokantaan ei saatu yheyttä";
        }
    }

    private function GenerateBreakTimes($breakstart, $breakend) {
        if (!is_null($breakstart) AND ! is_null($breakend)) {

            $date = new DateTime($breakstart);
            $date->format('h:i:s') . "\n";
            $formattedtime = "";
            $breaktimes[] = $breakstart;
            while ($formattedtime !== $breakend) {

                $date->add(new DateInterval('PT15M'));
                $formattedtime = $date->format('h:i:s');
                $breaktimes[] = $formattedtime;
            }
            return $breaktimes;
        } else {
            echo "AAAA";
        }
    }

}
