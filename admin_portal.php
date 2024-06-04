<?php include("./inc/header.inc.php"); 
require_once './config/connection.php';

// Check database connection
if ($conn === false) {
    die("Error: Unable to connect to the database. ");
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables to store form data
    $title = $_POST["title"];
    $new_title = $_POST["new_title"];
    $author = $_POST["author"];
    $publisher = $_POST["publisher"];
    $language = $_POST["language"];
    $category = $_POST["category"];

    // Build SQL query
    $sql = "UPDATE books SET ";
    $params = array();

    // Check each field and add it to the query if it's not empty
    if (!empty($new_title)) {
        $sql .= "Title = :new_title, ";
        $params['new_title'] = $new_title;
    }
    if (!empty($author)) {
        $sql .= "Author = :author, ";
        $params['author'] = $author;
    }
    if (!empty($publisher)) {
        $sql .= "Publisher = :publisher, ";
        $params['publisher'] = $publisher;
    }
    if (!empty($language)) {
        $sql .= "Language = :language, ";
        $params['language'] = $language;
    }
    if (!empty($category)) {
        $sql .= "Category = :category, ";
        $params['category'] = $category;
    }

    // Remove the trailing comma and space from the SQL query
    $sql = rtrim($sql, ", ");

    // Add WHERE clause to specify the book
    $sql .= " WHERE Title = :title";

    // Prepare and execute the SQL query
    $stmt = $conn->prepare($sql);
    $params['title'] = $title;
    $result = $stmt->execute($params);

    // Check if update was successful
    if ($result) {
        echo "Book updated successfully.";
    } else {
        echo "Error updating book: ";
    }
}
?>

<!-- Edit book form -->
<div class="container mt-3">
    <h3>Edit Book</h3>
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
        <!-- Rest of the form fields -->
        <div class="mb-3">
            <label for="new_title" class="form-label">New Title:</label>
            <input type="text" id="new_title" name="new_title" class="form-control">
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Author:</label>
            <input type="text" id="author" name="author" class="form-control">
        </div>
        <div class="mb-3">
            <label for="publisher" class="form-label">Publisher:</label>
            <input type="text" id="publisher" name="publisher" class="form-control">
        </div>
        <div class="mb-3">
            <label for="language" class="form-label">Language:</label>
            <select name="language" id="language" class="form-select">
                <option value="English">English</option>
                <option value="French">French</option>
                <option value="German">German</option>
                <option value="Mandarin">Mandarin</option>
                <option value="Japanese">Japanese</option>
                <option value="Russian">Russian</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category:</label>
            <select name="category" id="category" class="form-select">
                <option value="Fiction">Fiction</option>
                <option value="Non Fiction">Non Fiction</option>
                <option value="Reference">Reference</option>
            </select>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        <a href="delete.php" class="btn btn-danger">Delete a Book</a>
    </form>
</div>

<?php include("./inc/footer.inc.php"); ?>




