$(document).ready(function() {
    $('#file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        if (fileName) {
            $('#file-name-display').text('Selected file: ' + fileName).removeClass('text-muted');
            $('#upload-btn').prop('disabled', false);
        } else {
            $('#file-name-display').text('');
            $('#upload-btn').prop('disabled', true);
        }
    });

    $('#upload-form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        // Show progress bar
        $('#progress-container').show();
        $('#upload-btn').prop('disabled', true);

        $.ajax({
            url: 'upload.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(event) {
                    if (event.lengthComputable) {
                        var percent = Math.round((event.loaded / event.total) * 100);
                        $('#progress-bar').css('width', percent + '%').text(percent + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    window.location.href = data.url;
                } else {
                    alert('Upload failed: ' + data.message);
                    $('#progress-container').hide();
                    $('#upload-btn').prop('disabled', false);
                }
            },
            error: function() {
                alert('An error occurred during upload.');
                $('#progress-container').hide();
                $('#upload-btn').prop('disabled', false);
            }
        });
    });
});