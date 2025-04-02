<?php
include 'config.php'; 

// Returns to login page if user is not logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
	exit();
}

// Determine which user to load
/*$user_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_SESSION['userID']) ? intval($_SESSION['userID']) : null);

if (!$user_id) {
    die("No user ID provided.");
}*/

// Sets user's information
try {
    include_once('database.php'); // Connects to database
    
    // Gets the user by ID
    $user_id = intval($_SESSION['userID']);
    $stmt = $pdo->prepare("SELECT * FROM project_marvel_rivals WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        die("User not found");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Updates user's profile
    if (isset($_POST['update_profile'])) {
        header("Location: update_profile.php");
		exit();
    }

    // Logs user out
    if (isset($_POST['logout'])) {
        session_unset();
        header("Location: index.php");
		exit();
    }
}

// Shows all of the user's booked sessions from latest to oldest
function showUserSessions() {
    include 'database.php';

    $stmt = $pdo->prepare("SELECT 
    project_session_booking.id AS session_id,
    project_session_booking.session_time,
    project_session_booking.reason,
    project_marvel_rivals.username
    FROM project_session_booking 
    JOIN project_marvel_rivals ON project_session_booking.coach_id = project_marvel_rivals.id
    WHERE user_id = :user_id
    ORDER BY project_session_booking.session_time DESC");
	$stmt->bindParam(':user_id', $_SESSION['userID'], PDO::PARAM_STR);
	$stmt->execute();
	$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($sessions) > 0) {
        foreach ($sessions as $session) {
            echo '<tr>';
            echo '<td>' . $session['reason'] . '</td>';
            echo '<td>' . $session['username'] . '</td>'; // Time part
            echo '<td>' . $session['session_time'] . '</td>';
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "Profile"; ?></title> <!-- $_SESSION['user']['username']); ?>'s  -->
    <link rel="stylesheet" href="styles.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            padding-top: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        
        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 30px;
            border: 3px solid #eee;
        }
        
        .default-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 30px;
            color: #777;
            font-size: 40px;
        }
        
        .user-info h1 {
            margin: 0;
            font-size: 28px;
            display: flex;
            align-items: center;
        }
        
        .role-badge {
            display: inline-block;
            background: #ed3b3b;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 14px;
            margin-left: 10px;
            text-transform: capitalize;
        }
        
        .role-badge.coach {
            background: #2196F3;
        }
        
        .bio-section {
            margin-top: 20px;
        }
        
        .bio-section h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #555;
        }
        
        .bio-content {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #ed3b3b;
        }
        body {
            background-image: url('profile.jpg');
        }
        table {
            width: 100%;
        }
        td, tr {
	        width: 50%;
        }
    </style>
</head>
<body>
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

    <div class="profile-container">
        <div class="profile-header">
            <?php if ($user['profile_picture']): ?>
                <img src="<?php echo "uploads/" . htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="profile-picture">
            <?php else: ?>
                <div class="default-avatar">
                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                </div>
            <?php endif; ?>
            
            <div class="user-info">
                <h1>
                    <?php echo htmlspecialchars($user['username']); ?>
                    <span class="role-badge <?php echo strtolower($user['role']); ?>">
                        <?php echo htmlspecialchars($user['role']); ?>
                    </span>
                </h1>
            </div>
        </div>
        
        <form method="POST" action="">
            <button type="submit" id="update_profile" name="update_profile">Update Profile</button>
            <button type="submit" id="logout" name="logout">Logout</button>
	    </form>

        <?php if ($user['bio']): ?>
        <div class="bio-section">
            <h2>About Me</h2>
            <div class="bio-content">
                <?php echo nl2br(htmlspecialchars($user['bio'])); ?>
            </div>
        </div>
        <?php endif; ?>

        <br><h2>Notifications</h2>
        <table>
        <tr>
            <th>Reason</th>
            <th>Coach Name</th>
            <th>Time</th>
        </tr>
        <?php 
            showUserSessions();
        ?>
        </table>
    </div>
</body>
</html>