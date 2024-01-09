<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>


<?php 
include('header.php'); 
include('db.php');
?>
<br>

<div class="login-form">
    <h2>Register</h2>
    <form action="register.php" method="POST">
        <div class="mb-3">
            <input type="text" class="form-control" name="firstname" placeholder="firstname" required>
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" name="lastname" placeholder="lastname" required>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
        </div>
        <div class="mb-3">
            <input type="email" class="form-control" name="email" placeholder="Email" required>
        </div>
        <button type="submit" class="btn btn-primary" name="register">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

<?php

if (isset($_POST['register'])) {
    // Use the database connection from db.php
    $firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
    $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    if ($password !== $confirm_password) {
        echo "Passwords do not match. Please try again.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Prepare and execute the SQL query using $pdo
        $sql = "INSERT INTO `user` (`firstname`, `lastname`, `email`, `password`) VALUES (:firstname, :lastname, :email, :password)";
        $stmt = $conn->prepare($sql);

       
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        // Execute the statement
        $stmt->execute();

        echo "Registration successful!";
    }
}
?>

<?php include 'footer.php';?>
</body>
</html>
