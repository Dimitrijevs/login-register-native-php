<div class="border-top">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 container">
        <p class="col-md-4 mb-0 text-body-secondary">© 2024 Company, Inc</p>

        <?php if (isset($_SESSION['user_id'])) : ?>
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="/views/profile.php" class="nav-link">Profile</a></li>

                <li class="nav-item"><a href="/php/auth.php?logout=true" class="nav-link">Log out</a></li>
            </ul>
        <?php endif; ?>
    </footer>
</div>