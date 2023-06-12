<!DOCTYPE html>
<html>
<head>
  <title>Reservations</title>
  <style>
    .notification {
      display: none;
      padding: 20px;
      background-color: #f44336;
      color: white;
      font-weight: bold;
    }

    .success {
      background-color: #4CAF50;
    }
  </style>
</head>
<body>
<?php
$host = 'localhost';
$db = 'databasendgm';
$user = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);

    // Fetch all records from the reservations table
    $stmt = $conn->query("SELECT * FROM reservations");
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display the table header
    echo '<table>';
    echo '<tr><th>ID</th><th>Venue Name</th><th>Purpose</th><th>Reservation Date</th><th>Start Time</th><th>End Time</th><th>Grace Period</th><th>Contact Person</th><th>Action</th></tr>';

    // Display each reservation as a table row
    foreach ($reservations as $reservation) {
        echo '<tr>';
        echo '<td>' . $reservation['id'] . '</td>';
        echo '<td>' . $reservation['venue_name'] . '</td>';
        echo '<td>' . $reservation['purpose'] . '</td>';
        echo '<td>' . $reservation['reservation_date'] . '</td>';
        echo '<td>' . $reservation['start_time'] . '</td>';
        echo '<td>' . $reservation['end_time'] . '</td>';
        echo '<td>' . $reservation['grace_period'] . '</td>';
        echo '<td>' . $reservation['contact_person'] . '</td>';
        echo '<td>';
        echo '<form action="cancel_reservation.php" method="post">';
        echo '<input type="hidden" name="reservation_id" value="' . $reservation['id'] . '">';
        echo '<input type="submit" value="Cancel">';
        echo '</form>';
        echo '<form action="edit_reservation.php" method="post">';
        echo '<input type="hidden" name="reservation_id" value="' . $reservation['id'] . '">';
        echo '<input type="submit" value="Edit">';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</table>';
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>

    <form action="reservations.php" method="post">
        <input type="submit" value="Make Another Reservation">
    </form>
	
	<form action="Act9-1Home.php" method="post">
        <input type="submit" value="Home">
    </form>
</body>
</html>
