<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Party Details</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('https://ilimoww.com/wp-content/uploads/2022/12/GetPaidStock.com_-6399998ecee15.webp'); 
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(240, 240, 240, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .party-details {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            width: 300px;
            max-width: 80%;
        }

        h1 {
            color: #ff4500;
            font-size: 28px;
            margin-bottom: 30px;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="overlay">
    <div class="party-details">
        <h1>Show Party Details</h1>
        <?php
        session_start();

        $hostname = 'mysql.eecs.ku.edu';
        $username = '447s24_m401c456';
        $password = 'ohzie7Pu';
        $database = '447s24_m401c456';

        $conn = new mysqli($hostname, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        function executeQuery($sql)
        {
            global $conn;
            $result = $conn->query($sql);
            return $result;
        }

        if (isset($_GET['partyID'])) {
            $partyID = $_GET['partyID'];

         
            $sql = "SELECT * FROM P_Party WHERE PartyID = '$partyID'";
            $result = executeQuery($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<p>Party ID: " . $row['PartyID'] . "</p>";
                echo "<p>Party Name: " . $row['PartyName'] . "</p>";

    
                $attractionSql = "SELECT * FROM P_InLine WHERE PartyID = '$partyID'";
                $attractionResult = executeQuery($attractionSql);

                if ($attractionResult->num_rows > 0) {
                    echo "<p>Attractions:</p>";
                    echo "<ul>";
                    while ($attractionRow = $attractionResult->fetch_assoc()) {
                        echo "<li>" . $attractionRow['AttractionID'] . " - " . $attractionRow['AttractionName'] . " (Ready Time: " . $attractionRow['ReadyTime'] . ")</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No attractions found for this party.</p>";
                }
            } else {
                echo "<p>Party not found.</p>";
            }
        } else {
            echo "<p>Party ID not provided.</p>";
        }
        ?>
    </div>
</div>
</body>
</html>
