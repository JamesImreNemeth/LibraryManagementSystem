<?php
include("./inc/header.inc.php");
require_once './config/connection.php'; // Ensure this file sets up the PDO connection

// Check if the member is logged in
if (!isset($_SESSION['member_id'])) {
    header('Location: login.php');
    exit; // Ensure script termination after redirection
}

$MemberID = $_SESSION['member_id']; // Use the correct session variable

$database = new Database();
$conn = $database->getConnection();

// Fetch available books
$sql = "SELECT BookID, Title FROM books WHERE is_available = TRUE";
$stmt = $conn->prepare($sql);
$stmt->execute();
$availableBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$availableBooks) {
    echo '<div class="text-center">No books available for borrowing.</div>';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get selected book ID
    $selectedBookID = $_POST['book'];

    try {
        // Begin transaction
        $conn->beginTransaction();

        // Update book availability in books table
        $sql = "UPDATE books SET is_available = FALSE WHERE BookID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$selectedBookID]);

        // Update book_status table to set status to "On Loan"
        $sql = "UPDATE book_status SET Status = 'On Loan', MemberID = ? WHERE BookID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$MemberID, $selectedBookID]);

        // Calculate due date (21 days from now)
        $dueDate = date('Y-m-d H:i:s', strtotime('+21 days'));

        // Insert borrow record
        $sql = "INSERT INTO borrow_records (MemberID, BookID, due_date) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$MemberID, $selectedBookID, $dueDate]);

        // Commit transaction
        $conn->commit();
        echo '<div class="text-center">Book borrowed successfully!<br>Enjoy your book, remember the return due date is in 21 days from the day you borrowed your book.</div>';
    } catch (Exception $e) {
        // Rollback transaction in case of an error
        $conn->rollBack();
        echo '<div class="text-center">Error: ' . $e->getMessage() . '</div>';
    }
}
?>
