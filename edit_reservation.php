<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the reservation ID from the form
    $reservationId = $_POST["reservation_id"];

    // Retrieve the existing reservation details from the database
    $host = 'localhost';
    $db = 'databasendgm';
    $user = 'root';
    $password = '';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);

        // Fetch the reservation details based on the reservation ID
        $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = :reservation_id");
        $stmt->bindParam(':reservation_id', $reservationId);
        $stmt->execute();

        // Fetch a single row
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the reservation exists
        if ($reservation) {
            // Assign the reservation details to variables
            $venueName = $reservation['venue_name'];
            $purpose = $reservation['purpose'];
            $reservationDate = $reservation['reservation_date'];
            $startTime = $reservation['start_time'];
            $endTime = $reservation['end_time'];
            $contactPerson = $reservation['contact_person'];

            // Display the reservation update form
            echo '
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Update Reservation</title>
                    <style>
                        .notification {
                            padding: 20px;
                            background-color: #f44336;
                            color: white;
                            font-weight: bold;
                        }
                    </style>
                </head>
                <body>
                    <h2>Update Reservation</h2>';

            // Display an error notification if the reservation overlaps with existing reservations
            $overlapError = false;

            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                // Retrieve the submitted reservation details
                $venueName = isset($_POST["venue_name"]) ? $_POST["venue_name"] : $venueName;
                $purpose = isset($_POST["purpose"]) ? $_POST["purpose"] : $purpose;
                $reservationDate = isset($_POST["reservation_date"]) ? $_POST["reservation_date"] : $reservationDate;
                $startTime = isset($_POST["start_time"]) ? $_POST["start_time"] : $startTime;
                $endTime = isset($_POST["end_time"]) ? $_POST["end_time"] : $endTime;
                $gracePeriod = isset($_POST["grace_period"]) ? $_POST["grace_period"] : 0; // Default to 0 if not provided
                $contactPerson = isset($_POST["contact_person"]) ? $_POST["contact_person"] : $contactPerson;

                // Check for overlapping reservations
                $stmt = $conn->prepare("SELECT * FROM reservations WHERE id != :reservation_id 
                    AND reservation_date = :reservation_date 
                    AND ((start_time <= TIME(DATE_ADD(:end_time, INTERVAL :grace_period MINUTE)) AND end_time > :start_time) 
                    OR (start_time < TIME(DATE_ADD(:end_time, INTERVAL :grace_period MINUTE)) AND end_time >= :start_time)
                    OR (start_time >= :start_time AND end_time <= TIME(DATE_ADD(:end_time, INTERVAL :grace_period MINUTE))))");
                $stmt->bindParam(':reservation_id', $reservationId);
                $stmt->bindParam(':reservation_date', $reservationDate);
                $stmt->bindParam(':start_time', $startTime);
                $stmt->bindParam(':end_time', $endTime);
                $stmt->bindParam(':grace_period', $gracePeriod);
                $stmt->execute();

                // Check if any overlapping reservations are found
                if ($stmt->rowCount() > 0) {
                    // Display an error notification for overlap
                    echo '<div class="notification">Error: The selected reservation overlaps with other existing reservations.</div>';
                    $overlapError = true;
                }
            }

            // Display the reservation update form
            echo '
                <form action="update_reservation.php" method="post">
                    <input type="hidden" name="reservation_id" value="' . $reservationId . '">
                    Venue Name: <input type="text" name="venue_name" value="' . $venueName . '"><br>
                    Purpose: <input type="text" name="purpose" value="' . $purpose . '"><br>
                    Reservation Date: <input type="date" name="reservation_date" value="' . $reservationDate . '"><br>
                    Start Time: <input type="time" name="start_time" value="' . $startTime . '"><br>
                    End Time: <input type="time" name="end_time" value="' . $endTime . '"><br>
                    Grace Period (minutes): <input type="number" name="grace_period" value="' . $gracePeriod . '"><br>
                    Contact Person: <input type="text" name="contact_person" value="' . $contactPerson . '"><br>';

            // Display an error notification for the overlap error
            if ($overlapError) {
                echo '<div class="notification">Error: The selected reservation overlaps with other existing reservations.</div>';
            }

            echo '
                    <input type="submit" value="Update Reservation">
                </form>
                <script>
                    // Display the notification message
                    var notification = document.querySelector(".notification");
                    if (notification) {
                        notification.style.display = "block";
                    }
                </script>
                </body>
                </html>
            ';
        } else {
            echo 'Reservation not found.';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'Invalid request.';
}
?>
