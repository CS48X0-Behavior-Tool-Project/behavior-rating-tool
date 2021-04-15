var formSubmission = false;
var setFormSubmission = function() { formSubmission = true; }

window.onload = function() {
    window.addEventListener("beforeunload", function(e) {
        if (formSubmission) return undefined;

        var confirmation = 'Leaving the page will lose all current progress.';
        (e || window.event).returnValue = confirmation;
        return confirmation;
    });
}