<?php
include("./inc/header.inc.php");
require_once './config/connection.php'; // Ensure this file sets up the PDO connection

// Check if the member is logged in
if (!isset($_SESSION['member_id'])) {
    header('Location: login.php');
    exit; // Ensure script termination after redirection
}

$MemberID = $_SESSION['member_id']; // Use the correct session variable
$Title = $_POST['book']; // Change $_POST['Title'] to $_POST['book']

$database = new Database();
$conn = $database->getConnection();

try {
    // Begin transaction
    $conn->beginTransaction();

    // Get the BookID based on the provided title
    $sql = "SELECT BookID FROM books WHERE Title = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$Title]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $BookID = $row['BookID'];

    if (!$BookID) {
        throw new Exception("Book not found.");
    }

    // Check if the book is currently on loan by this member
    $sql = "SELECT Status FROM book_status WHERE BookID = ? AND MemberID = ? AND Status = 'On Loan'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$BookID, $MemberID]);
    $status = $stmt->fetchColumn();

    if ($status !== 'On Loan') {
        throw new Exception("Book is not currently on loan by this member.");
    }

    // Update book availability in books table
    $sql = "UPDATE books SET is_available = TRUE WHERE BookID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$BookID]);

    // Update book_status table to set status to "Available" and clear the MemberID
    $sql = "UPDATE book_status SET Status = 'Available', MemberID = NULL WHERE BookID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$BookID]);

    // Update borrow record with return date
    $sql = "UPDATE borrow_records SET return_date = NOW() WHERE MemberID = ? AND BookID = ? AND return_date IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$MemberID, $BookID]);

    // Commit transaction
    $conn->commit();
    echo '<div class="text-center">Book returned successfully!<br>I hope you enjoyed your book!</div>';
} catch (Exception $e) {
    // Rollback transaction in case of an error
    $conn->rollBack();
    echo '<div class="text-center">Error: ' . $e->getMessage() . '</div>';
}
?>

<?php include("./inc/footer.inc.php"); ?>

