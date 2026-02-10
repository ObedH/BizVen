<?php
die("signup.php reached");
// Error handling for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Make sure uploads directory exists
$uploadDir = dirname(__DIR__, 2) . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Database file path
$dbFile = dirname(__DIR__, 2) . '/mydb.sqlite';

// Open or create SQLite database
$db = new SQLite3($dbFile);

// Create users table if it doesn't exist
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    email TEXT NOT NULL,
    password TEXT NOT NULL,
    role TEXT NOT NULL,
    certification_file TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get text inputs
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Validate form fields
    if (!$username || !$email || !$password || !$role) {
        die("Please fill in all the required fields.");
    }

    // File upload handling
    $uploadPath = null;
    if (isset($_FILES['certification']) && $_FILES['certification']['error'] === 0) {
        $fileTmpPath = $_FILES['certification']['tmp_name'];
        $fileName = basename($_FILES['certification']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];

        if (!in_array($fileExtension, $allowedExtensions)) {
            die("Invalid file type.");
        }

        // Create unique file name
        $newFileName = time() . '-' . $fileName;
        $uploadPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($fileTmpPath, $uploadPath)) {
            die("Error uploading the file.");
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

    // Execute
    if ($stmt->execute()) {
        echo "User registered successfully!";
    } else {
        echo "Error: " . $db->lastErrorMsg();
    }

} else {
    echo "Invalid request method.";
}
?>
