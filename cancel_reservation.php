<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the reservation ID from the form
    $reservationId = $_POST["reservation_id"];

    // Here, you can implement the logic to cancel the reservation based on the reservation ID
    // You can delete the reservation record from the database or update a status field to indicate cancellation

    // Example: Deleting the reservation record from the database
    $host = 'localhost';
    $db = 'databasendgm';
    $user = 'root';
    $password = '';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);

        // Delete the reservation based on the reservation ID
        $stmt = $conn->prepare("DELETE FROM reservations WHERE id = :reservation_id");
        $stmt->bindParam(':reservation_id', $reservationId);
        $stmt->execute();

        echo 'Reservation canceled successfully.';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
