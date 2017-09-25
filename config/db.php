<?php

$whitelist = array(
    '127.0.0.1',
    '::1');
if ((in_array($_SERVER['REMOTE_ADDR'], $whitelist))) {
    -define("DB_HOST", "localhost");
    -define("DB_NAME", "ajanvaraus");
    -define("DB_USER", "root");
    -define("DB_PASS", "");
} else {
    -define("DB_HOST", "localhost");
    -define("DB_NAME", "id3024393_ajanvaraus");
    -define("DB_USER", "id3024393_admin");
    -define("DB_PASS", "lAF4LDM6nUVY");
}
?>