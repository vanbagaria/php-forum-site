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
    require_once "utils/sf_get_profile_pic_url.php";
    require_once "utils/sf_mysql.php";
    require_once "utils/sf_reg_form_validation.php";

    require_once __DIR__ . '/vendor/autoload.php';
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;
    
    $session_user = $_SESSION["user"];
    $user_display_name = $user_email = $user_register_date = "";
    $user_new_username = $user_new_email = "";
    $usernameErrStr = $emailErrStr = $picErrStr = "";
    $update_result_err = $update_result_success = "";
    $pic_upload_path = "";
    
    $sql_conn = sf_mysql_connect();

    $query = "SELECT id, username, email, registerdate FROM users WHERE username=?";
    $result = sf_mysql_query($sql_conn, $query, "s", $session_user);
    $sessionUserData = mysqli_fetch_assoc($result);
    $sessionUserID = $sessionUserData["id"];
    $user_display_name = $sessionUserData["username"];
    $user_email = $sessionUserData["email"];
    $user_register_date = $sessionUserData["registerdate"];
    
    $profile_log = new Logger('Profile');
    $profile_log->pushHandler(new StreamHandler("userdata/$sessionUserID/logs/profile.log", Logger::INFO));

    /* Run database update query when form is submitted and all validation checks are passed */
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $validationPassed = true;

        /* Username Not Empty Check */
        if(empty($_POST["reg-username-input"])) {
            $usernameErrStr = "* Please enter a username";
            $validationPassed = false;
        }
        else {
            /* Username Regex Check */
            /* At least three chars, at most 30, no special chars except period and underscore */
            $user_new_username = sf_test_input($_POST["reg-username-input"]);
            if (!sf_username_regmatch($user_new_username)) {
                $usernameErrStr = "* Invalid username";
                $validationPassed = false;
            }
            else {
                if($user_new_username != $user_display_name) {
                    /* Already in database check */
                    if(sf_username_exists($user_new_username)) {
                        $validationPassed = false;
                        $usernameErrStr = "* Username is taken";
                    }
                }
            }
        }

        /* E-mail not empty check */
        if(empty($_POST["reg-email-input"])) {
            $emailErrStr = "* Please enter an E-mail ID";
            $validationPassed = false;
        }
        else {
            $user_new_email = sf_test_input($_POST["reg-email-input"]);
            if(!filter_var($user_new_email, FILTER_VALIDATE_EMAIL)) {
                $emailErrStr = "* Invalid E-mail ID";
                $validationPassed = false;
            }
            else {
                if($user_new_email != $user_email) {
                    /* Already in database check */
                    if(sf_email_exists($user_new_email)) {
                        $validationPassed = false;
                        $emailErrStr = "* Email is already linked with an account";
                    }
                }
            }
        }

        /* Validate the profile photo if one is uploaded */
        if($_FILES["profile-pic-input"]["tmp_name"]) {
            $pic_extension = pathinfo($_FILES["profile-pic-input"]["name"], PATHINFO_EXTENSION);
            $pic_upload_path = "userdata/$sessionUserID/profile" . "." . $pic_extension;

            if($pic_extension != "jpg" and $pic_extension != "png") {
                $validationPassed = false;
                $picErrStr = "* Only .png and .jpg pictures are allowed";    
            }
        }

        if($validationPassed == true)
        {
            $updatedPic = $updatedInfo = false;

            /* Update the profile photo if one is uploaded */
            if($_FILES["profile-pic-input"]["tmp_name"]) {
                if(!is_dir("userdata/$sessionUserID")) {
                    mkdir("userdata/$sessionUserID");
                }
                move_uploaded_file($_FILES["profile-pic-input"]["tmp_name"], $pic_upload_path);
                $updatedPic = true;
            }

            /* Update the username and email if any one has been changed */
            if($user_new_username != $user_display_name or $user_new_email != $user_email) {
                // Run update query
                $query = "UPDATE users SET username=?, email=? WHERE id=?";
                sf_mysql_query($sql_conn, $query, "ssi", $user_new_username, $user_new_email, $sessionUserID);
                $_SESSION["user"] = $user_new_username;
                $updatedInfo = true;
                
                if($user_display_name != $user_new_username) {
                    $profile_log->info("Username changed from: '$user_display_name' to: '$user_new_username'");
                }
                if($user_email != $user_new_email) {
                    $profile_log->info("E-mail changed from: '$user_email' to: '$user_new_email'");
                }
            }
            
            if($updatedInfo) {
                $update_result_success = "Profile updated successfully.";
            }
            else if($updatedPic) {
                $update_result_success = "Picture updated successfully.";
            }
            else {
                $update_result_success = "Nothing to update";
            }
        }
        else {
            $update_result_err = "* Unable to update";
        }

        /* Update the form inputs to make them stick after a successful or failed attempt */
        $user_display_name = $user_new_username;
        $user_email = $user_new_email;
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Forum Profile</title>
        <link rel="stylesheet" href="<?php echo sf_timestamp_file("style.css"); ?>">
        <link rel="stylesheet" href="<?php echo sf_timestamp_file("styles/profile.css"); ?>">
        <script src="<?php echo sf_timestamp_file("components/username_form_validator.js"); ?>" defer></script>
    </head>

    <body>

    <header>
        <h1>Forum</h1>
    </header>

    <?php require_once "components/nav_bar.php"; ?>

    <main>
        <div class="general-container">
            <div class="general-container-title">
                Profile
            </div>
            <div class="general-container-content">
                <div class="profile-container">
                    <img class="profile-img" alt="profile-picture" src="<?php echo sf_get_profile_pic_url($_SESSION["user_id"]); ?>">

                    <form id="delete-pic-form" action="utils/sf_delete_profile_pic.php" method="post" onsubmit="window.location.reload(true);">
                    </form>

                    <form class="general-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="general-form-labels">
                            <label for="reg-username-input">Username:</label>
                            <label for="reg-email-input">E-mail:</label>
                            <label for="profile-pic-input">Picture:</label>
                            <label for="register-date-input">Registered on:</label>
                        </div>

                        <div class="general-form-inputs">
                            <input name="reg-username-input" id="reg-username-input" type="text" value="<?php echo $user_display_name; ?>">
                            <input name="reg-email-input" id="reg-email-input" type="text" value="<?php echo $user_email; ?>">
                            <input name="profile-pic-input" id="profile-pic-input" type="file">
                            <input name="register-date-input" id="register-date-input" type="text" value="<?php echo $user_register_date; ?>" disabled>
                            <button type="submit" form="delete-pic-form">Delete Picture</button>
                            <button type="submit" id="update-submit-button">Update</button>
                            
                            <div class="general-form-result">
                                <span class="general-form-error-msg"> <?php echo $update_result_err; ?> </span>
                                <span class="general-form-success-msg"> <?php echo $update_result_success; ?> </span>
                            </div>
                        </div>

                        <div class="general-form-errors">
                            <div class="general-form-error-msg" id="username-error-msg"><?php echo $usernameErrStr; ?></div>
                            <div class="general-form-error-msg"><?php echo $emailErrStr; ?></div>
                            <div class="general-form-error-msg"><?php echo $picErrStr; ?></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php require_once "components/footer.php"; ?>
    
    </body>
</html>
