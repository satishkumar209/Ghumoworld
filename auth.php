<?php
// Start a session to manage user state (crucial for a login system)
session_start(); 
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // SQL to fetch user by email
    $sql = "SELECT id, password_hash, name FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $stored_hash = $user['password_hash'];

        // Verify the provided password against the stored hash
        if (password_verify($password, $stored_hash)) {
            // Success: Set session variables and redirect
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            echo "Login successful! Welcome, " . $user['name'] . ".";
            // In a real application, you would redirect: header("Location: dashboard.php");
        } else {
            // Failure: Password mismatch
            echo "Login failed: Invalid email or password.";
        }
    } else {
        // Failure: User not found
        echo "Login failed: Invalid email or password.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>