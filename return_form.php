<?php include ("./inc/header.inc.php"); 

require_once './config/connection.php'; // Include database connection file

// Check database connection
$database = new Database();
$conn = $database->getConnection();

// Check if the member is logged in
if (!isset($_SESSION['member_id'])) {
    header('Location: login.php');
    exit; // Ensure script termination after redirection
}

// Fetch available books
$sql = "SELECT BookID, Title FROM books WHERE is_available = FALSE";
$stmt = $conn->prepare($sql);
$stmt->execute();
$availableBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container">
    <div class="row justify-content-center"> <!-- Center the column -->
        <div class="col-md-6">
            <div class="text-center"> <!-- Center the content -->
                <?php if (!$availableBooks): ?>
                    <h4>You have not borrowed any books.</h4>
                <?php else: ?>
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Return Book</h3>
                            <form action="return.php" method="post">
                                <div class="form-group ">
                                    <label for="book">Select Book:</label>
                                    <select name="book" id="book" class="form-control form-select" required>
                                        <?php foreach ($availableBooks as $book): ?>
                                            <option value="<?php echo $book['Title']; ?>"><?php echo $book['Title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Return</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include ("./inc/footer.inc.php"); ?>

