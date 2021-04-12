// Emails
// When old email is input, prompt for new email
$(document).ready(function(){
    $("#old-email").on("input", function(){
        if($("#email").val() == ""){
            document.getElementById("email").style.borderColor = "red";
        }
        if($("#email-confirm").val() == ""){
            document.getElementById("email-confirm").style.borderColor = "red";
        }
    })
});

// If the new email field is changed, the confirm field gets a red border
$(document).ready(function(){
    $("#email").on("input", function(){
        document.getElementById("email").style.borderColor = "#ced4da";
        if($("#email").val() != "" && $("#email").val() != $("#email-confirm").val()){
          document.getElementById("email-confirm").style.borderColor = "red";
        } else {
          document.getElementById("email-confirm").style.borderColor = "#ced4da";
        }
    })
});

// The confirm email field loses its red border when email values equal
$(document).ready(function(){
    $("#email-confirm").on("input", function(){
        if($("#email").val() == $("#email-confirm").val()){
            document.getElementById("email-confirm").style.borderColor = "#ced4da";
        } else {
            document.getElementById("email-confirm").style.borderColor = "red";
        }
    })
});

// Passwords

// When old password is input, prompt for new password
$(document).ready(function(){
    $("#old-password").on("input", function(){
        if($("#password").val() == ""){
            document.getElementById("password").style.borderColor = "red";
        }
        if($("#password-confirm").val() == ""){
            document.getElementById("password-confirm").style.borderColor = "red";
        }
    })
});

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
    var mismatch_email = true;
    var mismatch_password = true;
    // If old email is filled in and new email is empty
    if($("#old-email").val() != "" && $("#email").val() == ""){
        event.preventDefault();
        alert("Please enter a new email or remove your old email");
    }

    // Email validation
    var email = $("#email").val();
    var emailconf = $("#email-confirm").val();
    if (email == emailconf) {
        mismatch_email = false;
    }

    // If old password is filled in and new password is empty
    if($("#old-password").val() != "" && $("#password").val() == ""){
        event.preventDefault();
        alert("Please enter a new password or remove your old password");
    }

    // Password validation
    var pass = $("#password").val();
    var passconf = $("#password-confirm").val();
    if (pass == passconf) {
        mismatch_password = false;
    }

    if (mismatch_email && mismatch_password) {
        event.preventDefault();
        alert("The new emails and new passwords you entered do not match");
    } else if (mismatch_email) {
        event.preventDefault();
        alert("The new emails you entered do not match");
    } else if (mismatch_password) {
        event.preventDefault();
        alert("The new passwords you entered do not match");
    } else {
        return true;
    }
}
