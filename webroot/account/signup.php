<?php
// Error handling (optional for debugging)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get text inputs
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    
    // File upload handling
    if (isset($_FILES['certification']) && $_FILES['certification']['error'] == 0) {
        $fileTmpPath = $_FILES['certification']['tmp_name'];
        $fileName = $_FILES['certification']['name'];
        $fileSize = $_FILES['certification']['size'];
        $fileType = $_FILES['certification']['type'];
        
        // Define allowed file types
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        
        // Check if the file has an allowed extension
        if (in_array(strtolower($fileExtension), $allowedExtensions)) {
            $uploadDir = 'uploads/';  // The directory where the file will be uploaded
            $newFileName = time() . '-' . $fileName;  // Create a unique file name
            $uploadPath = $uploadDir . $newFileName;

            // Move the file to the upload directory
            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                echo "File has been uploaded successfully: " . $newFileName . "<br>";
            } else {
                echo "Error uploading the file.";
            }
        } else {
            echo "Invalid file type.";
        }
    }
    
    // Validate form fields
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        echo "Please fill in all the required fields.";
    } else {
        // Hash password for security
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Save the data to the database (replace with your actual DB connection logic)
        // Example: MySQL (MySQLi)
        $conn = new mysqli('localhost', 'username', 'password', 'database_name');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, certification_file) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $hashedPassword, $role, $uploadPath);

        // Execute the query
        if ($stmt->execute()) {
            echo "User registered successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the connection
        $stmt->close();
        $conn->close();
    }
} else {
    // If not a POST request, show the form
    echo "Invalid request method.";
}
?>
