<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Corrected variable name for password
$dbname = "sep";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if course ID is received
if (isset($_POST['courseId'])) {
    $courseId = $_POST['courseId'];

    // Prepare and execute the DELETE query using prepared statements
    $stmt = $conn->prepare("DELETE FROM course WHERE courseId = ?");
    $stmt->bind_param("i", $courseId);

    if ($stmt->execute()) {
        // Return success response
        echo json_encode(array('success' => true));
    } else {
        // Return error response
        echo json_encode(array('success' => false, 'error' => 'Failed to delete course.'));
    }
} else {
    // Return error response if course ID is not received
    echo json_encode(array('success' => false, 'error' => 'Course ID not provided.'));
}

// Close connection
$conn->close();
?>
