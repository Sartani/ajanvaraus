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
            $dates = $this->StartToEndDates($start_date, $end_date, 'dates');
            $weekdays = $this->StartToEndDates($start_date, $end_date, 'weekdays');
            $this->InsertToCalendarDates($dates, $weekdays);
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

    private function InsertToCalendarDates($dates, $weekdays) {
        require_once("mysql.php");
        $mysql = new mysql();

        if ($mysql->connectDB()) {
            $id = $mysql->insert_id;
            foreach (array_combine($dates, $weekdays) as $date => $weekday) {

                $stmt = $mysql->db_connection->prepare('INSERT INTO calendar_dates (calendar_id, date, weekday) VALUES (?, ?, ?)');
                $stmt->bind_param('iss', $id, $date, $weekday);
                $stmt->execute();
            }
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

}
