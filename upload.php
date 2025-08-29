<?php
// Set the content type header
header('Content-Type: application/json');

// Your database credentials and connection code
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "file_storage";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    // Return a JSON error response on failure
    http_response_code(500);
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(500);
        die(json_encode(['status' => 'error', 'message' => 'File upload error.']));
    }

    $originalName = $file['name'];
    $fileSize = $file['size'];
    $uniqueName = uniqid() . '_' . basename($originalName);
    $targetFile = 'downloads/' . $uniqueName;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        $expiresAt = date('Y-m-d H:i:s', strtotime('+7 days'));

        $sql = "INSERT INTO files (original_name, unique_name, file_size, expires_at) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $originalName, $uniqueName, $fileSize, $expiresAt);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        http_response_code(200);
        // This is the only output the script should have
        echo json_encode(['status' => 'success', 'url' => 'file.php?id=' . $uniqueName]);
        exit();

    } else {
        http_response_code(500);
        die(json_encode(['status' => 'error', 'message' => 'Failed to save file.']));
    }
} else {
    http_response_code(405);
    die(json_encode(['status' => 'error', 'message' => 'Invalid request method.']));
}  