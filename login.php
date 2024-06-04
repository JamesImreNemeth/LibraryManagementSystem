<?php include("./inc/header.inc.php"); ?>

<div style="padding: 0 20px;"> <!-- Add padding here -->
    <?php
    // Display error message if set
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']); // Remove error message from session
    }
    ?>

    <!-- HTML form for login -->
    <form method="post" action="login_query.php">
        <div class="form-group">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <input type="submit" value="Login" class="btn btn-primary">
    </form>
</div>

<!-- Include the footer -->
<?php include("./inc/footer.inc.php"); ?>



