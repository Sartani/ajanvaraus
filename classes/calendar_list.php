<?php

/**
 * Description of calendar_list
 *
 * @author Mikko
 */
require_once("login.php");

new calendar_list;

class calendar_list {

    public function __construct() {
        $login = new login();
        $mysql = new mysql();
        if ($login->IsUserLogged()) {
            $this->generate_calendar_list(TRUE, $mysql);
        } else {
            $this->generate_calendar_list(FALSE, $mysql);
        }
    }

    #osaksi calendaria?

    private function NotLoggedList($mysql) {
        if ($mysql->connectDB()) {
            $mysql->db_connection;

            $stmt = $mysql->db_connection->prepare('SELECT calendar_name FROM calendar_options ORDER BY (calendar_name) ASC');
            $stmt->execute();
            $calendars = $stmt->get_result();

            if ($calendars->num_rows > 0) {
                while ($row = $calendars->fetch_row()) {
                    echo '<div> <p> <button type="button" onclick="ShowCalendar(\'' . $row[0] . '\')" name="varaa" class="btn btn-primary btn-lg">' . $row[0] . '</button> </p>';
                }  
            }
        }
    }
    private function IsLoggedList($mysql) {
        if ($mysql->connectDB()) {
            $mysql->db_connection;

            $stmt = $mysql->db_connection->prepare('SELECT calendar_name FROM calendar_options ORDER BY (calendar_name) ASC');
            $stmt->execute();
            $calendars = $stmt->get_result();

            if ($calendars->num_rows > 0) {
                while ($row = $calendars->fetch_row()) {
                    echo '<div> <p> <button type="button" onclick="ShowCalendar(\'' . $row[0] . '\')" name="varaa" class="btn btn-primary btn-lg">' . $row[0] . '</button> ';
                    echo '<button type="button" title="Tästä voit poistaa kalenterin" class="btn btn-lg btn-danger" data-target="#DeleteCalendar"'
                    .'data-toggle="modal" data-delete-name="' . $row[0] . '"> Poista kalenteri </button></p> </div>';
                }
            }
        }
    }

    private function generate_calendar_list($logged, $mysql) {

        if ($logged) {

            $this->IsLoggedList($mysql);
            echo '<p><button type="button" onclick="ShowLuoKalenteri()" id="LuoKalenteri" name = "LuoKalenteri" class="btn btn-primary btn-lg">Uusi kalenteri</button></p>';
        } else {
            $this->NotLoggedList($mysql);
        }
    }

}
