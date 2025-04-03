<?php
include 'config.php'; 

// Note, this reuses the code from the profile.php file. Make it only use one file instead.

// Returns to login page if user is not logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
	exit();
}

// Returns to coach list
if (!isset($_SESSION['coachID'])) {
    header("Location: list_coaches.php");
	exit();
}

// Sets coach's information
try {
    include_once('database.php'); // Connects to database
    
    // Gets the user by ID
    $user_id = intval($_SESSION['coachID']);
    $stmt = $pdo->prepare("SELECT * FROM project_marvel_rivals WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        die("User not found");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Profile</title>
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
            <?php 
            if ($user['profile_picture']) {
                echo '<img height="50px" src="' . $user['profile_picture'] . '" alt="Profile Picture Test" class="profile-picture">';
            } else {
                echo '<div class="default-avatar">';
                echo strtoupper(substr($user['username'], 0, 1));
                echo '</div>';
            }
            ?>
            
            <div class="user-info">
                <h1>
                    <?php echo htmlspecialchars($user['username']); ?>
                    <span class="role-badge <?php echo strtolower($user['role']); ?>">
                        <?php echo htmlspecialchars($user['role']); ?>
                    </span>
                </h1>
            </div>
        </div>
        
        <form method="POST" action="booking_coach.php">
            <button type="submit">Book Coach</button>
	    </form>

        <?php if ($user['bio']): ?>
        <div class="bio-section">
            <h2>About Me</h2>
            <div class="bio-content">
                <?php echo nl2br(htmlspecialchars($user['bio'])); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>