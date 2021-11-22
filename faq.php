<?php
    require_once "utils/sf_timestamp_file.php";
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Forum FAQ</title>
        <link rel="stylesheet" href="<?php echo sf_timestamp_file("style.css"); ?>">
        <link rel="stylesheet" href="<?php echo sf_timestamp_file("styles/faq.css"); ?>">
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
                Frequently Asked Questions
            </div>
            <div class="general-container-content">
                <h3># Account</h3>

                <div class="faq-question">
                    Q. Can I change my Username / Password / E-mail ?
                </div>

                <div class="faq-answer">
                    A. Log in and open the <a href="profile.php">Profile</a> page to change your username and e-mail address. <br>
                    Your old username will no longer work for logging in,
                    you will have to use your new username while logging into your account. <br>
                    The password can be changed from the <a href="settings.php">Settings</a> page.
                </div>
            </div>
        </div>
    </main>

    <?php require_once "components/footer.php"; ?>
    
    </body>
</html>
