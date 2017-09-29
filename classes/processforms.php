<?php

/**
 * Description of ProcessForms
 *
 * @author Mikko
 */
new ProcessForms();

class ProcessForms {

    private $id = NULL;

    public function __construct() {
        if (isset($_POST['create_calendar'])) {
            echo"banaanimaakari";
            $DateTimeRange = $_POST['DateTimeRange'];
            $days = $this->CombineReservableDays();
            $calendar_name = $_POST['CalendarName'];
            $reservation_intervals = $_POST['HowManyMinutes'];
            $start_time = $_POST['FromWhen'];
            $end_time = $_POST['ToWhen'];
            $break_start = NULL;
            $break_end = NULL;
            if(isset($_POST['NeedBreak'])){
                $break_start = $_POST['BreakFromWhen'];
                $break_end = $_POST['BreakToWhen'];
            }
            if ($this->ValidateLuoKalenteriForm($DateTimeRange, $days, $calendar_name, $reservation_intervals, $start_time, $end_time, $break_start, $break_end)) {
                require_once("mysql.php");
                $mysql = new mysql();
                $this->CreateNewCalendar($calendar_name, $DateTimeRange, $days, $reservation_intervals, $start_time, $end_time, $break_start, $break_end, $mysql);
            }
        }
        if (isset($_POST['ReserveOnDate']) AND isset($_POST['TimeToReserve']) AND isset($_POST['reserver-name'])) {
            if ($this->ValidateReserveTimeForm($_POST['ReserveOnDate'], $_POST['TimeToReserve'], $_POST['reserver-name'])) {
                require_once("mysql.php");
                $mysql = new mysql();
                $this->SelectIdByName($_POST['PassCalendarName'],$mysql);
                $this->InsertToReservations($_POST['ReserveOnDate'], $_POST['TimeToReserve'], $_POST['reserver-name'], $mysql);
            }
        }
    }

    private function CreateNewCalendar($calendar_name, $DateTimeRange, $days, $reservation_intervals, $start_time, $end_time, $break_start, $break_end, $mysql) {


//$start_time = $this->ExplodeString(1, $DateTimeRange);
//$end_time = $this->ExplodeString(4, $DateTimeRange);
        $start_date = $this->ExplodeString(0, $DateTimeRange);
        $end_date = $this->ExplodeString(1, $DateTimeRange);
        $this->InsertToCalendarOptions($calendar_name, $days, $reservation_intervals, $start_date, $end_date, $start_time, $end_time, $break_start, $break_end, $mysql);
        $dates = $this->StartToEndDates($start_date, $end_date, 'dates');
        $weekdays = $this->StartToEndDates($start_date, $end_date, 'weekdays');
        $this->InsertToCalendarDates($dates, $weekdays, $mysql);
    }

    private function ValidateLuoKalenteriForm() {
        return TRUE;
    }

    private function ExplodeString($slot, $DateTimeRange) {
        $split = explode(" ", $DateTimeRange);
        return $split[$slot];
    }

    private function InsertToCalendarOptions($calendar_name, $days, $reservation_intervals, $start_date, $end_date, $start_time, $end_time, $break_start, $break_end, $mysql) {

        if ($mysql->connectDB()) {
            $mysql->db_connection;

            if ($stmt = $mysql->db_connection->prepare('INSERT INTO calendar_options (start_date, end_date, start_time, end_time, break_start_time, break_end_time, reservable_days, calendar_name, reservation_intervals) VALUES (?,?,?,?,?,?,?,?,?)')) {
                $stmt->bind_param('ssssssssi', $start_date, $end_date, $start_time,$end_time, $break_start, $break_end, $days, $calendar_name, $reservation_intervals);
                $stmt->execute();
                $this->id = $mysql->db_connection->insert_id;
                print_r(htmlspecialchars($mysql->db_connection->error));
            } else {
                echo "Valintojen syöttö tietokantaan ei onnistunut, ole hyvä ja ota yhteyttä ylläpitoon";
                print_r('prepare() failed: ' . htmlspecialchars($mysql->db_connection->error));
            }
        }
    }

    private function InsertToCalendarDates($dates, $weekdays, $mysql) {


        if ($mysql->connectDB()) {
          
            foreach (array_combine($dates, $weekdays) as $date => $weekday) {
            $id = $this->id;
                $stmt = $mysql->db_connection->prepare('INSERT INTO calendar_dates (calendar_id, date, weekday) VALUES (?, ?, ?)');
                $stmt->bind_param('iss',$id, $date, $weekday);
                $stmt->execute();
            }
        }else {
            echo "Päivien syötössä kalenteriin tapahtui virhe, ole hyvä ja ota yhteyttä ylläpitoon";
        }
    }

    private function StartToEndDates($start_date, $end_date, $option) {
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $end = $end->modify('+1 day');

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval, $end);
        $test = 0;
        if ($option == 'dates') {
            foreach ($daterange as $date) {
                //echo $date->format("Y-m-d D") . "<br>";
                $dates[] = $date->format("Y-m-d");
                echo $dates[$test];
                $test = $test + 1;
            }
            return $dates;
        } elseif ($option == 'weekdays') {
            foreach ($daterange as $date) {
                $weekdays[] = $date->format("D");
                echo $weekdays[$test];
                $test = $test + 1;
            }
            return $weekdays;
        }
    }
    private function CombineReservableDays() {
        $ReserVableDays = "";
        if (isset($_POST['AvailableDaysCheckboxma'])) {
            $ReserVableDays = $ReserVableDays . $_POST['AvailableDaysCheckboxma'] . " ";
        }
        if (isset($_POST['AvailableDaysCheckboxti'])) {
            $ReserVableDays = $ReserVableDays . $_POST['AvailableDaysCheckboxti'] . " ";
        }
        if (isset($_POST['AvailableDaysCheckboxke'])) {
            $ReserVableDays = $ReserVableDays . $_POST['AvailableDaysCheckboxke'] . " ";
        }
        if (isset($_POST['AvailableDaysCheckboxto'])) {
            $ReserVableDays = $ReserVableDays . $_POST['AvailableDaysCheckboxto'] . " ";
        }
        if (isset($_POST['AvailableDaysCheckboxpe'])) {
            $ReserVableDays = $ReserVableDays . $_POST['AvailableDaysCheckboxpe'] . " ";
        }
        if (isset($_POST['AvailableDaysCheckboxla'])) {
            $ReserVableDays = $ReserVableDays . $_POST['AvailableDaysCheckboxla'] . " ";
        }
        if (isset($_POST['AvailableDaysCheckboxsu'])) {
            $ReserVableDays = $ReserVableDays . $_POST['AvailableDaysCheckboxsu'];
        }
        
        echo $ReserVableDays;
        return $ReserVableDays;
    }

    private function InsertToReservations($ReserveOnDate, $TimeToReserve, $ReserverName, $mysql) {
        if ($mysql->connectDB()) {

            $id = $this->id;
            if ($stmt = $mysql->db_connection->prepare('INSERT INTO calendar_reservations (calendar_id, reservation_date, reservation_time, reserver_name) VALUES (?, ?, ?,?)')) {
                $stmt->bind_param('isss', $id, $ReserveOnDate, $TimeToReserve, $ReserverName);
                if($stmt->execute()){
                echo "Tiedot syötetty";}else{
                print_r("Tietoja ei syötetty. " . htmlspecialchars($mysql->db_connection->error));}
            } else {
                print_r('prepare() failed: ' . htmlspecialchars($mysql->db_connection->error));
            }
        } else {
            echo "Tietokantaan ei saatu yhteyttä";
        }
    }
    
    private function SelectIdByName($name, $mysql) {
        if ($mysql->connectDB()) {


            if ($stmt = $mysql->db_connection->prepare('SELECT calendar_id FROM calendar_options WHERE calendar_name = ?')) {
                $stmt->bind_param('s', $name);
                if ($stmt->execute()) {
                    $result= $stmt->get_result();
                    $row = $result->fetch_row();
                    $this->id = $row[0];

                } else {
                    print_r("ID ei haettu. " . htmlspecialchars($mysql->db_connection->error));
                }
            } else {
                print_r('prepare() failed: ' . htmlspecialchars($mysql->db_connection->error));
            }
        } else {
            echo "Tietokantaan ei saatu yhteyttä";
        }
    }

    private function ValidateReserveTimeForm($ReserveOnDate, $TimeToReserve, $ReserverName) {
        #ToDo
        return TRUE;
    }

}
