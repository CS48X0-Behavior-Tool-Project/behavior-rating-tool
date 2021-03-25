function validate(event) {
    // Check that at least one behaviour is selected
    behInfo = document.getElementById("behaviour-info");
    if ($('input[name="behaviour-check[]"]:checked').length == 0) {
        event.preventDefault();
        alert("You must select at least one behaviour");
        behInfo.style.borderColor = "red";
    } else {
        behInfo.style.borderColor = "#dfdfdf";
    }

    // Check that an interpretation is selected
    intInfo = document.getElementById("interpretation-info");
    if ($('input[name=interpretation-check]:checked').length == 0){
        event.preventDefault();
        alert("You must select an interpretation");
        intInfo.style.borderColor = "red";
    } else {
        intInfo.style.borderColor = "#dfdfdf";
    }
}
