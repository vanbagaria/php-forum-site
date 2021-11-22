<?php
    // ini_set('display_errors', 1);
    // error_reporting(E_ALL);

    session_start();
    if(!empty($_SESSION["user"])) {
        header("Location: index.php");
        exit;
    }

    require_once "utils/sf_timestamp_file.php";
    require_once "utils/sf_test_input.php";
    require_once "utils/sf_mysql.php";

    $username = $password = "";
    $usernameErrStr = $passwordErrStr = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $validationPassed = true;

        if(empty($_POST["login-username-input"])) {
            $usernameErrStr = "* Please enter a username";
            $validationPassed = false;
        }
        else {
            $username = sf_test_input($_POST["login-username-input"]);
        }
        
        if(empty($_POST["login-password-input"])) {
            $passwordErrStr = "* Please enter a password";
            $validationPassed = false;
        }
        else {
            $password = sf_test_input($_POST["login-password-input"]);
        }

        if($validationPassed) {
            $sql_conn = sf_mysql_connect();
            $query = "SELECT * FROM users WHERE username=?";
            $result = sf_mysql_query($sql_conn, $query, "s", $username);
    
            // Authenticate and set the session user variable
            if(mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);
                if(password_verify($password, $user["password"])) {
                    $_SESSION["user"] = $user["username"];
                    $_SESSION["user_id"] = $user["id"];
                    header("Location: index.php");
                }
                else {
                    $passwordErrStr = "* wrong password";
                }
            }
            else {
                $usernameErrStr = "* username not found";
            }
        }
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Forum Login</title>
        <link rel="stylesheet" href="<?php echo sf_timestamp_file("style.css"); ?>">
    </head>

    <body>

    <header>
        <h1>Forum</h1>
    </header>

    <?php require_once "components/nav_bar.php"; ?>

    <main>
        <div class="general-container">
            <div class="general-container-title">
                Log In
            </div>
            <div class="general-container-content">
                <form class="general-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="general-form-labels">
                        <label for="login-username-input">Username:</label>
                        <label for="login-password-input">Password:</label>
                    </div>
                    <div class="general-form-inputs">
                        <input name="login-username-input" id="login-username-input" type="text" value="<?php echo $username; ?>">
                        <input name="login-password-input" id="login-password-input" type="password" value="<?php echo $password; ?>">
                        <button type="submit" id="login-submit-button">Log In</button>
                    </div>
                    <div class="general-form-errors">
                        <div class="general-form-error-msg" id="username-error-msg"><?php echo $usernameErrStr; ?></div>
                        <div class="general-form-error-msg" id="password-error-msg"><?php echo $passwordErrStr; ?></div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php require_once "components/footer.php"; ?>
    
    </body>
</html>
