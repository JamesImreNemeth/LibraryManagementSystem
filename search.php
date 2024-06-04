<?php
require_once './config/connection.php'; // Include the connection.php file

// Get the search query
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Perform the search if the query is not empty
if (!empty($query)) {
    // Prepare and execute the search query
    $sql = "SELECT * FROM books WHERE Title LIKE :query OR Author LIKE :query";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $query . "%";
    $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();

    // Fetch all the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $results = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Display all the results -->
    <div class="container mt-5">
        <h1>Search Results</h1>
        <?php if (!empty($query) && count($results) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publisher</th>
                        <th>Language</th>
                        <th>Category</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Title']); ?></td>
                            <td><?php echo htmlspecialchars($row['Author']); ?></td>
                            <td><?php echo htmlspecialchars($row['Publisher']); ?></td>
                            <td><?php echo htmlspecialchars($row['Language']); ?></td>
                            <td><?php echo htmlspecialchars($row['Category']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No results found for "<?php echo htmlspecialchars($query); ?>"</p>
        <?php endif; ?>
        <a href="index.php" class="btn btn-primary mt-3">Back to Home</a>
    </div>
</body>
</html>
