<nav class="navbar navbar-expand-lg animation-downwards bg-color1 sticky-navbar">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item mx-3">
                    <a class="nav-link" href="homepage">Home</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="manageSubjects">Manage Subjects</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="manageSchedules">Manage Schedules</a>
                </li>
                <li class="nav-item mx-3">
                    <button class="nav-link" data-bs-toggle="modal" data-bs-target="#modalProfile">My
                        Profile</button>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-4">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <?php echo $user_data['user_name']; ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="../phpscripts/user-logout.php" class="nav-link text-dark py-0">Logout</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>