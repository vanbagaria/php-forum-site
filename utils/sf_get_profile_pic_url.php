<?php
    require_once "sf_timestamp_file.php";
    /**
     * Returns the url of the profile picture for a given user id
     * The pictures are stored as profile.png or profile.jpg in a folder with the same name
     * as the user id in the userdata folder
     * e.g. A png profile picture for user with id 13 is stored in: userdata/13/profile.png
     * If the file doesn't exist, then a default profile picture is returned.
     * The $timestamped boolean specifies whether to append a timestamp to the returned url
     * It is set to true by default.
     */
    function sf_get_profile_pic_url($user_id, $timestamped=true) {
        $default = "default/profile.png";
        $base_url = "userdata/$user_id/";
        
        if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/" . $base_url . "profile.jpg")) {
            if($timestamped === true) {
                return sf_timestamp_file($base_url . "profile.jpg");
            }
            else {
                return $base_url . "profile.jpg";
            }
        }

        if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/" . $base_url . "profile.png")) {
            if($timestamped === true) {
                return sf_timestamp_file($base_url . "profile.png");
            }
            else {
                return $base_url . "profile.jpg";
            }
        }

        if($timestamped === true) {
            return sf_timestamp_file($default);
        }
        else {
            return $default;
        }
    }
?>