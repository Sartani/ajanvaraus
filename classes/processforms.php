<?php

/**
 * Description of ProcessForms
 *
 * @author Mikko
 */
new ProcessForms();

class ProcessForms {

    public function __construct() {
        if (isset($_POST['create_calendar'])) {
            echo"banaanimaakari";
            $DateTimeRange = $_POST['DateTimeRange'];
            $days = $_POST['AvailableDays'];
            $calendar_name = $_POST['CalendarName'];
            $reservation_intervals = $_POST['HowManyMinutes'];
            $start_time = $_POST['FromWhen'];
            $end_time = $_POST['ToWhen'];
            $this->CreateNewCalendar($calendar_name, $DateTimeRange, $days, $reservation_intervals, $start_time, $end_time);
        }
    }

    private function CreateNewCalendar($calendar_name, $DateTimeRange, $days, $reservation_intervals, $start_time, $end_time) {

        if ($this->ValidateLuoKalenteriForm($DateTimeRange, $days, $calendar_name, $reservation_intervals, $start_time, $end_time)) {
//$start_time = $this->ExplodeString(1, $DateTimeRange);
//$end_time = $this->ExplodeString(4, $DateTimeRange);
            $start_date = $this->ExplodeString(0, $DateTimeRange);
            $end_date = $this->ExplodeString(1, $DateTimeRange);
            $this->InsertToCalendarOptions($calendar_name, $days, $reservation_intervals, $start_date, $end_date, $start_time, $end_time);
        }
    }

    private function ValidateLuoKalenteriForm() {
        return TRUE;
    }

    private function ExplodeString($slot, $DateTimeRange) {
        $split = explode(" ", $DateTimeRange);
        return $split[$slot];
    }

    private function InsertToCalendarOptions($calendar_name, $days, $reservation_intervals, $start_date, $end_date, $start_time, $end_time) {
        require_once("mysql.php");
        $mysql = new mysql();

        if ($mysql->connectDB()) {
            $mysql->db_connection;

            $stmt = $mysql->db_connection->prepare('INSERT INTO calendar_options (start_date, end_date, start_time, end_time, days, calendar_name, reservation_intervals) VALUES (?,?,?,?,?,?,?)');
            $stmt->bind_param('ssssssi', $start_date, $end_date, $start_time, $end_time, $days, $calendar_name, $reservation_intervals);
            $stmt->execute();
        }
    }

}
