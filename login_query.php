<?php
session_start();
require_once './config/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Both fields are required.";
        header("Location: login.php");
        exit;
    }

    // Prepare and execute the SQL query to fetch user data
    $stmt = $conn->prepare("SELECT MemberID, FirstName, LastName, Password, MemberType FROM members WHERE Email = ?");
    $stmt->bindParam(1, $email);
    $stmt->execute();
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($member) {
        // Debug outputs
        echo "Hashed Password: " . $member['Password'] . "<br>";
        echo "Submitted Password: " . $password . "<br>";
        
        if (password_verify($password, $member['Password'])) {
            // Password is correct, start a session
            $_SESSION['member_id'] = $member['MemberID'];
            $_SESSION['member_email'] = $email;
            $_SESSION['member_firstname'] = $member['FirstName'];
            $_SESSION['member_lastname'] = $member['LastName'];
            $_SESSION['member_type'] = $member['MemberType'];
            
            // Optional: Set a success message
            $_SESSION['success_message'] = "Login successful! Welcome back.";

            // Redirect to the dashboard or home page
            header("Location: index.php");
            exit; // Ensure script termination after redirection
        } else { 
            $_SESSION['error_message'] = "Invalid email or password. Please try again.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid email or password. Please try again.";
    }
    // Redirect to the login page
    header("Location: login.php");
    exit; // Ensure script termination after redirection
} else {
    header("Location: login.php");
    exit; // Ensure script termination after redirection
}
?>
