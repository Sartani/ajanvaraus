<?php


/**
 * Class to handle registering users
 *
 * @author Mikko
 */
class register {
      private function registerNewUser() {

        $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (!$this->db_connection->set_charset("utf8")) {
            $this->errors[] = $this->db_connection->error;
        }

        if (!$this->db_connection->connect_errno) {
            $user_name = ($_POST['user_name']);
            $user_email = ($_POST['user_email']);
            $user_password = $_POST['user_password_new'];
            $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

            $stmt = $this->db_connection->prepare('SELECT * FROM users WHERE user_name = ? OR user_email = ?');
            $stmt->bind_param('ss', $user_name, $user_email);
            $stmt->execute();

            $query_check_user_name = $stmt->get_result();

            if ($query_check_user_name->num_rows != 0) {
                $this->errors[] = "Käyttäjätunnus tai sähköposti on jo rekisteröity.";
            } else {

                $stmt = $this->db_connection->prepare('INSERT INTO users (user_name, user_password, user_email) VALUES (?,?,?)');
                $stmt->bind_param('sss', $user_name, $user_password_hash, $user_email);


                if ($stmt->execute()) {
                    $this->messages[] = "Käyttäjätili on luotu. Kirjaudu sisään.";
                    header("Location: index.php?register=1");
                    exit();
                }
            }
        }
    }

}
