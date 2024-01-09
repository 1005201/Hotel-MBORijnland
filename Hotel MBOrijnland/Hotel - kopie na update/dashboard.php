<?php
include "function.php";
require "header.php";
$manager = new Management($conn);

// Call the readProject method directly
$projects = $manager->readProject();
echo '<table border="1">';
echo '<tr>
        <th>Room Number</th>
        <th>Reserved</th>
        <th>People</th>
        <th>Check-in</th>
        <th>Check-out</th>
        <th>Cleaned</th>
        <th>Price</th>
        <th>Room Information</th>
        <th>Name</th>
        <th>Edit</th>
    </tr>';

foreach ($projects as $project) {
    echo '<tr>
        <td>' . $project["room_nr"] . '</td> ';

if ($project["reservation"] == 1) {
    echo '<td>empty</td>';
} else {
    echo '<td>Reserved</td>';
}

echo '<td>' . $project["room_type"] . '</td>
      <td>' . $project["check_in"] . '</td>
      <td>' . $project["check_out"] . '</td>';

if ($project["clean"] == 1) {
    echo '<td>dirty</td>';
} else {
    echo '<td>cleaned</td>';
}

echo '<td>' . $project["price"] . '</td>
      <td>' . $project["room_information"] . '</td>
      <td>' . $project["firstname"] . ' ' . $project["lastname"] . '</td>
      <td>
      <a href="edit.php?id='. $project['id'] .'">Edit</a></td>
      </tr>';

}
echo '</table>';
// require "footer.php";
?>
