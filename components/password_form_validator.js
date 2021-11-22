/**
 * Including this script on a page that has the elements it refers to (reg-password-input, reg-password-input-repeat, 
 * password-error-msg, reg-submit-button) will attach an AJAX request to the reg-password-input that validates 
 * the password using the reg_form_validator.php script and sets the appropriate message in the password-error-msg element.
 * This script will also check that the two password inputs match and disable the reg-submit-button if validation fails.
 * It will set the boolean passwordIsValid that can be checked by other scripts on the same page.
 * It will enable the reg-submit-button if validation passes and the boolean usernameIsValid if defined, is true.
 */

/**
 * This flag can be used in other scripts on the same page
 */
var passwordIsValid = true;

var passwordInput = document.getElementById("reg-password-input");
var passwordInputRepeat = document.getElementById("reg-password-input-repeat");
passwordInput.addEventListener("keyup", validatePassword);
passwordInputRepeat.addEventListener("keyup", validatePassword);

function validatePassword() {
    if(passwordInput.value != passwordInputRepeat.value) {
        document.getElementById("password-error-msg").innerHTML = "* Passwords don't match";
        document.getElementById("reg-submit-button").disabled = true;
        passwordIsValid = false;
    }
    else {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                switch(this.responseText) {
                    case "INVALID":
                        document.getElementById("password-error-msg").innerHTML = "* Password must have: <br> - at least 8 characters <br> - at least 1 uppercase letter, 1 lowercase letter, and 1 number <br> - can contain special characters like @ # $ & *";
                        document.getElementById("reg-submit-button").disabled = true;
                        passwordIsValid = false;
                    break;
                        
                    case "VALID":
                        document.getElementById("password-error-msg").innerHTML = "";
                        passwordIsValid = true;
                        if(typeof usernameIsValid === 'undefined') {
                            document.getElementById("reg-submit-button").disabled = false;
                        }
                        else if(usernameIsValid){
                            document.getElementById("reg-submit-button").disabled = false;
                        }
                    break;
                }
            }
        };
        xhttp.open("POST", "components/reg_form_validator.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("for=password&reg-password-input=" + passwordInput.value);
    }
}