<?php include("./inc/header.inc.php"); ?>

<div style="padding: 0 20px;"> <!-- Add padding here -->
    <!-- Display success and error messages if any -->
    <form method="post" action="register_query.php" name="registrationForm" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="firstname" class="form-label">First Name:</label>
            <input type="text" name="firstName" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="lastName" class="form-label">Last Name:</label>
            <input type="text" name="lastName" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="retypePassword" class="form-label">Re-type Password:</label>
            <input type="password" name="retypePassword" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="userType" class="form-label">User Type:</label>
            <select name="userType" class="form-control">
                <option value="user">Member</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <input type="submit" name="register" value="Register" class="btn btn-primary">
    </form>
</div>

<?php include("./inc/footer.inc.php"); ?>


