<div class="border-bottom">
    <header class="d-flex flex-wrap justify-content-center py-3 container">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
            <span class="fs-4">Simple header</span>
        </a>

        <?php if (isset($_SESSION['user_id'])) : ?>
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="/views/profile.php" class="nav-link">Profile</a></li>

                <li class="nav-item"><a href="/php/auth.php?logout=true" class="nav-link">Log out</a></li>
            </ul>
        <?php endif; ?>
    </header>
</div>