<?php

class room_details
{
    public $id;
    public $room_nr;
    public $reservation;
    public $room_type;
    public $room_information;
    public $check_in;
    public $check_out;
    public $price;
    public $image;

    public function __construct($room_id, $room_nr, $reservation, $room_type, $room_information, $check_in, $check_out, $price, $image)
    {
        $this->id = $room_id;
        $this->room_nr = $room_nr;
        $this->reservation = $reservation;
        $this->room_type = $room_type;
        $this->room_information = $room_information;
        $this->check_in = $check_in;
        $this->check_out = $check_out;
        $this->price = $price;
        $this->image = $image;
    }

    public function display_summary()
    {
        $base64Image = base64_encode($this->image);
        $imageSrc = "data:image/jpeg;base64,{$base64Image}";

        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px auto; display: flex; align-items: center; max-width: 1000px;'>";
        echo "<a href='roomview_details.php?id={$this->id}'><img src='{$imageSrc}' alt='Room Image' style='width: 350px; height: 300px;'></a>";
        echo "<div style='margin-left: 10px;'>";
        echo "<p>Room Number: {$this->room_nr}</p>";
        echo "<p>Reservation: {$this->reservation}</p>";
        echo "<p>Room Type: {$this->room_type}</p>";
        echo "<p>Room Information: {$this->room_information}</p>";
        echo "<p>Check-in: {$this->check_in}</p>";
        echo "<p>Check-out: {$this->check_out}</p>";
        echo "<p>Price: € {$this->price}</p>"; // Prepend € to the price
        echo "</div>";
        echo "</div>";
    }
}

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    $requestedRoomId = $_GET['id'];

    // Query to fetch room details for the specified ID
    $sql = "SELECT * FROM room WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $requestedRoomId, PDO::PARAM_INT);
    $stmt->execute();

    $roomToDisplay = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($roomToDisplay) {
        $roomObject = new Room_details($roomToDisplay['id'], $roomToDisplay['room_nr'], $roomToDisplay['reservation'], $roomToDisplay['room_type'], $roomToDisplay['room_information'], $roomToDisplay['check-in'], $roomToDisplay['check-out'], $roomToDisplay['price'], $roomToDisplay['image']);
        $roomObject->display_summary();
    } else {
        echo "Geen kamer detail gevonden.";
    }
} else {
    // Handle the case where the query failed
    echo "Er is een fout opgetreden bij het uitvoeren van de query.";
}

// Function to check room availability for a given date range
function isRoomAvailable($room_id, $check_in, $check_out, $conn)
{
    $sql = "SELECT * FROM room WHERE id = :room_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':room_id', $room_id, PDO::PARAM_INT);
    $stmt->execute();

    $roomData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($roomData) {
        $reservations = $roomData['reservation'];

        // Check if $reservationData is a non-empty string
        if (!empty($reservationData) && is_string($reservationData)) {
            $reservations = json_decode($reservationData, true);

            // Check if $reservations is an array before proceeding
            if (is_array($reservations)) {
                // Check for conflicts with existing reservations
                foreach ($reservations as $reservation) {
                    $reservationStart = new DateTime($reservation['check-in']);
                    $reservationEnd = new DateTime($reservation['check-out']);

                    $requestedStart = new DateTime($check_in);
                    $requestedEnd = new DateTime($check_out);

                    // Check if the requested range overlaps with an existing reservation
                    if (($requestedStart >= $reservationStart && $requestedStart < $reservationEnd) ||
                        ($requestedEnd > $reservationStart && $requestedEnd <= $reservationEnd) ||
                        ($requestedStart <= $reservationStart && $requestedEnd >= $reservationEnd)) {
                        return false; // Room is not available
                    }
                }
            } else {
                // Handle the case where $reservations is not an array
                echo "Error: Reservations data is not in the expected format.";
                // You might want to log this error or take appropriate action
            }
        } else {
            // Handle the case where $reservationData is empty or not a string
            echo "Error: Invalid reservations data format.";
            // You might want to log this error or take appropriate action
        }
        return true; // Room is available

    }

    return false; // Room not found
}

// Display availability calendar for a specific room
function displayAvailabilityCalendar($room_id, $conn)
{
    echo "<h2>Availability Calendar for Room {$room_id}</h2>";

    // Generate a calendar for a specific range (you can customize this)
    $startDate = new DateTime('now');
    $endDate = (new DateTime('now'))->modify('+30 days'); // Display availability for the next 30 days

    echo "<table border='1'>";
    echo "<tr><th>Date</th><th>Availability</th></tr>";

    $currentDate = clone $startDate;
    while ($currentDate <= $endDate) {
        $dateString = $currentDate->format('Y-m-d');
        $availability = isRoomAvailable($room_id, $dateString, $dateString, $conn) ? 'Available' : 'Not Available';

        echo "<tr><td>{$dateString}</td><td>{$availability}</td></tr>";

        $currentDate->modify('+1 day');
    }

    echo "</table>";
}

// Example: Display availability calendar for Room 1
$roomIdToCheck = 1;
displayAvailabilityCalendar($roomIdToCheck, $conn);
