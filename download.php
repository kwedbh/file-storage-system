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

$uniqueName = $_GET['file'] ?? '';

if ($uniqueName) {
    $sql = "SELECT original_name, unique_name FROM files WHERE unique_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uniqueName);
    $stmt->execute();
    $result = $stmt->get_result();
    $fileData = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    if ($fileData) {
        $filePath = 'downloads/' . $fileData['unique_name'];

        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($fileData['original_name']) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            http_response_code(404);
            die("File not found.");
        }
    }
}

http_response_code(404);
die("Invalid file link.");
?>