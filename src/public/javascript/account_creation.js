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

// Anticipated grad year
$(document).ready(function(){
    $("#gradYearSelect").on("input", function(){
        if($("#gradYearSelect").val() == "Select your anticipated grad year"){
            document.getElementById("gradYearSelect").style.borderColor = "red";
        } else {
            document.getElementById("gradYearSelect").style.borderColor = "#ced4da";
        }
    })
});

// Validates that new email and password fields are equal
function validate(event) {
    if($("#gradYearSelect").val() == "Select your anticipated grad year"){
        event.preventDefault();
        alert("You must select an anticipated grad year. Select N/A if you are not a graduating student.");
    }

    var mismatch_password = true;

    // Password validation
    var pass = $("#password").val();
    var passconf = $("#password-confirm").val();
    if (pass == passconf) {
        mismatch_password = false;
    }

    if (mismatch_password) {
        event.preventDefault();
        console.log("not a match");
        alert("The new passwords you entered do not match");
    } else {
        return true;
    }
}
