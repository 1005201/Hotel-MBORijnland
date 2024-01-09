<?php
include('header.php');
include('db.php');

// Check if the user is already logged in and redirect if necessary
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $sql = "SELECT * FROM `user` WHERE `email` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        header("Location: index.php");
        exit();
    }else{
        echo 'Password or email is incorrect';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="login-form">
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <div class="mb-3">
            <input type="text" class="form-control" name="email" placeholder="email" required>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary" name="login">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
</div>


<?php include 'footer.php' ; ?>
</body>
</html>
