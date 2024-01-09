<?php
include_once "function.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$manager = new Management($conn);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateSubmit'])) {
    // Call the updateProject method when the form is submitted
    $manager->updateProject($_POST['order_id']);
}

// Call the readProject method to fetch data for the form
$projects = $manager->readProject($_GET['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservations</title>
</head>
<body>

<h2>Edit Reservations</h2>
<?php foreach($projects as $project){?>
<form action="" method="post">
    <input type="hidden" name="order_id" value="<?php echo $project['id']; ?>">
    
    <label for="firstname">Firstname:</label>
    <input type="text" id="firstname" name="firstname" value="<?php echo $project['firstname']; ?>" required><br>

    <label for="lastname">lastname:</label>
    <input type="text" id="lastname" name="lastname" value="<?php echo $project['lastname']; ?>" required><br>

    <label for="room_nr">Kamer:</label>
    <input type="text" id="room_nr" name="room_nr" value="<?php echo $project["room_nr"]; ?>" required><br>

    <label for="reservation">Reservations:</label>
    <input type="text" id="reservation" name="reservation" value="<?php echo $project["reservation"]; ?>"><br>

    <label for="room_type">Personen:</label>
    <input type="text" id="room_type" name="room_type" value="<?php echo $project["room_type"]; ?>" readonly><br>

    <label for="check_in">Check-in:</label>
    <input type="date" id="check_in" name="check_in" value="<?php echo $project["check_in"]; ?>" required><br>

    <label for="check_out">check-out:</label>
    <input type="date" name="check_out" rows="4" value="<?php echo $project["check_out"]; ?>" required></input><br>

    
    <label for="clean">Clean:</label>
    <input type="text" name="clean" rows="4" value="<?php echo $project["clean"]; ?>" required><br>
    
    <label for="price">price:</label>
    <input type="text" name="price" rows="4" value="<?php echo $project["price"]; ?>" required><br>
    
    <label for="room_information">room information:</label><br>
    <textarea id="room_informantion" name="room_information" rows="4" required><?php echo $project["room_information"]; ?></textarea><br>

    <input type="submit" name="updateSubmit" value="Update Order">
</form>

</body>
</html>
<?php
}
?>