<?php
include("./inc/header.inc.php");
require_once './config/connection.php'; // Ensure this file sets up the PDO connection

// Check database connection
if ($conn === false) {
    die("Error: Unable to connect to the database. ");
}

// Check if form is submitted for deleting
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $titleToDelete = $_POST["title"];

    // Check if the book is currently on loan
    $sql = "SELECT bs.Status FROM books b JOIN book_status bs ON b.BookID = bs.BookID WHERE b.Title = :title";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['title' => $titleToDelete]);
    $status = $stmt->fetchColumn();

    if ($status == 'On Loan') {
        echo "Error: This book is currently on loan and cannot be deleted.";
    } else {
        // Build SQL query for deleting the book
        $sql = "DELETE FROM books WHERE Title = :title";
        
        // Prepare and execute the SQL query
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute(['title' => $titleToDelete]);

        // Check if deletion was successful
        if ($result) {
            echo "Book deleted successfully.";
        } else {
            echo "Error deleting book.";
        }
    }
}
?>

<!-- Delete book form -->
<div class="container mt-3">
    <h3>Delete Book</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="mb-3">
            <label for="title" class="form-label">Select Book:</label>
            <select name="title" id="title" class="form-select">
                <?php
                // Fetch and display titles of available books from the database
                $sql = "SELECT Title FROM books";
                $stmt = $conn->query($sql);

                // Check if query was executed
                if ($stmt === false) {
                    die("Error executing the query: ");
                }

                // Fetch books
                $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Check if books are fetched successfully
                if ($books) {
                    // Loop through fetched books and create options for the select dropdown
                    foreach ($books as $book) {
                        echo "<option value='" . $book['Title'] . "'>" . $book['Title'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No books found</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
    </form>
</div>

<?php include("./inc/footer.inc.php"); ?>
