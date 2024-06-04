<?php
    include("./inc/header.inc.php");
    require_once './config/connection.php';

    // Check if the member is logged in
    if (!isset($_SESSION['member_id'])) {
        header('Location: login.php');
        exit; // Ensure script termination after redirection
    }
?>
    <div class="container">
        <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="img/library_1.jpg" class="d-block w-100" alt="Library Image 1">
                    <div class="carousel-caption d-none d-md-block">  
                        <h3>Welcome to Our Library!</h3>
                        <p>Explore a world of knowledge and discover your next favorite book.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="img/library_2.jpg" class="d-block w-100" alt="Library Image 2">
                    <div class="carousel-caption d-none d-md-block">
                        <h3>What you can achieve?</h3>
                        <p class="d-none d-sm-block">Choose from a variety of books to help with your courses and subjects.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="img/library_3.jpg" class="d-block w-100" alt="Library Image 3">
                    <div class="carousel-caption d-none d-md-block">
                        <h3>We are here to help!</h3>
                        <p class="d-none d-sm-block">If you need assistance to find your resources, please talk to one of our staff members.</p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

    </div>


        <!-- Featured Book Section -->
    <div id="featured-book" class="container mt-5">
        <h2>Featured Book</h2>
        <?php
        // Fetch the featured book from the database
        $sql_featured = "SELECT b.*, b.BookCover 
                FROM Books b
                WHERE b.BookID = 1"; // Fetching BookID 1

        $result_featured = $conn->query($sql_featured);

        // Check if the featured book exists
        if ($result_featured->rowCount() == 1) {
            $row_featured = $result_featured->fetch(PDO::FETCH_ASSOC);
            echo '<div class="book card mb-3" data-book-id="' . $row_featured['BookID'] . '">';

            // Output book image
            $imageData_featured = base64_encode($row_featured['BookCover']);
            echo "<img src='data:image/png;base64,$imageData_featured' alt='Book Image' class='card-img-top book-cover featured-book-image'>";

            echo '<div class="card-body">';
            // Output book details
            $title_featured = isset($row_featured['Title']) ? $row_featured['Title'] : 'Unknown Title';
            echo '<h2 class="card-title">' . $title_featured . '</h2>';
            echo '<p class="card-text">Author: ' . $row_featured['Author'] . '</p>';
            echo '<p class="card-text">Publisher: ' . $row_featured['Publisher'] . '</p>';
            echo '<p class="card-text">Language: ' . $row_featured['Language'] . '</p>';
            echo '<p class="card-text">Category: ' . $row_featured['Category'] . '</p>';
            echo '<a href="borrow_form.php" class="btn btn-primary">Borrow</a>'; // Changed to anchor tag
            echo '</div>'; // close card-body
            echo '</div>'; // close card
        } else {
            echo "Featured book not found";
        }
        ?>
    </div>

<?php include ("./inc/footer.inc.php"); ?>

