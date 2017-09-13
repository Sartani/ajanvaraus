<?php
require_once("../config/db.php");

/**
 * Description of mysql
 *
 * @author Mikko
 */
class mysql {
    public $db_connection = null;
    
     public function connectDB() {
        $result = 1;
        if ($this->db_connection == null) {
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }
            if (!$this->db_connection) {
                $this->errors[] = "Tietokanta: yhteysongelma!";
                $this->errors[] = " VIHREET: " . implode(":- ", $this->db_connection->error);
                $result = 0;
            }
        }
        return $result;
    }
}
