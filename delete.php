<?php
include("./inc/header.inc.php");
require_once './config/connection.php'; // Ensure this file sets up the PDO connection

// Check database connection
if ($conn === false) {
    die("Error: Unable to connect to the database.");
}

// Function to alter foreign key constraints
function modifyForeignKeyConstraints($conn) {
    try {
        // Drop the existing foreign key constraint in book_status
        $sql = "ALTER TABLE book_status DROP FOREIGN KEY book_status_ibfk_1";
        $conn->exec($sql);

        // Add the new foreign key constraint with ON DELETE CASCADE in book_status
        $sql = "ALTER TABLE book_status
                ADD CONSTRAINT book_status_ibfk_1
                FOREIGN KEY (BookID) REFERENCES books(BookID)
                ON DELETE CASCADE";
        $conn->exec($sql);

        // Drop the existing foreign key constraint in borrow_records
        $sql = "ALTER TABLE borrow_records DROP FOREIGN KEY borrow_records_ibfk_2";
        $conn->exec($sql);

        // Add the new foreign key constraint with ON DELETE CASCADE in borrow_records
        $sql = "ALTER TABLE borrow_records
                ADD CONSTRAINT borrow_records_ibfk_2
                FOREIGN KEY (BookID) REFERENCES books(BookID)
                ON DELETE CASCADE";
        $conn->exec($sql);

    } catch (PDOException $e) {
        echo "Error modifying foreign key constraints: " . $e->getMessage();
    }
}

// Modify foreign key constraints
modifyForeignKeyConstraints($conn);

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
        try {
            // Begin transaction
            $conn->beginTransaction();

            // Get the BookID of the book to delete
            $sql = "SELECT BookID FROM books WHERE Title = :title";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['title' => $titleToDelete]);
            $bookId = $stmt->fetchColumn();

            if ($bookId) {
                // Delete the book from books table
                $sql = "DELETE FROM books WHERE BookID = :bookId";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['bookId' => $bookId]);

                // Commit transaction
                $conn->commit();
                echo "Book deleted successfully.";
            } else {
                echo "Error: Book not found.";
            }
        } catch (Exception $e) {
            // Rollback transaction if something goes wrong
            $conn->rollBack();
            echo "Error deleting book: " . $e->getMessage();
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
