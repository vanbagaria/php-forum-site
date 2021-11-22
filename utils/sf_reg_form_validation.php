<?php

    require_once "sf_mysql.php";

    /**
     * Username Regex Check
     * At least three chars, at most 30, no special chars except period and underscore
     */
    function sf_username_regmatch($test_username) {
        return preg_match("/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{2,29}$/", $test_username);
    }

    /**
     * Password Regex Check
     * - at least 8 characters
     * - must contain at least 1 uppercase letter, 1 lowercase letter, and 1 number
     * - Can contain special characters
     */
    function sf_password_regmatch($test_password) {
        return preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $test_password);
    }

    /**
     * Checks if the username already exists in the database
     */
    function sf_username_exists($test_username) {
        $sql_conn = sf_mysql_connect();
        $query = "SELECT * FROM users WHERE username=?";
        $result = sf_mysql_query($sql_conn, $query, "s", $test_username);
        if(mysqli_num_rows($result) == 1) {
            return true;
        }
        return false;
    }

    /**
     * Checks if the email id already exists in the database
     */
    function sf_email_exists($test_email) {
        $sql_conn = sf_mysql_connect();
        $query = "SELECT * FROM users WHERE email=?";
        $result = sf_mysql_query($sql_conn, $query, "s", $test_email);
        if(mysqli_num_rows($result) == 1) {
            return true;
        }
        return false;
    }
?>