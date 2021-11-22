<?php
    require_once "utils/sf_timestamp_file.php";
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Forum</title>
        <link rel="stylesheet" href="<?php echo sf_timestamp_file("style.css"); ?>">
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
                Forums
            </div>
            <div class="forum-list-element">
                <a href="#">General</a>
            </div>
            <div class="forum-list-element">
                <a href="#">Games</a>
            </div>
            <div class="forum-list-element">
                <a href="#">Music</a>
            </div>
            <div class="forum-list-element">
                <a href="#">Animation</a>
            </div>
            <div class="forum-list-element">
                <a href="#">Game Development</a>
            </div>
            <div class="forum-list-element">
                <a href="#">Programming</a>
            </div>
            <div class="forum-list-element">
                <a href="#">Feedback and Suggestions</a>
            </div>
        </div>
    </main>

    <?php require_once "components/footer.php"; ?>
    
    </body>
</html>
