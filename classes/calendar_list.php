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
        if ($login->IsUserLogged()) {
            $this->generate_calendar_list(TRUE);
        } else {
            $this->generate_calendar_list(FALSE);
        }
    }

    #osaksi calendaria?

    private function NotLoggedList() {
        $mysql = new mysql();
        if ($mysql->connectDB()) {
            $mysql->db_connection;

            $stmt = $mysql->db_connection->prepare('SELECT calendar_name FROM calendar_options ORDER BY (calendar_name) ASC');
            $stmt->execute();
            $calendars = $stmt->get_result();

            if ($calendars->num_rows > 0) {
                while ($row = $calendars->fetch_row()) {
                    echo '<p> <button type="button" onclick="ShowCalendar(\'' . $row[0] . '\')" name="varaa" class="btn btn-primary btn-lg">' . $row[0] . '</button> </p>';
                }
            }
        }
    }

    private function generate_calendar_list($logged) {

        if ($logged) {
            $this->NotLoggedList();
            echo '<p><button type="button" onclick="ShowLuoKalenteri()" id="LuoKalenteri" name = "LuoKalenteri" class="btn btn-primary btn-lg">Uusi kalenteri</button></p>';
        } else {
            $this->NotLoggedList();
        }
    }

}
