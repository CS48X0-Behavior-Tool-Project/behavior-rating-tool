// Passwords
// If the new password field is changed, the confirm field gets a red border
$(document).ready(function(){
    $("#password").on("input", function(){
        if($("#password").val() != "" && $("#password").val() != $("#password-confirm").val()){
          document.getElementById("password-confirm").style.borderColor = "red";
        } else {
          document.getElementById("password-confirm").style.borderColor = "#ced4da";
        }
    })
});

// The confirm password field loses its red border when password values equal
$(document).ready(function(){
    $("#password-confirm").on("input", function(){
        if($("#password").val() == $("#password-confirm").val()){
            document.getElementById("password-confirm").style.borderColor = "#ced4da";
        } else {
            document.getElementById("password-confirm").style.borderColor = "red";
        }
    })
});

// Validates that new email and password fields are equal
function validate(event) {
    var mismatch_password = true;

    // Password validation
    var pass = $("#password").val();
    var passconf = $("#password-confirm").val();
    if (pass == passconf) {
        mismatch_password = false;
    }

    if (mismatch_password) {
        event.preventDefault();
        alert("The new passwords you entered do not match");
    } else {
        return true;
    }
}
