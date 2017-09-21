<?php

/**
 * This class will be used to generate the calendar
 *
 * @author Mikko
 */


class calendar {
    
     public function __construct() {
     

     if (isset($_POST['calendar'])){
                echo "<h1> ". $_POST['calendar'] . "</h1> <br>";
            }
            if (!isset($_POST['calendar'])){
                echo "ei toimi";
            }

        if (isset($_SESSION['logged']) AND $_SESSION['logged'] == 'TRUE' and ! isset($_POST['create_calendar'])) {
            
            $this->GenerateCalendar('7', '', '8', '8');
            #Generoi kalenteri näkymä kirjautuneelle käyttäjälle
        } elseif(!isset($_SESSION['logged]'])) {
            $this->GenerateCalendar('7', '', '8', '8');
        }
    }

    private function GenerateCalendar($days, $intervals, $HourCount, $HourStart) {


        $this->GenerateDays($days);
        $this->GenerateHours($days, $HourCount, $HourStart);
    }

    private function GenerateDays($days) {
        echo '<div class="container">
                <div class="table-responsive">          
  <table class="table">
    <thead>
      <tr>';
        $day = 0;
        while ($day < $days) {
            $whichday = $this->WhichDay($day);
            echo "<th>" . $whichday . "</th>";
            $day = $day + 1;
        }
        echo '</tr> </thead>';
    }

    private function GenerateHours($days, $HourCount, $Hourstart) {
        echo '<tbody>';
        $rowstart = 0;
        $echohour = $Hourstart;
        while ($rowstart < $HourCount) {
            echo'<tr>';
            $tdstart = 0;
            while ($tdstart < $days) {
                $echoday = $this->WhichDay($tdstart);
                $echohourto = $echohour + 1;
                echo'<td> <button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#' . $echoday . '-collapse-' . $echohour . '" aria-expanded="false">'
                . $echohour . ' - ' . $echohourto  . '</button>'
                . ' <div class="collapse" id="' . $echoday . '-collapse-' . $echohour . '">'
                . '<div class="card card-body">'
                . '<div class="btn-group-vertical btn-block" role="group" aria-label="Vertical button group">';
                $echominutes = '00';
                //Echoes 00-45 at 15 min intervals
                while ($echominutes <= 45) {
                    echo '<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#ReserveTime" data-day="' .$echoday . '" data-time-to-reserve="' . $echohour . ':' . $echominutes . '">'. $echominutes . '</button>';
                    $echominutes = $echominutes + 15;
                }
                echo "</div> </div> </div> </td>";
                $tdstart = $tdstart + 1;
            }
            echo '</tr>';
            $echohour = $echohour + 1;
            $rowstart = $rowstart + 1;
        }
        echo'</tbody> </table> </div> </div>';
    }

    private function WhichDay($day) {
        $days = array("Maanantai", "Tiistai", "Keskiviikko", "Torstai", "Perjantai", "Lauantai", "Sunnuntai");
        return $days[$day];
    }

}
