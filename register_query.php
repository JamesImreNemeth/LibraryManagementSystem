<?php
session_start();
require_once './config/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = isset($_POST["firstName"]) ? $_POST["firstName"] : "";
    $last_name = isset($_POST["lastName"]) ? $_POST["lastName"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";
    $confirmPassword = isset($_POST["retypePassword"]) ? $_POST["retypePassword"] : "";
    $userType = isset($_POST["userType"]) ? $_POST["userType"] : "";

    // Check if email already exists in the database
    $stmt_check_email = $conn->prepare("SELECT * FROM members WHERE Email = ?");
    $stmt_check_email->execute([$email]);
    $existing_member = $stmt_check_email->fetch(PDO::FETCH_ASSOC);

    if ($existing_member) {
        $_SESSION['error_message'] = "An account with this email address already exists.";
        header("Location: registration.php");
        exit;
    }

    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Both email and password are required.";
        header("Location: registration.php");
        exit;
    }

    // Hash the password before storing it in the database
    $stored_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if password matches confirm password
    if ($password !== $confirmPassword) {
        $_SESSION['error_message'] = "Passwords do not match. Please try again.";
        header("Location: registration.php");
        exit;
    }

    // Prepare and execute the SQL query to insert user data
    $stmt = $conn->prepare("INSERT INTO members (FirstName, LastName, Email, Password, MemberType) VALUES (?, ?, ?, ?, ?)");
    $stmt->bindParam(1, $first_name);
    $stmt->bindParam(2, $last_name);
    $stmt->bindParam(3, $email);
    $stmt->bindParam(4, $stored_password);
    $stmt->bindParam(5, $userType);

    if ($stmt->execute()) {
        // Redirect to confirmation page after successful registration
        $_SESSION['success_message'] = "Registration successful!";
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Registration failed. Please try again later.";
        header("Location: registration.php");
        exit;
    }
} else {
    header("Location: registration.php");
    exit;
}
?>
