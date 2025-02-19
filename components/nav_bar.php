<?php
    if(!isset($_SESSION)) {
        session_start();
    }
    $is_user_logged_in = !empty($_SESSION["user"]);

    function sf_nav_get_active_attr($nav_button) {
        if($_SERVER['PHP_SELF'] == $nav_button) {
            return "id=\"active-nav-button\"";
        }
        return "";
    }
?>

    <!-- Navigation bar include start -->
    <nav>
        <ul class="nav-left">
            <li><a <?php echo sf_nav_get_active_attr("/index.php"); ?> href="index.php">Home</a></li>
            <?php
                if($is_user_logged_in) {
                    $active_atr = sf_nav_get_active_attr("/profile.php");
                    echo "<li><a $active_atr href=\"profile.php\">Profile</a></li>\n\t\t\t";

                    $active_atr = sf_nav_get_active_attr("/settings.php");
                    echo "<li><a $active_atr href=\"settings.php\">Settings</a></li>\n\t\t\t";
                } else {
                    $active_atr = sf_nav_get_active_attr("/login.php");
                    echo "<li><a $active_atr href=\"login.php\">Log In</a></li>\n\t\t\t";
                    
                    $active_atr = sf_nav_get_active_attr("/register.php");
                    echo "<li><a $active_atr href=\"register.php\">Register</a></li>";
                }
                $active_atr = sf_nav_get_active_attr("/faq.php");
                echo "<li><a $active_atr href=\"faq.php\">FAQ</a></li>";
            ?>
        </ul>
        <ul class="nav-right">
            <li>
                <?php
                    if($is_user_logged_in) {
                        echo "<a id=\"nav_sign_out_link\" href=\"logout.php\">Log Out</a>";
                    }
                ?>
            </li>
        </ul>
    </nav>

    <!-- Navigation bar include end -->
    