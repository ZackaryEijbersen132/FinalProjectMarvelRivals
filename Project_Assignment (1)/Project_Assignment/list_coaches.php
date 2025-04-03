<?php 
include 'config.php'; 

if (isset($_SESSION['coachID']))
    unset($_SESSION['coachID']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Directs user to coach profile
    if (isset($_POST['view_coach'])) {
        $_SESSION['coachID'] = intval($_POST['coach_id']); // Note, this needs to be unset after returning to this page

        header("Location: coach_profile.php");
		exit();
    }
}

// Searches for all users who are coaches
function showCoaches() {
    include 'database.php';

    $role = 'Coach';
    $stmt = $pdo->prepare("SELECT * FROM project_marvel_rivals WHERE role = :role");
	$stmt->bindParam(':role', $role, PDO::PARAM_STR);
	$stmt->execute();
	$coaches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($coaches) > 0) {
        foreach ($coaches as $coach) {
            echo '<tr>';
            echo '<td><img height="50px" src="' . $coach['profile_picture'] . '"></td>';
            echo '<td>' . $coach['username'] . '</td>';
            echo '<td>' .  '</td>'; // Time part
            echo '<td>
                <form action="" method="post">
                    <input type="hidden" name="coach_id" value="' . htmlspecialchars($coach['id']) . '">
                    <button type="submit" name="view_coach" id="view_coach">View Coach</button>
                </form>
                </td>';
            echo '</tr>';
        }
    } else {
        echo '<tr>';
            echo '<td>No coaches available.</td>';
        echo '</tr>';
    }
}

?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="styles.css">
    <title>Coaches</title>
    <style>
        body {
            background-image: url('forum.jpg');
        }
        h1 {
            text-shadow: 2px 2px blue;
            font-family: verdana;
            text-align: center;
            color: black;
        }
        fieldset {
            margin-top: 75px;
            background-color: white;
            width: 200px;
            border-style: solid;
            border-width: 3px;
            border-color: black;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <h1>Gaming Warehouse Coaches</h1>
    <nav>
        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="forum.php">View Forum</a>
            <div class="dropdown">
                <button class="dropbtn">Coaches 
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="list_coaches.php">List Coaches</a>
                    <a href="booking_coach.php">Booking Coach</a>
                </div>
            </div> 
            <a href="information_section.php">Information</a>
            <a href="profile.php" style="float:right">Profile</a>
            <!--<a href="coach_creation.php">Coach Creation</a>-->
        </div>
    </nav>

    <table>
        <!--<tr>
            <th>Profile Picture</th>
            <th>Coach Name</th>
            <th>Availability</th>
            <th>Actions</th>
        </tr>-->
        <?php 
            showCoaches();
        ?>
    </table>

</body>
</html>