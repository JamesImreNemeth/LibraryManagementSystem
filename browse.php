<?php
include ("./inc/header.inc.php");
require_once './config/connection.php';


// Check if the member is logged in
if (!isset($_SESSION['member_id'])) {
    header('Location: login.php');
    exit; // Ensure script termination after redirection
}

?>
<div class="container text-center"> <!-- Added container and text-center classes -->
    <div id="book-list">
        <?php
        // Fetch book data along with corresponding image data from the database
        $sql = "SELECT b.*, b.BookCover 
                FROM Books b";

        $result = $conn->query($sql);

        // Check if there are any books in the database
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="book card mb-3" data-book-id="' . $row['BookID'] . '">';

                // Output book image
                $imageData = base64_encode($row['BookCover']);
                echo "<img src='data:image/png;base64,$imageData' alt='Book Image' class='card-img-top book-cover'>";

                echo '<div class="card-body">';
                // Output book details
                $title = isset($row['Title']) ? $row['Title'] : 'Unknown Title';
                echo '<h2 class="card-title">' . $title . '</h2>';
                echo '<p class="card-text">Author: ' . $row['Author'] . '</p>';
                echo '<p class="card-text">Publisher: ' . $row['Publisher'] . '</p>';
                echo '<p class="card-text">Language: ' . $row['Language'] . '</p>';
                echo '<p class="card-text">Category: ' . $row['Category'] . '</p>';
                echo '<a href="borrow_form.php" class="btn btn-primary">Borrow</a>'; // Changed to anchor tag
                echo '</div>'; // close card-body
                echo '</div>'; // close card
            }
        } else {
            echo "0 results";
        }

        // Close the database connection
        $conn = null;
        ?>
    </div>
</div> <!-- End of container -->

<?php
include("./inc/footer.inc.php");
?>
