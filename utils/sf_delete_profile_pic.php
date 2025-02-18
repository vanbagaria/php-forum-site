<?php
    /**
     * utils/sf_delete_profile_pic.php
     * This route when hit with a http post request will check if a user is logged in
     * and if so, delete that user's profile picture.
    */

    session_start();
    if(empty($_SESSION["user"])) {
        exit("Must be logged in!");
    }

    require_once "sf_get_profile_pic_url.php";

    $response = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $pic_path = sf_get_profile_pic_url($_SESSION["user_id"], false);
        if(strpos($pic_path, "default") === false) {
            unlink($_SERVER["DOCUMENT_ROOT"] . "/forum/" . $pic_path);
        }
        header("Location: /forum/profile.php");
    }
?>