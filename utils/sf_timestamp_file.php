<?php
    /**
     * Appends a timestamp to a given file path
     * e.g. Given the file path: "userdata/13/profile.png"
     * This function will return: "userdata/13/profile.png/?v=1636659727"
     * were 1636659727 will be the modification time of the given file.
     * This is useful to reflect changes in the file to clients with a cached version of the file
     * Note: Given filepath should be relative to the server root.
     */
    function sf_timestamp_file($file) {
        return $file . "?v=" . filemtime($_SERVER["DOCUMENT_ROOT"] . "/forum/" . $file);
    }
?>