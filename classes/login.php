<?php

require_once("mysql.php");
new login();

class login {

    public function __construct() {
        if (isset($_POST["user_email"])) {
            session_start();
            $this->Login($_POST["user_email"], $_POST["user_password"]);
        }else{
            echo "jotain meni nyt pieleen";
        }
    }

    private function Login($user_email, $user_password) {
        $mysql = new mysql();

        if ($mysql->connectDB()) {
            $mysql->db_connection;
            $user_email = ($_POST['user_email']);
            $user_password = $_POST['user_password'];


            $stmt = $mysql->db_connection->prepare('SELECT * FROM users WHERE user_name = ? OR user_email = ? AND user_password = ?');
            $stmt->bind_param('sss', $user_email, $user_email, $user_password);
            $stmt->execute();

            $query_check_user_name = $stmt->get_result();

            if ($query_check_user_name->num_rows === 1) {
                echo"Onnistui";
                $_SESSION['logged'] = 'TRUE';
            } else {
                echo"Epäonnistui";
            }
        }
    }

}
