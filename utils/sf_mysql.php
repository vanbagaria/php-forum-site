<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    $db_log = new Logger('Database');
    $db_log->pushHandler(new StreamHandler('logs/database_errors.log', Logger::ERROR));

    function sf_mysql_connect() {
        global $db_log;
        
        // Create DB connection (replace database credentials here)
        $sql_conn = mysqli_connect("db_host", "db_username", "db_password", "forum_db");

        // Check DB connection
        if (!$sql_conn) {
            $db_log->error("DB Connection failure: " . mysqli_connect_error());
            die("Database connection failed!");
        }

        return $sql_conn;
    }

    /* Returns a mysqli result set if one is produced, returns the no of rows affected if not */
    function sf_mysql_query($conn, $queryStr, $var = null, &...$vars) {
        global $db_log;

        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $queryStr);

        if ($var !== null) {
            if (empty($vars)) {
                mysqli_stmt_bind_param($stmt, $var);
            } else {
                mysqli_stmt_bind_param($stmt, $var, ...$vars);
            }
        }
        
        /* Kill the calling script on query failure */
        if(!mysqli_stmt_execute($stmt)) {
            $db_log->error("DB Query failure: " . mysqli_error($conn));
            mysqli_stmt_close($stmt);
            die("Database query failed!");
        }
        
        /* Check for result set */
        $result = mysqli_stmt_get_result($stmt);
        if(!$result) {
            $result = mysqli_stmt_affected_rows($stmt);
        }
        mysqli_stmt_close($stmt);
        return $result;
    }
?>