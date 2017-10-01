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
    
      public function IsDateAndTimeAvailable($id, $date, $time) {
        if ($this->connectDB()) {
            $stmt = $this->db_connection->prepare('SELECT reservation_date, reservation_time FROM calendar_reservations WHERE calendar_id = (?) AND reservation_date = (?) AND reservation_time=(?)');
            $stmt->bind_param('sss', $id, $date, $time);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                return TRUE;
            }
            return FALSE;
        } else {
            echo "Tietokantaan ei saatu yheyttä";
        }
    }
    
        public function SelectIdByName($name) {
        if ($this->connectDB()) {

            if ($stmt = $this->db_connection->prepare('SELECT calendar_id FROM calendar_options WHERE calendar_name = ?')) {
                $stmt->bind_param('s', $name);
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    $row = $result->fetch_row();
                    return $id = $row[0];
                    
                } else {
                    print_r("ID ei haettu. " . htmlspecialchars($this->db_connection->error));
                }
            } else {
                print_r('prepare() failed: ' . htmlspecialchars($this->db_connection->error));
            }
        } else {
            echo "Tietokantaan ei saatu yhteyttä";
        }
    }

    public function DeleteAllFromCalendarOptionsByID($id) {
        if ($this->connectDB()) {

            if ($stmt = $this->db_connection->prepare('DELETE FROM calendar_options WHERE calendar_id = ?')) {
                $stmt->bind_param('i', $id);
                if ($stmt->execute()) {
                    
                } else {
                    print_r("execute() failed:  " . htmlspecialchars($this->db_connection->error));
                }
            } else {
                print_r('prepare() failed: ' . htmlspecialchars($this->db_connection->error));
            }
        } else {
            echo "Tietokantaan ei saatu yhteyttä";
        }
    }

    public function DeleteAllFromCalendarReservationsByID($id) {
        if ($this->connectDB()) {

            if ($stmt = $this->db_connection->prepare('DELETE FROM calendar_reservations WHERE calendar_id = ?')) {
                $stmt->bind_param('i', $id);
                if ($stmt->execute()) {
                    
                } else {
                    print_r("execute() failed:  " . htmlspecialchars($this->db_connection->error));
                }
            } else {
                print_r('prepare() failed: ' . htmlspecialchars($this->db_connection->error));
            }
        } else {
            echo "Tietokantaan ei saatu yhteyttä";
        }
    }

    public function DeleteAllFromCalendarDatesByID($id) {
        if ($this->connectDB()) {

            if ($stmt = $this->db_connection->prepare('DELETE FROM calendar_dates WHERE calendar_id = ?')) {
                $stmt->bind_param('i', $id);
                if ($stmt->execute()) {
                    
                } else {
                    print_r("execute() failed:  " . htmlspecialchars($this->db_connection->error));
                }
            } else {
                print_r('prepare() failed: ' . htmlspecialchars($this->db_connection->error));
            }
        } else {
            echo "Tietokantaan ei saatu yhteyttä";
        }
    }

}
