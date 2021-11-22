<?php
    session_start();
    if(empty($_SESSION["user"])) {
?>

        <!-- Login Form Include Start -->
        <form id="login-form">
            <div class="login-input">
                <label for="username-input">Username:</label>
                <br>
                <input id="username-input" type="text">
            </div>
            <div class="login-input">
                <label for="password-input">Password:</label>
                <br>
                <input id="password-input" type="password">
            </div>
        </form>
        <br>
        <br>
        <button type="submit" onclick="validateLogin();">Log In</button>
        <span id="login-form-result"></span>

        <script>
            usernameLoginInput = document.getElementById("username-input");
            passwordLoginInput = document.getElementById("password-input");
            loginFormResult = document.getElementById("login-form-result");

            usernameLoginInput.addEventListener("keydown", submitOnEnterKeyDown);
            passwordLoginInput.addEventListener("keydown", submitOnEnterKeyDown);

            function submitOnEnterKeyDown(event) {
                console.log(event.key);
                if(event.key == "Enter") {
                    console.log("Validating login...");
                    validateLogin();
                }
            }

            function validateLogin() {
                if(usernameLoginInput.value == "") {
                    loginFormResult.innerHTML = "* Please enter a username";
                    return;
                }
                if(passwordLoginInput.value == "") {
                    loginFormResult.innerHTML = "* Please enter a password";
                    return;
                }

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if(this.readyState == 4 && this.status == 200) {
                        switch(this.responseText) {
                            case "USER_NOT_FOUND":
                                loginFormResult.innerHTML = "* Username does not exists";
                            break;
                    
                            case "WRONG_PASSWORD":
                                loginFormResult.innerHTML = "* Password is wrong";
                            break;
                                
                            default:
                                // On success, reload the current page
                                window.location.reload(true);
                            break;
                        }
                    }
                };

                xhttp.open("POST", "components/login_form_validator.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("username=" + usernameLoginInput.value + "&password=" + passwordLoginInput.value);
            }
        </script>
        <!-- Login Form Include End -->

<?php
    }
?>