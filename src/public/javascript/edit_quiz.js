function updateThumbnail(uuid) {
    $("#thumbnail").attr("src", `${location.origin}/videos/${uuid}`);
    $("#video-box").attr("src", `${location.origin}/videos/${uuid}`);
}

function validate(event) {
    // Video upload check
    impVideo = document.getElementById("import-video");
    if ($("#video-id").val() == "") {
        event.preventDefault();
        alert("You must upload a video for the quiz");
        impVideo.style.borderColor = "red";
    } else {
        impVideo.style.borderColor = "#dfdfdf";
    }

    // Animal selection check
    aniInfo = document.getElementById("animal-info")
    if ($('input[name="animal-radio[]"]:checked').length == 0){
        event.preventDefault();
        alert("You must select an animal");
        aniInfo.style.borderColor = "red";
    } else {
        // Initially set to grey
        document.getElementById('animal-new').style.borderColor = "#dfdfdf";

        var animalRadio = document.querySelectorAll("[name='animal-radio[]']:checked");
        var radio = animalRadio[0];
        var id = String(radio.id).replace('a', 'animal');
        // If they selected a new animal but didn't input the animal name
        if (id == "animal-new" && document.getElementById(id).value == ""){
            event.preventDefault();
            alert("You have not filled in the animal information");
            aniInfo.style.borderColor = "red";
            document.getElementById(id).style.borderColor = "red";
        } else {
            aniInfo.style.borderColor = "#dfdfdf";
            document.getElementById('animal-new').style.borderColor = "#dfdfdf";
        }
    }

    // Behaviour check
    behInfo = document.getElementById("behaviour-info");
    if ($('input[name="behaviour-check[]"]:checked').length == 0){
        event.preventDefault();
        alert("You must select at least one correct behaviour");
        behInfo.style.borderColor = "red";
    } else {
        // Color all borders grey first
        var inputs = document.querySelectorAll("[name='behaviour-check[]']");
        for (var i = 0; i < inputs.length; i++){
            var current = inputs[i];
            var id = String(current.id).replace('b', 'box');
            document.getElementById(id).style.borderColor = "#dfdfdf";
        }
        // Check that selected behaviours aren't blank fields
        var checked_boxes = document.querySelectorAll("[name='behaviour-check[]']:checked");
        for (var i = 0; i < checked_boxes.length; i++) {
            var currentCheckbox = checked_boxes[i];
            var id = String(currentCheckbox.id).replace('b', 'box');
            input = document.getElementById(id);
            resp = input.value;
            if (resp == ""){
                event.preventDefault();
                alert("The selected correct behaviour must have an answer associated with it");
                behInfo.style.borderColor = "red";
                input.style.borderColor = "red";
                break;
            } else {
                input.style.borderColor = "#dfdfdf";
                behInfo.style.borderColor = "#dfdfdf";
            }
        }
    }

    // Interpretation check
    intInfo = document.getElementById("interpretation-info")
    if ($('input[name=interpretation-radio]:checked').length == 0){
        event.preventDefault();
        alert("You must select one correct interpretation");
        intInfo.style.borderColor = "red";
    } else {
        // Color all borders grey first
        var inputs = document.querySelectorAll("[name='interpretation-radio']");
        for (var i = 0; i < inputs.length; i++){
            var current = inputs[i];
            var id = String(current.id).replace('i', 'inter');
            document.getElementById(id).style.borderColor = "#dfdfdf";
        }

        var intRadio = document.querySelectorAll("[name='interpretation-radio']:checked");
        var radio = intRadio[0];
        var id = String(radio.id).replace('i', 'inter');
        // If the selected interpretation is not filled in
        if (document.getElementById(id).value == ""){
            event.preventDefault();
            alert("You have not filled in the correct interpretation");
            intInfo.style.borderColor = "red";
            document.getElementById(id).style.borderColor = "red";
        } else {
            intInfo.style.borderColor = "#dfdfdf";
            document.getElementById(id).style.borderColor = "#dfdfdf";
        }
    }
}
