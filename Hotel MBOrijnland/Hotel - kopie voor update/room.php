<?php
include 'db.php';

class Room {
    public $id;
    public $room_nr;
    public $room_type;
    public $room_information;
    public $price;
    public $image;

    public function __construct($room_id, $room_nr, $room_type, $room_information, $price, $image) {
        $this->id = $room_id;
        $this->room_nr = $room_nr;
        $this->room_type = $room_type;
        $this->room_information = $room_information;
        $this->price = $price;
        $this->image = $image;
    }

    public function display_summary() {
        $base64Image = base64_encode($this->image);
        $imageSrc = "data:image/jpeg;base64,{$base64Image}";

        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px auto; display: flex; align-items: center; max-width: 1000px;'>";
        echo "<a href='roomview_details.php?id={$this->id}'><img src='{$imageSrc}' alt='Room Image' style='width: 150px; height: 100px;'></a>";
        echo "<div style='margin-left: 10px;'>";
        echo "<p>Room Number: {$this->room_nr}</p>";
        echo "<p>Room Type: {$this->room_type}</p>";
        echo "<p>Room Information: {$this->room_information}</p>";
        echo "<p>Price: € {$this->price}</p>"; // Prepend € to the price
        echo "</div>";
        echo "</div>";
    }
}

// Query om alle kamers op te halen
$sql = "SELECT * FROM room";
$result = $conn->query($sql);

if ($result) {
    $rooms = $result->fetchAll(PDO::FETCH_ASSOC);

    if ($rooms) {
        // Process the results
        foreach ($rooms as $room) {
            $roomObject = new Room($room['id'], $room['room_nr'], $room['room_type'], $room['room_information'], $room['price'], $room['image']);
            $roomObject->display_summary();
        }
    } else {
        echo "Geen kamers gevonden.";
    }
} else {
    // Handle the case where the query failed
    echo "Er is een fout opgetreden bij het uitvoeren van de query.";
}

?>