<?php


require_once("mysql.php");
new register();

/**
 * Class to handle registering users
 *
 * @author Mikko
 */
class register {

    private $db_connection = null;

    public function __construct() {

        if (isset($_POST["user_email"])) {
            echo"Rekisteröinti ei tällä hetkellä ole käytössä";
        }
    }
    
    private function registerNewUser() {
        $mysql = new mysql();
        
        if ($mysql->connectDB()) {
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $user_name = ($_POST['user_name']);
            $user_email = ($_POST['user_email']);
            $user_password = $_POST['user_password'];


            $stmt = $this->db_connection->prepare('SELECT * FROM users WHERE user_name = ? OR user_email = ?');
            $stmt->bind_param('ss', $user_name, $user_email);
            $stmt->execute();

            $query_check_user_name = $stmt->get_result();

            if ($query_check_user_name->num_rows != 0) {
                echo"Käyttäjätunnus tai sähköposti on jo rekisteröity.";
            } else {

                $stmt = $this->db_connection->prepare('INSERT INTO users (user_name, user_password, user_email) VALUES (?,?,?)');
                $stmt->bind_param('sss', $user_name, $user_password, $user_email);


                if ($stmt->execute()) {
                    #do stuff
                    exit();
                }
            }
        }
    }

}
