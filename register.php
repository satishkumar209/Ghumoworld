<?php
// Include the database connection file
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $location = $conn->real_escape_string($_POST['location']);
    $destination = $conn->real_escape_string($_POST['destination']);
    $password = $_POST['password']; // Get the raw password

    // IMPORTANT: Hash the password before storing it
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // SQL to check if user already exists
    $check_sql = "SELECT email FROM users WHERE email = '$email'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        echo "Error: Email already registered.";
    } else {
        // SQL to insert new user data
        $insert_sql = "INSERT INTO users (name, email, mobile, location, destination, password_hash)
                       VALUES ('$name', '$email', '$mobile', '$location', '$destination', '$password_hash')";

        if ($conn->query($insert_sql) === TRUE) {
            echo "Registration successful! <a href='login.html'>Go to Login</a>";
        } else {
            echo "Error during registration: " . $conn->error;
        }
    }
} else {
    // If accessed directly without POST
    echo "Invalid request method.";
}

$conn->close();
?>