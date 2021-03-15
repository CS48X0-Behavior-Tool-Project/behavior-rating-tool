jQuery(function() {
    // Set CSRF token in header
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#upload-button").on('click', function(e) {
        e.preventDefault();
        var formData = new FormData($('#upload-form')[0]);

        $.ajax({
            type: 'POST',
            url: '/videos',
            cache: false,
            data: formData,
            success: (data) => updateUI(data, true),
            error: (data) => updateUI(data, false),
            contentType: false,
            processData: false
        });
    });
});

/**
 * Updates all UI elements related to video uploading accordingly
 *
 * @param {*} data Data map or error message
 * @param {*} success Whether the request was successful or not
 * @param {string} err Error message if received
 */
function updateUI(data, success) {
    updateAlert(success ? data['msg'] : data.statusText, success ? 'success' : 'danger');
    updateVideoID(data['uuid']);
    updateVideoName(data['name']);
    updateThumbnail(data['uuid']);
}

/**
 * Sets the alert box to display AJAX response
 *
 * @param {string} msg The message to be displayed in the alert box
 * @param {string} className Class of alert box to be adjusted
 */
function updateAlert(msg, className) {
    $("#upload-alert").text(msg);
    $("#upload-alert").show();
    $("#upload-alert").attr("class", `alert alert-${className}`);
}

/**
 * Updates ID input field to match uploaded video UUID
 *
 * @param {string} uuid UUID of uploaded video
 */
function updateVideoID(uuid) {
    $("#video-id").val(uuid);
}

/**
 * Updates name input field to match uploaded video name
 *
 * @param {string} name Name of uploaded video
 */
function updateVideoName(name) {
    $("#video-name").val(name);
}

function updateVideoLabel() {
    $("#file-label").text(`${$("#video-upload")[0].files[0].name}`);
}

function updateThumbnail(uuid) {
    $("#thumbnail").attr("src", `videos/${uuid}`);
}
