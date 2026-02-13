<?php
// Error handling for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Make sure uploads directory exists
$uploadDir = '/var/www/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Database file path
$dbFile = '/var/www/database/mydb.sqlite';

// Open or create SQLite database
$db = new SQLite3($dbFile);

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get text inputs
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Validate form fields
    if (!$username || !$email || !$password || !$role) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all the required fields.']);
        exit;
    }

    // File upload handling
    $uploadPath = null;
    if (isset($_FILES['certification']) && $_FILES['certification']['error'] === 0) {
        $fileTmpPath = $_FILES['certification']['tmp_name'];
        $fileName = basename($_FILES['certification']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
        $maxFileSize = 5 * 1024 * 1024; // Max file size: 5MB

        // Check file size
        if ($_FILES['certification']['size'] > $maxFileSize) {
            echo json_encode(['status' => 'error', 'message' => 'File is too large. Maximum allowed size is 5MB.']);
            exit;
        }

        // Check allowed extensions
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file type.']);
            exit;
        }

        // Sanitize file name to remove any unsafe characters
        $newFileName = time() . '-' . preg_replace("/[^a-zA-Z0-9.-]/", "_", $fileName);
        $uploadPath = $uploadDir . $newFileName;

        // Move the uploaded file
        if (!move_uploaded_file($fileTmpPath, $uploadPath)) {
            echo json_encode(['status' => 'error', 'message' => 'Error uploading the file.']);
            exit;
        }
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prepare SQLite statement
    $stmt = $db->prepare("INSERT INTO users (username, email, password, role, certification_file) VALUES (:username, :email, :password, :role, :certification_file)");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);
    $stmt->bindValue(':role', $role, SQLITE3_TEXT);
    $stmt->bindValue(':certification_file', $uploadPath ?? '', SQLITE3_TEXT);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'User registered successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $db->lastErrorMsg()]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
