<?php

require_once("mysql.php");
new login();

class login {

    public function __construct() {
        if (isset($_POST["user_email"])) {
            session_start();
            $this->Login($_POST["user_email"], $_POST["user_password"]);
        }else{
           #do stuff
        }
        if (isset($_POST['logout'])){
            $this->Logout();
        }
    }
     public function IsUserLogged(){
         session_start();
           if (isset($_SESSION['logged']) AND $_SESSION['logged']=='TRUE') {
               return TRUE;
        } else {
            return FALSE;
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
    private function Logout(){
        session_start();
        session_unset();
        session_destroy();
        
    }

}
