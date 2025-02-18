<?php
    require_once "utils/sf_timestamp_file.php";
    require_once "utils/sf_mysql.php";

    if (isset($_GET['id'])) {
        $topic_id = intval($_GET['id']);
    } else {
        echo "No topic ID provided.";
    }

    $sql_conn = sf_mysql_connect();
    $query_get_topic = "SELECT topic_name FROM forum_topics WHERE id=?";
    $result = sf_mysql_query($sql_conn, $query_get_topic, 'i', $topic_id);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $topic_title = $row['topic_name'];
    }

    $query_get_threads = "SELECT id, title FROM forum_threads WHERE topic_id=?";
    $result = sf_mysql_query($sql_conn, $query_get_threads, 'i', $topic_id);
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?php echo $topic_title; ?></title>
        <link rel="stylesheet" href="<?php echo sf_timestamp_file("style.css"); ?>">
    </head>

    <body>

    <header>
        <h1>Forum</h1>
        <?php require_once "components/login_form.php"; ?>
    </header>

    <?php require_once "components/nav_bar.php"; ?>

    <main>
        <h2><?php echo $topic_title; ?></h2>
        <div class="general-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="forum-list-element">';
                    echo '<a href="thread.php?id=' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['title']) . '</a>';
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
