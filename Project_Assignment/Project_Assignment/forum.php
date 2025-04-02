<?php 
include 'config.php'; 

// Resets forum ID
if (isset($_SESSION['forumID']))
    unset($_SESSION['forumID']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Redirects users to the forum
    if (isset($_POST['view_forum'])) {
        $_SESSION['forumID'] = intval($_POST['forum_id']); // Note, this needs to be unset after returning to this page

        header("Location: posts.php");
		exit();
    }

    // Creates a new forum
    if (isset($_POST['new_forum'])) {
        include 'database.php';

        $title = htmlspecialchars($_POST['new-forum-title']);
        $description = htmlspecialchars($_POST['new-forum-description']);

        $stmt = $pdo->prepare("INSERT INTO project_forum (title, description, owner_id) VALUES (?, ?, ?)");
	    $stmt->execute([$title, $description, $_SESSION['userID']]);

        header("Location: forum.php");
		exit();
    }
}

// Shows all forums
function showForums() {
    include 'database.php';

    $stmt = $pdo->query("SELECT * FROM project_forum");
	$stmt->execute();
	$forums = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($forums) > 0) {
        foreach ($forums as $forum) {
            echo '<tr>';
            echo '<td>' . $forum['title'] . '</td>';
            echo '<td>' . $forum['created_at'] . '</td>'; 
            echo '<td>
                <form action="" method="post">
                    <input type="hidden" name="forum_id" value="' . htmlspecialchars($forum['id']) . '">
                    <button type="submit" name="view_forum" id="view_forum">View Forum</button>
                </form>
                </td>';
            echo '</tr>';
        }
    } else {
        echo '<tr>';
            echo '<td>No forums available.</td>';
        echo '</tr>';
    }
}

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Forums</title>
    <link rel="stylesheet" href="styles.css">
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
    <h1>Gaming Warehouse Forums</h1>
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
            <th>Title</th>
            <th>Date of Creation</th>
            <th>Actions</th>
        </tr>-->
        <?php 
            showForums();
        ?>
    </table>
    
    <?php 
    // Display option to create forums if user is logged in
    if (isset($_SESSION['userID'])) {
        echo '<form method="POST" action="forum.php">';
        echo '<input type="hidden" name="user_id" value="' . $_SESSION['userID'] . '"><br>';
        echo '<input type="text" id="new-forum-title" name="new-forum-title" placeholder="Enter a title" required><br>';
        echo '<input type="text" id="new-forum-description" name="new-forum-description" placeholder="Enter a description" required><br>';
        echo '<button type="submit" name="new_forum">Create Forum</button><br>';
        echo '</form>';
    }
    ?>

    <!--<fieldset>
    <form>
        <label for="testpost">Reply: </label><br>
        <input type="text" id="testuser" name="testuser"><br>
        <button type="submit">Post</button><br>
    </form>
    </fieldset>-->

</body>
</html>