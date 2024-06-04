<?php 
include ("./inc/header.inc.php"); 
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
$sql = "SELECT BookID, Title FROM books WHERE is_available = TRUE";
$stmt = $conn->prepare($sql);
$stmt->execute();
$availableBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Borrow book form -->
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Borrow Book</h3>
                    <form method="post" action="borrow.php">
                        <div class="form-group">
                            <label for="book">Select Book:</label>
                            <select name="book" id="book" class="form-control form-select" >
                                <?php foreach ($availableBooks as $book): ?>
                                    <option value="<?php echo $book['BookID']; ?>"><?php echo $book['Title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Borrow</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("./inc/footer.inc.php"); ?>

