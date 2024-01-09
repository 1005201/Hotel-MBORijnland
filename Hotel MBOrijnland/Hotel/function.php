<?php
include "db.php";

class Management
{
    private $conn;

    public function __construct($conn)
    {// makes connection to the database
        $this->conn = $conn;
    }

    public function updateProject($id)
    {
        //prepare sql to update room and user information
        $sql = "UPDATE user AS u
        JOIN room AS r ON u.room_id = r.id
        SET
            u.firstname = :firstname,
            u.lastname = :lastname,
            r.room_nr = :room_nr,
            r.reservation = :reservation,
            r.room_type = :room_type,
            r.`check-in` = :check_in,
            r.`check-out` = :check_out,
            r.clean = :clean,
            r.price = :price,
            r.room_information = :room_info
        WHERE u.id = :id";


        $stmt = $this->conn->prepare($sql);
        //binds them together in the post
        $stmt->bindparam(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':firstname', isset($_POST['firstname']) ? $_POST['firstname'] : '', PDO::PARAM_STR);
        $stmt->bindValue(':lastname', isset($_POST['lastname']) ? $_POST['lastname'] : '', PDO::PARAM_STR);                
        $stmt->bindparam(':room_nr', $_POST['room_nr'], PDO::PARAM_INT);
        $stmt->bindparam(':reservation', $_POST['reservation'], PDO::PARAM_STR);
        $stmt->bindparam(':room_type', $_POST['room_type'], PDO::PARAM_STR);
        $stmt->bindparam(':check_in', $_POST['check_in'], PDO::PARAM_STR);
        $stmt->bindparam(':check_out', $_POST['check_out'], PDO::PARAM_STR);
        $stmt->bindparam(':clean', $_POST['clean'], PDO::PARAM_STR);
        $stmt->bindparam(':price', $_POST['price'], PDO::PARAM_INT);
        $stmt->bindparam(':room_info', $_POST['room_information'], PDO::PARAM_STR);
        $stmt->execute();
        //sends the user back to the dashboard
        header("Location: dashboard.php");
        exit();
    }

    public function readProject($id = null)
    {
        // Check if $id is provided to determine the query type
        if ($id !== null) {
            // Fetch a specific project based on ID
            $sql = "SELECT u.id,
                u.firstname, u.lastname, r.room_nr, r.reservation, r.room_type, r.room_information, 
                r.`check-in` as check_in, r.`check-out` as check_out, r.clean, r.price
                FROM user u
                JOIN room as r ON u.room_id = r.id
                WHERE u.id = :id";
    
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            // Fetch all projects
            $sql = "SELECT u.id,
                u.firstname, u.lastname, r.room_nr, r.reservation, r.room_type, r.room_information, 
                r.`check-in` as check_in, r.`check-out` as check_out, r.clean, r.price
                FROM user u
                JOIN room as r ON u.room_id = r.id";
    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }
    

}
?>