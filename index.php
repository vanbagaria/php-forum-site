<?php
    require_once "utils/sf_timestamp_file.php";
    require_once "utils/sf_mysql.php";

    $query_get_topics = "SELECT id, topic_name FROM forum_topics";
    $sql_conn = sf_mysql_connect();
    $result = sf_mysql_query($sql_conn, $query_get_topics);
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
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="forum-list-element">';
                    echo '<a href="topic.php?id=' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['topic_name']) . '</a>';
                    echo '</div>';
                }
            } else {
                echo '<div class="forum-list-element">No topics available</div>';
            }
            ?>
        </div>
    </main>

    <?php require_once "components/footer.php"; ?>
    
    </body>
</html>
