<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
	<style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #333333;
            color: #ffffff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e0e0e0;
        }

        .logout-form {
            text-align: center;
            margin-top: 20px;
        }

        .logout-form input[type="submit"] {
            padding: 10px 20px;
            background-color: #337ab7;
            color: #ffffff;
            border: none;
            cursor: pointer;
        }

        .logout-form input[type="submit"]:hover {
            background-color: #23527c;
        }
    </style>
</head>
<body>
    <h2 align="center"> List of Available Venues </h2>

    <?php
    // Database connection
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'database1';

    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
        die("Failed to connect to the database: " . mysqli_connect_error());
    }

    // Retrieve data from a specific table
    $table = 'venues'; // Replace 'your_table' with the actual table name

    $query = "SELECT * FROM $table";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Failed to fetch data from the database: " . mysqli_error($conn));
    }

    //Create array for values to be read from DB1
    $results = array();

    while ($row = mysqli_fetch_array($result)) {
        $results[] = array(
            "name" => $row['Name'],
            "capacity" => $row['Capacity']
        );
    }

    echo "<table style='width:.5' border ='1 px'>";
    echo "<tr>";
    echo "<th>Venue Name</th>";
    echo "<th>Capacity</th>";
    echo "</tr>";
    foreach ($results as $row) {
        echo "<tr>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["capacity"] . "</td>";
        echo "</tr>";
    }

    // Close the database connection
    mysqli_close($conn);
    ?>

    <form action="Act9-1Login.php" method="logout">
        <input type="submit" value="Logout"/>
    </form>
    <form action="reservations.php" method="reservation">
        <input type="submit" value="Make a Reservation"/>
    </form>
</body>
</html>
