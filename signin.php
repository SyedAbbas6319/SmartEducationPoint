<?php
//connect to db
$servername = "localhost";
$username = "root";
$pass = "";
$dbname = "sep";

$conn = new mysqli($servername, $username, $pass, $dbname);

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //get the email and password from the form
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];

    //check if the email and password are correct
    $sql = "SELECT * FROM signup WHERE email = '$Email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $saved_Password = $row["Password"];

        if ($Password == $saved_Password) {
            //email and password are correct, log the user in
            session_start();
            $_SESSION['Email'] = $Email;
            echo json_encode(array("success" => true));
            exit; // Make sure to exit after sending JSON response
        } else {
            //password is incorrect, display an error msg
            echo json_encode(array("error" => "Password is wrong"));
            exit; // Make sure to exit after sending JSON response
        }
    } else {
        // Email not found, display an error msg
        echo json_encode(array("error" => "Email not found"));
        exit; // Make sure to exit after sending JSON response
    }
}
?>
