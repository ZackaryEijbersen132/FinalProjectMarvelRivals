<?php
// Start session to check if user selected coach role
session_start();

// Redirect to CreateAccount if not coming from coach registration
if (!isset($_SESSION['is_coach']) || !$_SESSION['is_coach']) {
    header("Location: CreateAccount.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Redirect to login after submission
    header("Location: Login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Registration - Marvel Rivals</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
            background-image: url('accountCreation.jpg');
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #afc5db;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-style: solid;
            border-width: 5px;
            border-color: #161717;
        }
        
        h1 {
            text-align: center;
            color: black;
            margin-bottom: 30px;
            text-shadow: 2px 2px blue;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        button {
            background-color: #2196F3;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #0b7dda;
        }
        .bronze {
            color: #c27542;
        }
        .silver {
            color: #a39f9d;
        }
        .gold {
            color: #dbce56;
        }
        .platinum {
            color: #41a8bf;
        }
        .diamond {
            color: #a5dce8;
        }
        .grandmaster {
            color: #903e9e;
        }
        .celestial {
            color: #db4523;
        }
        .eternity {
            color: #bf22ba;
        }
        .oneAboveAll {
            color: #d45a1e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Coach Information</h1>
        <p>Please provide your game details to complete your coach registration.</p>
        
        <form action="CoachCreation.php" method="post">
            <div class="form-group">
                <label for="game_id">Marvel Rivals Game ID</label>
                <input type="text" id="game_id" name="game_id" required>
            </div>
            
            <div class="form-group">
                <label for="rank">Current Rank</label>
                <select id="rank" name="rank" required>
                    <option value="">Select your rank</option>
                    <option value="bronze">Bronze(Tier III-I)</option>
                    <option value="silver">Silver(Tier III-I)</option>
                    <option value="gold">Gold(Tier III-I)</option>
                    <option value="platinum">Platinum(Tier III-I)</option>
                    <option value="diamond">Diamond(Tier III-I)</option>
                    <option value="grandmaster">Grandmaster(Tier III-I)</option>
                    <option value="celestial">Celestial(Tier III-I)</option>
                    <option value="eternity">Eternity</option>
                    <option value="oneAboveAll">One Above All</option>
                </select>
            </div>
            
            <button type="submit">Complete Registration</button>
        </form>
    </div>
</body>
</html>