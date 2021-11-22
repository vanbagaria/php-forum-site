<?php
    require_once "../utils/sf_test_input.php";
    require_once "../utils/sf_mysql.php";
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $response = "VALID";

        /* If request is for username validation */
        if($_POST["for"] == "username") {
            $reg_username = sf_test_input($_POST["reg-username-input"]);
            /* At least three chars, at most 30, no special chars except period and underscore */
            if (!preg_match("/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{2,29}$/", $reg_username)) {
                $response = "INVALID";
            }
            else {
                $sql_conn = sf_mysql_connect();
                $query = "SELECT * FROM users WHERE username=?";
                $result = sf_mysql_query($sql_conn, $query, "s", $reg_username);
                if(mysqli_num_rows($result) == 1) {
                    $response = "EXISTS";
                }
            }
        }
        /* If request is for password validation */
        else if($_POST["for"] == "password") {
            $reg_password = sf_test_input($_POST["reg-password-input"]);
            /**
             * Password should have
             * - at least 8 characters
             * - must contain at least 1 uppercase letter, 1 lowercase letter, and 1 number
             * - Can contain special characters
             */
            if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $reg_password)) {
                $response = "INVALID";
            }
        }

        echo $response;
    }
    else {
        echo "Who are you? What are you trying to do? This link is not accessible like this. Go back.";
    }
?>