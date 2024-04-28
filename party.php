<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Party</title>
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

      .signup-container {
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

      input[type="text"],
      input[type="password"] {
          width: 100%;
          padding: 12px;
          margin-bottom: 20px;
          box-sizing: border-box;
          border: 1px solid #ddd;
          border-radius: 6px;
          font-size: 16px;
      }

      button[type="submit"] {
          background-color: #ff4500;
          color: #fff;
          border: none;
          border-radius: 6px;
          padding: 12px 20px;
          font-size: 16px;
          cursor: pointer;
          width: 100%;
      }

      button[type="submit"]:hover {
          background-color: #e04107;
      }

      .signup-link {
          margin-top: 20px;
          font-size: 14px;
      }

      .signup-link a {
          color: #007bff;
          text-decoration: none;
      }

      .signup-link a:hover {
          text-decoration: underline;
      }
    </style>
</head>
<body>
<div class="container">
    <h1>My Party</h1>
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

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("Location: login.php");
        exit;
    }

    // Process leave party action
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["leave_party"])) {
        $userID = $_SESSION["userID"];
        $partyID = $_POST["leave_party"];

       
        $sql = "DELETE FROM P_Membership WHERE UserID = '$userID' AND PartyID = '$partyID'";
        if (executeQuery($sql)) {
            echo "<p>You have successfully left the party.</p>";
        } else {
            echo "<p>Failed to leave the party. Please try again.</p>";
        }
    }

    // Display current party ID and actions
    $userID = $_SESSION["userID"];
    $sql = "SELECT PartyID FROM P_Membership WHERE UserID = '$userID'";
    $result = executeQuery($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $partyID = $row['PartyID'];

        
        echo "<p>Party ID: <a href='showparty.php?partyID=$partyID'>$partyID</a></p>";

      
        echo "<form method='post'>";
        echo "<input type='hidden' name='leave_party' value='$partyID'>";
        echo "<input type='submit' value='Leave Party' class='btn btn-danger'>";
        echo "</form>";
    } else {
        echo "<p>You are not currently in any party.</p>";

        
        echo "<h3>Not having a party? Build your own!</h3>";
        echo "<form method='post'>";
        echo "<label for='new_party_name'>Party Name:</label>";
        echo "<input type='text' id='new_party_name' name='new_party_name'>";
        echo "<input type='submit' value='Create Party' class='btn btn-success'>";
        echo "</form>";

        // Process creating a new party
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_party_name"])) {
            $partyName = $_POST["new_party_name"];
            $creatorUserID = $_SESSION["userID"];

    
            $sql = "INSERT INTO P_Party (PartyName, CreatorUserID) VALUES ('$partyName', '$creatorUserID')";
            if (executeQuery($sql)) {
                echo "<p>New party '$partyName' created successfully!</p>";
            } else {
                echo "<p>Failed to create a new party. Please try again.</p>";
            }
        }
    }

    // Process joining an existing party
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["join_party"])) {
        $partyID = $_POST["join_party"];
        $userID = $_SESSION["userID"];
       
        $checkSql = "SELECT * FROM P_Party WHERE PartyID = '$partyID'";
        $checkResult = executeQuery($checkSql);

        if ($checkResult->num_rows > 0) {
            
            $insertSql = "INSERT INTO P_Membership (PartyID, UserID) VALUES ('$partyID', '$userID')";
            if (executeQuery($insertSql)) {
                echo "<p>Joined party '$partyID' successfully!</p>";
            } else {
                echo "<p>Failed to join the party. Please try again.</p>";
            }
        } else {
            echo "<p>Party '$partyID' does not exist. Please enter a valid party ID.</p>";
        }
    }
    ?>

    <h3>Join a New Party</h3>
    <form method="post">
        <label for="party_id">Party ID:</label>
        <input type="text" id="party_id" name="join_party">
        <input type="submit" value="Join Party" class="btn btn-primary">
    </form>
</div>

</body>
</html>
