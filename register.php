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
    require_once "utils/sf_reg_form_validation.php";
    
    require_once __DIR__ . '/vendor/autoload.php';
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;
    
    $reg_log = new Logger('Registration');
    $reg_log->pushHandler(new StreamHandler('logs/registration_info.log', Logger::INFO));

    $reg_username = $reg_email = $reg_password = "";
    $usernameErrStr = $emailErrStr = $passwordErrStr = "";
    $reg_result_err = $reg_result_success = "";
    
    /* Run database insert query when registration form is submitted and all validation checks are passed */
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $validationPassed = true;
        
        $sql_conn = sf_mysql_connect();

        /* Username Not Empty Check */
        if(empty($_POST["reg-username-input"])) {
            $usernameErrStr = "* Please enter a username";
            $validationPassed = false;
        }
        else {
            /**
             * At this stage, username is already validated by the AJAX request sent to
             * regValidateUsername.php by the frontend JavaScript validator.
             * However, we still run the validation checks again in case the POST request
             * is sent from somewhere else.
             */

            /* Username Regex Check */
            /* At least three chars, at most 30, no special chars except period and underscore */
            $reg_username = sf_test_input($_POST["reg-username-input"]);
            if (!sf_username_regmatch($reg_username)) {
                $usernameErrStr = "* Invalid username";
                $validationPassed = false;
            }
            else {
                /* Already in database check */
                if(sf_username_exists($reg_username)) {
                    $validationPassed = false;
                    $usernameErrStr = "* Username already exists";
                }
            }
        }

        /* Password not empty check */
        if(empty($_POST["reg-password-input"])) {
            $passwordErrStr = "* Please enter a password";
            $validationPassed = false;
        }
        else {
            $reg_password = sf_test_input($_POST["reg-password-input"]);
            /* Password regex check */
            if (!sf_password_regmatch($reg_password)) {
                $passwordErrStr = "* Invalid password";
                $validationPassed = false;
            }
        }

        /* E-mail not empty check */
        if(empty($_POST["reg-email-input"])) {
            $emailErrStr = "* Please enter an E-mail ID";
            $validationPassed = false;
        }
        else {
            $reg_email = sf_test_input($_POST["reg-email-input"]);
            if(!filter_var($reg_email, FILTER_VALIDATE_EMAIL)) {
                $emailErrStr = "* Invalid E-mail ID";
                $validationPassed = false;
            }
            else {
                /* Already in database check */
                if(sf_email_exists($reg_email)) {
                    $validationPassed = false;
                    $emailErrStr = "* Email is already linked with an account";
                }
            }
        }

        if($validationPassed == true)
        {
            // Hash the password
            $hashed_password = password_hash($reg_password, PASSWORD_DEFAULT);

            // Run insertion query
            $query = "INSERT INTO users (username, password, email) values (?, ?, ?)";
            sf_mysql_query($sql_conn, $query, "sss", $reg_username, $hashed_password, $reg_email);
            $reg_result_success = "Registration successful!";
            $reg_log->info("New user registered: $reg_username, $reg_email");
        }
        else {
            $reg_result_err = "* Unable to register";
        }
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Forum Registration</title>
        <link rel="stylesheet" href="<?php echo sf_timestamp_file("style.css"); ?>">
        <script src="<?php echo sf_timestamp_file("components/username_form_validator.js"); ?>" defer></script>
        <script src="<?php echo sf_timestamp_file("components/password_form_validator.js"); ?>" defer></script>
    </head>

    <body>

    <header>
        <h1>Forum</h1>
        <?php require_once "components/login_form.php"; ?>
    </header>

    <?php require_once "components/nav_bar.php"; ?>

    <main>
        <div class="general-container">
            <div class="general-container-title">
                Register
            </div>
            <div class="general-container-content">
                <form class="general-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="general-form-labels">
                        <label for="reg-username-input">Username:</label>
                        <label for="reg-password-input">Password:</label>
                        <label for="reg-password-input-repeat">Repeat:</label>
                        <label for="reg-email-input">E-mail:</label>
                    </div>

                    <div class="general-form-inputs">
                        <input name="reg-username-input" id="reg-username-input" type="text" value="<?php echo $reg_username; ?>">
                        <input name="reg-password-input" id="reg-password-input" type="password" value="<?php echo $reg_password; ?>">
                        <input name="reg-password-input-repeat" id="reg-password-input-repeat" type="password" value="<?php echo $reg_password; ?>">
                        <input name="reg-email-input" id="reg-email-input" type="text" value="<?php echo $reg_email; ?>">
                        <button type="submit" id="reg-submit-button">Register</button>
                    </div>

                    <div class="general-form-errors">
                        <div class="general-form-error-msg" id="username-error-msg"><?php echo $usernameErrStr; ?></div>
                        <div class="general-form-error-msg" id="password-error-msg"><?php echo $passwordErrStr; ?></div>
                        <div class="general-form-error-msg"><?php echo $emailErrStr; ?></div>
                    </div>
                </form>
                <div class="general-form-result">
                    <span class="general-form-error-msg"> <?php echo $reg_result_err; ?> </span>
                    <span class="general-form-success-msg"> <?php echo $reg_result_success; ?> </span>
                </div>
            </div>
        </div>
    </main>

    <?php require_once "components/footer.php"; ?>
    
    </body>
</html>
