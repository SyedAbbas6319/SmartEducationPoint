<?php
header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sep";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Process received course data
if (isset($_POST['courseId'], $_POST['courseName'], $_POST['courseTeacher'], $_POST['videoLecturePath'])) {
    $courseId = $_POST['courseId'];
    $courseName = $_POST['courseName'];
    $courseTeacher = $_POST['courseTeacher'];
    $videoLecturePath = $_POST['videoLecturePath'];
    $coursePrice = isset($_POST['coursePrice']) ? $_POST['coursePrice'] : 0;

    // Validate course data
    if (empty($courseId) || empty($courseName) || empty($courseTeacher) || empty($videoLecturePath)) {
        echo json_encode(['success' => false, 'message' => 'Course data is incomplete. Please fill in all required fields.']);
        exit();
    }

    // Prepare and execute the UPDATE query using prepared statements
    $stmt = $conn->prepare("UPDATE course SET courseName = ?, courseTeacher = ?, videoLecturePath = ?, coursePrice = ? WHERE courseId = ?");
    if ($stmt) {
        $stmt->bind_param("ssssi", $courseName, $courseTeacher, $videoLecturePath, $coursePrice, $courseId);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Course updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error executing query: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error preparing statement: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Required data not received.']);
}

// Close connection
$conn->close();
?>
