<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "file_storage";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$uniqueName = $_GET['id'] ?? '';
$fileData = null;

if ($uniqueName) {
    $sql = "SELECT original_name, unique_name, file_size, expires_at FROM files WHERE unique_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uniqueName);
    $stmt->execute();
    $result = $stmt->get_result();
    $fileData = $result->fetch_assoc();
    $stmt->close();
}

if (!$fileData) {
    http_response_code(404);
    die("<h1>File not found.</h1>");
}

$downloadLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/download.php?file=" . $fileData['unique_name'];
$conn->close();

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
    return $bytes;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Ready - <?php echo htmlspecialchars($fileData['original_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm" style="width: 100%; max-width: 500px;">
            <div class="card-body text-center">
                <h1 class="card-title text-success">File Ready!</h1>
                <p class="card-text text-muted mb-4">Share this link with your recipient.</p>
                
                <div class="file-details bg-light p-3 rounded mb-4">
                    <i class="fas fa-file-alt fa-2x text-secondary mb-2"></i>
                    <p class="mb-0"><strong>File:</strong> <?php echo htmlspecialchars($fileData['original_name']); ?></p>
                    <p class="mb-0"><strong>Size:</strong> <?php echo formatSizeUnits($fileData['file_size']); ?></p>
                    <p class="mb-0"><strong>Expires:</strong> <?php echo date('F j, Y', strtotime($fileData['expires_at'])); ?></p>
                </div>

                <div class="input-group mb-3">
                    <input type="text" id="download-link" class="form-control" value="<?php echo htmlspecialchars($downloadLink); ?>" readonly>
                    <button class="btn btn-outline-success" onclick="copyLink()">
                        <i class="fas fa-copy me-1"></i> Copy
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyLink() {
            var copyText = document.getElementById("download-link");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            alert("Download link copied to clipboard!");
        }
    </script>
</body>
</html>