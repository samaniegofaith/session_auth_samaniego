
<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $_SESSION = array();
    session_destroy();

    header("Location: index.php");
    exit();
}

$username = $user['username'];
$email = $user['email'];
$created_at = $user['created_at'];
$current_session = session_id();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - SessionAuthSystem</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .welcome-section {
            padding-top: 75px;
            padding-bottom: 55px;
        }

        .welcome-section h1 {
            color: #4682b4;
            font-size: 42px;
            font-weight: bold;
        }

        .welcome-section p {
            color: #777;
            font-size: 19px;
        }

        .explore-btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
        }

        .explore-btn:hover {
            background-color: #287bb5;
            color: white;
        }

        .info-section {
            padding: 30px 0;
            border-top: 1px solid #e5e5e5;
        }

        .info-section h5 {
            color: #607d8b;
            margin-bottom: 15px;
        }

        .info-section p {
            color: #777;
            font-size: 14px;
        }

        .session-id {
            word-break: break-all;
            font-size: 13px;
        }

        .footer-note {
            margin-top: 35px;
            color: #888;
            font-size: 14px;
        }
    </style>
</head>

<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">

        <a class="navbar-brand" href="dashboard.php">
            SessionAuthSystem
        </a>

        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">

            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">
                        Dashboard
                    </a>
                </li>
            </ul>

            <span class="navbar-text text-white me-3">
                <?php echo htmlspecialchars($username); ?>
            </span>

            <a href="logout.php" class="btn btn-outline-light btn-sm">
                Logout
            </a>

        </div>
    </div>
</nav>


<!-- Welcome Section -->
<div class="container">

    <section class="welcome-section">

        <h1>
            Welcome,
            <?php echo htmlspecialchars($username); ?>!
        </h1>

        <p>
            This page is protected and only visible to logged-in users.
        </p>

        <a href="#dashboardInfo" class="btn explore-btn">
            Explore Dashboard
        </a>

    </section>


    <!-- Dashboard Information -->
    <section class="info-section" id="dashboardInfo">

        <div class="row">

            <!-- Account Status -->
            <div class="col-md-4 mb-4">

                <h5>
                    ♡ Account Status
                </h5>

                <p>
                    Your session is active and protected against
                    Session Fixation using
                    <strong>session_regenerate_id(true)</strong>.
                </p>

            </div>


            <!-- User Details -->
            <div class="col-md-4 mb-4">

                <h5>
                    ▣ User Details
                </h5>

                <p>
                    <strong>User ID:</strong>
                    <?php echo htmlspecialchars((string)$user_id); ?>
                </p>

                <p>
                    <strong>Username:</strong>
                    <?php echo htmlspecialchars($username); ?>
                </p>

            

            </div>


            <!-- active session ID -->
            <div class="col-md-4 mb-4">

                <h5>
                    Active Session ID
                </h5>

                <p>
                    <strong>Current Session ID:</strong>
                </p>

                <p class="session-id">
                    <?php echo htmlspecialchars($current_session); ?>
                </p>

                <p>
                    <small>
                        Generated after login and session regeneration.
                    </small>
                </p>

            </div>

        </div>

    </section>


    <!-- Footer Note -->
    <div class="footer-note">

        <p>
            You can log out and log back in to see session regeneration in action.
        </p>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
