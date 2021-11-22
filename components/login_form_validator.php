<?php
    require_once "../utils/sf_test_input.php";
    require_once "../utils/sf_mysql.php";

    session_start();

    $response = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(empty($_SESSION["user"])) {
            $username = sf_test_input($_POST["username"]);
            $password = sf_test_input($_POST["password"]);

            $sql_conn = sf_mysql_connect();
            $query = "SELECT * FROM users WHERE username=?";
            $result = sf_mysql_query($sql_conn, $query, "s", $username);

            // Authenticate and set the session user variable
            if(mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);
                if(password_verify($password, $user["password"])) {
                    $_SESSION["user"] = $user["username"];
                    $_SESSION["user_id"] = $user["id"];
                    $response = "SUCCESS";
                }
                else {
                    $response = "WRONG_PASSWORD";
                }
            }
            else {
                $response = "USER_NOT_FOUND";
            }
        }
        echo $response;
    }
?>