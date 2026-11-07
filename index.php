
<?php
session_start();
include "db.php";

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$message = "";

if (isset($_GET['registered'])) {
    $message = "Registration successful! You can now login.";
}

if (isset($_POST['login'])) {
    $login_input = trim($_POST['login_input']);
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE email = ? OR username = ? LIMIT 1"
    );

    $stmt->bind_param("ss", $login_input, $login_input);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            header("Location: dashboard.php");
            exit();

        } else {
            $message = "Incorrect password.";
        }

    } else {
        $message = "Username or email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - SessionAuthSystem</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow p-4">

                <h2 class="text-center mb-4">User Login</h2>

                <?php if ($message != ""): ?>
                    <div class="alert alert-info">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <div class="mb-3">
                        <label>Username or Email</label>
                        <input
                            type="text"
                            name="login_input"
                            class="form-control"
                            placeholder="Enter username or email"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Enter password"
                            required
                        >
                    </div>

                    <button type="submit" name="login" class="btn btn-primary w-100">
                        Login
                    </button>

                </form>

                <p class="text-center mt-3">
                    Don't have an account?
                    <a href="register.php">Register here</a>
                </p>

            </div>

        </div>
    </div>
</div>

</body>
</html>
