<?php
    /**
     * This function does the following to the input data:
     * 1. Removes whitespace from the left and right of the input. (trim($data))
     * 2. Calls stripslashes($data)
     * 3. Calls htmlspecialchars($data)
     */
    function sf_test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>