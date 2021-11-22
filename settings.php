<?php
    // ini_set('display_errors', 1);
    // error_reporting(E_ALL);

    session_start();
    if(empty($_SESSION["user"])) {
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
    
    $currentPassword = $newPassword = "";
    $settingsResultSuccessStr = $settingsResultErrorStr = "";
    $currentPasswordErrStr = $newPasswordErrStr = "";

    $session_user = $_SESSION["user"];
    
    $sql_conn = sf_mysql_connect();
    $query = "SELECT id, password FROM users WHERE username=?";
    $result = sf_mysql_query($sql_conn, $query, "s", $session_user);
    $sessionUserData = mysqli_fetch_assoc($result);
    $sessionUserID = $sessionUserData["id"];
    $currentFetchedPassword = $sessionUserData["password"];

    $settings_log = new Logger('Settings');
    $settings_log->pushHandler(new StreamHandler("userdata/$sessionUserID/logs/settings.log", Logger::INFO));

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $validationPassed = true;

        if(empty($_POST["current-password-input"])) {
            $currentPasswordErrStr = "* Please enter your current password";
            $validationPassed = false;
        }
        else {
            $currentPassword = sf_test_input($_POST["current-password-input"]);
            // Check if the current password is valid
            if(!password_verify($currentPassword, $currentFetchedPassword)) {
                $currentPasswordErrStr = "* Entered current password is wrong";
                $validationPassed = false;
            }
        }

        if(empty($_POST["reg-password-input"])) {
            $newPasswordErrStr = "* Please enter a new password";
            $validationPassed = false;
        }
        else {
            $newPassword = sf_test_input($_POST["reg-password-input"]);
            // Regex match the new password
            if(!sf_password_regmatch($newPassword)) {
                $newPasswordErrStr = "* Invalid new password";
                $validationPassed = false;
            }
        }
        
        if($validationPassed == true) {
            if($newPassword == $currentPassword) {
                $settingsResultSuccessStr = "Nothing to change.";
            }
            else {
                // Hash the password
                 $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
    
                // Run the password update query
                $query = "UPDATE users SET password=? WHERE id=?";
                sf_mysql_query($sql_conn, $query, "ss", $hashed_password, $sessionUserID);
                $settingsResultSuccessStr = "Password changed successfully.";
                $settings_log->info("Password changed.");
            }
        }
        else {
            $settingsResultErrorStr = "* Unable to change password";
        }
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Forum Settings</title>
        <link rel="stylesheet" href="<?php echo sf_timestamp_file("style.css"); ?>">
        <script src="components/password_form_validator.js" defer></script>
    </head>
    <body>
        <header>
            <h1>Forum</h1>
        </header>

        <?php require_once "components/nav_bar.php"; ?>

        <main>
            <div class="general-container">
                <div class="general-container-title">
                    Settings
                </div>
                <div class="general-container-content">
                    <h3>Change Password</h3>
                    <form class="general-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="general-form-labels">
                            <label for="current-password-input">Current Password:</label>
                            <label for="reg-password-input">New Password:</label>
                            <label for="reg-password-input-repeat">New Password:</label>
                        </div>
                        <div class="general-form-inputs">
                            <input name="current-password-input" id="current-password-input" type="password" value="<?php echo $currentPassword; ?>">
                            <input name="reg-password-input" id="reg-password-input" type="password" value="<?php echo $newPassword; ?>">
                            <input name="reg-password-input-repeat" id="reg-password-input-repeat" type="password" value="<?php echo $newPassword; ?>">
                            <button type="submit" id="reg-submit-button">Change</button>
                        </div>
                        <div class="general-form-errors">
                            <div class="general-form-error-msg" id="current-password-error-msg"><?php echo $currentPasswordErrStr; ?></div>
                            <div class="general-form-error-msg" id="password-error-msg"><?php echo $newPasswordErrStr; ?></div>
                        </div>
                    </form>
                    <div class="general-form-result">
                        <span class="general-form-error-msg"> <?php echo $settingsResultErrorStr; ?> </span>
                        <span class="general-form-success-msg"> <?php echo $settingsResultSuccessStr; ?> </span>
                    </div>
                </div>
            </div>
        </main>

        <?php require_once "components/footer.php"; ?>
        
    </body>
</html>