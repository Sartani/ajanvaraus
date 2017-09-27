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
            echo "<h1> " . $_POST['calendar'] . "</h1> <br>";
            require_once("mysql.php");
            $mysql = new mysql();
            $calendar = $_POST['calendar'];
            $this->reservable_days = $this->SelectResevableDays($calendar,"",$mysql);
        }
        if (!isset($_POST['calendar'])) {
            echo "Mikään kalenteri ei ollut valittuna, ole hyvä ja ota yhteyttä ylläpitoon";
        }

        if (isset($_SESSION['logged']) AND $_SESSION['logged'] == 'TRUE' and isset($_POST['create_calendar'])) {
            
            $this->GenerateCalendar('7', '15', '8', '8');
            #Generoi kalenteri näkymä kirjautuneelle käyttäjälle
        } elseif(!isset($_SESSION['logged]'])) {
            $this->GenerateCalendar('7', '15', '8', '8');
        }
    }

    private function GenerateCalendar($days, $intervals, $HourCount, $HourStart) {
        $howmanydays = count($this->reservable_days);
        $smallestday = $this->reservable_days[0];
        $datesandweekdays = $this->GenerateWeekDaysDates($howmanydays, $smallestday);
        $this->GenerateDays($datesandweekdays);
        $this->GenerateHours($days, $HourCount, $HourStart, $datesandweekdays, $intervals);
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

    private function GenerateHours($days, $HourCount, $Hourstart, $datesandweekdays, $intervals) {
        echo '<tbody>';
        $rowstart = 0;
        $echohour = $Hourstart;
       
        while ($rowstart < $HourCount) {
            echo'<tr>';
            $tdstart = 0;
            while ($tdstart < $days) {
                $echodate = $this->ExplodeString(0, $datesandweekdays[$tdstart]);
                $echoday = $this->ExplodeString(1, $datesandweekdays[$tdstart]);
                $echohourto = $echohour + 1;
                echo'<td> <button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#' . $echoday . '-collapse-' . $echohour . '" aria-expanded="false">'
                . $echohour . ' - ' . $echohourto  . '</button>'
                . ' <div class="collapse" id="' . $echoday . '-collapse-' . $echohour . '">'
                . '<div class="card card-body">'
                . '<div class="btn-group-vertical btn-block" role="group" aria-label="Vertical button group">';
               $this-> EchoMinutes($echoday, $echohour, $intervals, $echodate);
                echo "</div> </div> </div> </td>";
                $tdstart = $tdstart + 1;
            }
            echo '</tr>';
            $echohour = $echohour + 1;
            $rowstart = $rowstart + 1;
        }
        echo'</tbody> </table> </div> </div>';
    }
    private function EchoMinutes($echoday, $echohour, $intervals, $echodate) {
        $echominutes = '00';
        while ($echominutes <= 45) {
            echo '<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#ReserveTime" data-day="' . $echoday . '"data-date="' . $echodate. '" data-time-to-reserve="' . $echohour . ':' . $echominutes . '">' . $echominutes . '</button>';
            $echominutes = $echominutes + $intervals;
        }
    }

    private function WhichDay($day) {
            $days = array("maanantai", "tiistai", "keskiviikko", "torstai", "perjantai", "lauantai", "sunnuntai");
        return $days[$day];
    }
    
     private function SelectResevableDays($calendar, $when, $mysql) {
        if ($when != "") {
          
        } elseif ($when == "") {
            $when = date("Y-m-d");
            if ($mysql->connectDB()) {
                $mysql->db_connection;
                $stmt = $mysql->db_connection->prepare('SELECT calendar_dates.date, calendar_dates.weekday FROM calendar_dates INNER JOIN calendar_options ON calendar_dates.calendar_id = calendar_options.calendar_id WHERE calendar_options.calendar_name = (?) ORDER BY (date) ASC');
                $stmt->bind_param('s', $calendar);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_array()) {
                    $reservable_days[] = $row;
                }

                return $reservable_days;
            }
        } else {
            echo"Jokin meni vikaan päivien valinnassa, ole hyvä ja ota yhteys ylläptioon";
        }
    }
 
    private function GenerateWeekDaysDates($howmanydays, $smallestday) {
        $day = 0;
        $daysasnumbers = ["Mon" => "0", "Tue" => "1",
            "Wed" => "2", "Thu" => "3", "Fri" => "4",
            "Sat" => "5", "Sun" => "6"];
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
            echo "tässä1 -->" . $datesandweekdays[$day];
            $day = $day + 1;
            $adddate = $adddate + 1;
        }return $datesandweekdays;
    }

    private function AddDate($addaysto, $howmanydays) {
        $date = new DateTime($addaysto);
        $date->add(new DateInterval('P' . $howmanydays . 'D'));
        $addeddate = $date->format('y-m-d');
        return $addeddate;
    }

    private function SubstractDate($addaysto, $howmanydays) {
        $date = new DateTime($addaysto);
        $date->sub(new DateInterval('P' . $howmanydays . 'D'));
        $substracteddate = $date->format('y-m-d');
        return $substracteddate;
    }
      
    private function ExplodeString($slot,  $StringToExplode) {
        $split = explode(" ", $StringToExplode);
        return $split[$slot];
    }
    private function  SeperateDaysDates ($DatesDays, $ChooseReturn){
        if ($ChooseReturn == 'Days'){
            
        }elseif($ChooseReturn=='dates'){
            
        }else{
            echo "Tapahtui virhe päivien ja päiväysten erottelussa. Ole hyvä ja ota yhteyttä ylläpitoon";
        }
    }
}
