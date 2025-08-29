<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sendspace Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm" style="width: 100%; max-width: 500px;">
            <div class="card-body text-center">
                <h1 class="card-title text-primary">Send Files Securely</h1>
                <p class="card-text text-muted mb-4">Share files easily with a temporary link.</p>
                
                <form id="upload-form" action="upload.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="fileToUpload" id="file-input" class="d-none" required>
                    <label for="file-input" class="file-upload-label d-flex flex-column align-items-center justify-content-center p-5 mb-4 border border-2 border-primary border-dashed rounded-3">
                        <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                        <span class="fw-bold">Click to Upload</span>
                    </label>
                    <p id="file-name-display" class="text-muted"></p>
                    <button type="submit" class="btn btn-primary w-100 mt-3" id="upload-btn" disabled>Upload File</button>
                </form>

                <div id="progress-container" class="mt-4" style="display:none;">
                    <div class="progress">
                        <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="progress-text" class="mt-2 fw-bold">0%</div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>