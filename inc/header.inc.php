<?php
session_start();

// Check if the user is logged in and the session is active
if (isset($_SESSION['member_id'])) {
    // Set the session expiration time (2 hours) if not already set
    if (!isset($_COOKIE['expirationTime'])) {
        $expirationTime = time() + 7200;
        setcookie("expirationTime", $expirationTime, $expirationTime, "/");
    }

    // Check if the session started more than 2 hours ago
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 7200)) {
        // Unset all of the session variables
        $_SESSION = array();
        // Destroy the session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        // Destroy the session
        session_destroy();
        // Destroy the expiration time cookie
        setcookie("expirationTime", '', time() - 42000, '/');
        // Redirect to the login page or display a message indicating session expiration
        header("Location: login.php");
        exit;
    }
    // Update last activity time stamp
    $_SESSION['LAST_ACTIVITY'] = time();
}
?>

<!-- Header Boiler plate -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Australian University Library</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="inc/style.css"> <!-- Adjusted path to your custom CSS file -->
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom JavaScript -->
    <script src ="inc/script.js"></script>
</head>

<body>
    <div class="container">
        <!-- Start of the container -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Australian University Library</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="browse.php">Browse</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="borrow_form.php">Borrow</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="return_form.php">Return</a>
                        </li>
                    </ul>
                    <form class="d-flex ms-auto" action="search.php" method="GET">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="query">
                        <button class="btn btn-outline-light" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
        <header>
            <?php 
            if (isset($_SESSION['member_id'])): 
            ?>
                <p class="mt-2">Welcome, <?php echo htmlspecialchars($_SESSION['member_firstname']) . ' ' . htmlspecialchars($_SESSION['member_lastname']); ?>! <a href="logout.php">Logout</a></p>
                <div id="timer">Session Expires In: 02:00:00</div>
                <script>
                    // Function to update the timer every second
                    function updateTimer() {
                        var now = new Date().getTime();
                        var expirationTime = getExpirationTime();
                        var distance = expirationTime - now;

                        if (distance <= 0) {
                            // Timer expired
                            clearInterval(timerInterval);
                            document.getElementById("timer").innerHTML = "Session Expired";
                            return;
                        }

                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        // Pad single digits with leading zeros
                        hours = String(hours).padStart(2, '0');
                        minutes = String(minutes).padStart(2, '0');
                        seconds = String(seconds).padStart(2, '0');

                        document.getElementById("timer").innerHTML = "Session Expires In: " + hours + ":" + minutes + ":" + seconds;
                    }

                    // Function to get the expiration time from the cookie
                    function getExpirationTime() {
                        var cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)expirationTime\s*\=\s*([^;]*).*$)|^.*$/, "$1");
                        return cookieValue ? parseInt(cookieValue) * 1000 : 0;
                    }

                    // Update the timer immediately
                    updateTimer();

                    // Update the timer every second
                    var timerInterval = setInterval(updateTimer, 1000);
                </script>
            <?php else: ?>
                <p>
                    <a href="login.php" class="btn btn-primary">Login</a>
                    <a href="registration.php" class="btn btn-secondary">Register</a>
                </p>
            <?php endif; ?>
        </header>
    </div>
    <!-- End of the container -->
