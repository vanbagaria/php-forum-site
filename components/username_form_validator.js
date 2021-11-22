/**
 * Including this script on a page that has the elements it refers to (reg-username-input,
 * username-error-msg, reg-submit-button) will attach an AJAX request to the reg-username-input that validates 
 * the username using the reg_form_validator.php script and sets the appropriate message in the username-error-msg element.
 * It will disable the reg-submit-button if validation fails.
 * It will set the boolean usernameIsValid that can be checked by other scripts.
 * It will enable the reg-submit-button if validation passes and the boolean passwordIsValid if defined, is true.
 */

/**
 * This flag can be used in other scripts on the same page
 */
var usernameIsValid = true;

var usernameInput = document.getElementById("reg-username-input");
usernameInput.addEventListener("keyup", validateUsername);

function validateUsername() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            switch(this.responseText) {
                case "EXISTS":
                    document.getElementById("username-error-msg").innerHTML = "* Username already exists";
                    document.getElementById("reg-submit-button").disabled = true;
                    usernameIsValid = false;
                break;
        
                case "INVALID":
                    document.getElementById("username-error-msg").innerHTML = "* Username is invalid!";
                    document.getElementById("reg-submit-button").disabled = true;
                    usernameIsValid = false;
                break;
                    
                case "VALID":
                    document.getElementById("username-error-msg").innerHTML = "";
                    usernameIsValid = true;
                    if(typeof passwordIsValid === 'undefined') {
                        document.getElementById("reg-submit-button").disabled = false;
                    }
                    else if(passwordIsValid){
                        document.getElementById("reg-submit-button").disabled = false;
                    }
                break;
            }
        }
    };
    xhttp.open("POST", "components/reg_form_validator.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("for=username&reg-username-input=" + usernameInput.value);
}