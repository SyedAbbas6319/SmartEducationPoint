<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sep";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process received course data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['courseName'], $_POST['courseTeacher'])) {
    // Handle form submission and database insertion
    $courseName = $_POST['courseName'];
    $courseTeacher = $_POST['courseTeacher'];
    $coursePrice = isset($_POST['coursePrice']) ? $_POST['coursePrice'] : 0;
    $videoOption = $_POST['videoOption'];
    $videoLectureLink = isset($_POST['videoLectureLink']) ? $_POST['videoLectureLink'] : '';
    $videoLectureFile = isset($_FILES['videoLectureFile']) ? $_FILES['videoLectureFile']['name'] : '';
    $courseIconFile = isset($_FILES['courseIconPath']) ? $_FILES['courseIconPath']['name'] : '';

    // Validate course data (additional validation may be required)
    if (empty($courseName) || empty($courseTeacher) || empty($courseIconFile)) {
        die("Course data is incomplete. Please fill in all required fields.");
    }

    // Handle course icon upload
    $targetDir = "uploads/icon/";
    $courseIconPath = $targetDir . basename($_FILES["courseIconPath"]["name"]);
    if (!move_uploaded_file($_FILES["courseIconPath"]["tmp_name"], $courseIconPath)) {
        die("Sorry, there was an error uploading your course icon file.");
    }

    // Handle video lecture based on the selected option
    if ($videoOption === 'link') {
        $videoLecturePath = $videoLectureLink; // If video link option is selected, use the provided link
    } else {
        // If video upload option is selected, move the uploaded file to the target directory
        $targetDir = "uploads/video/";
        $videoLecturePath = $targetDir . basename($_FILES["videoLectureFile"]["name"]);
        if (!move_uploaded_file($_FILES["videoLectureFile"]["tmp_name"], $videoLecturePath)) {
            die("Sorry, there was an error uploading your video file.");
        }
        // Set video lecture path for playback
        $videoLecturePath = $targetDir . basename($_FILES["videoLectureFile"]["name"]);
    }

    // Proceed with database insertion
    // Prepare and execute the INSERT query using prepared statements
    $stmt = $conn->prepare("INSERT INTO course (courseName, courseTeacher, courseIconPath, videoLecturePath, coursePrice) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $courseName, $courseTeacher, $courseIconPath, $videoLecturePath, $coursePrice);

    if ($stmt->execute()) {
        // Redirect to course.html after successful course addition
        header("Location: course.html");
        exit(); // Ensure that no further code is executed after redirection
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Fetch list of courses
$selectQuery = "SELECT * FROM course";
$result = $conn->query($selectQuery);
$courses = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = array(
            'courseId' => $row['courseId'],
            'courseIconPath' => $row['courseIconPath'],
            'courseName' => $row['courseName'],
            'courseTeacher' => $row['courseTeacher'],
            'videoLecturePath' => $row['videoLecturePath'],
            'coursePrice' => $row['coursePrice']
        );
    }
} else {
    echo "No courses found.";
}

// Return courses as JSON
header('Content-Type: application/json');
echo json_encode(array('courses' => $courses));

// Close connection
$conn->close();
?>
