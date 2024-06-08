<footer class="bg-dark text-white py-4">
    <div class="container">
        <!-- Your footer content -->
        <?php
        // Check if the member is logged in
        if (isset($_SESSION['member_id'])) {
            // Assuming your session includes MemberType after login
            $memberType = $_SESSION['member_type'];

            // Provide access to admin portal if the member is an admin
            if ($memberType === 'Admin') {
                ?>
                <p><a href="admin_portal.php" class="text-white">Admin</a></p>
                <?php
            }
        }
        ?>
        <p class="mb-0">Copyright &copy; 2024 Australian University Library</p>
    </div>
</footer>
</div> <!-- End of the container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Custom JavaScript -->
<script src ="js/script.js"></script>
</body>
</html>


