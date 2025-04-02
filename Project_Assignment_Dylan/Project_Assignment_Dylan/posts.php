<?php 
include 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Creates a new forum
    if (isset($_POST['new_reply'])) {
        include 'database.php';

        $postDescription = htmlspecialchars($_POST['new-post-description']);

        $stmt = $pdo->prepare("INSERT INTO project_posts (forum_id, user_id, post_description) VALUES (?, ?, ?)");
	    $stmt->execute([$_SESSION['forumID'], $_SESSION['userID'], $postDescription]);

        header("Location: posts.php");
		exit();
    }
}

// Displays information about the topic
function showForumDetails() {
    include 'database.php';

    $stmt = $pdo->prepare("SELECT 
    project_forum.id AS forum_id,
    project_forum.created_at,
    project_forum.description,
    project_forum.title,
    project_marvel_rivals.username
    FROM project_forum
    JOIN project_marvel_rivals ON project_forum.owner_id = project_marvel_rivals.id
    WHERE project_forum.id = :id");
	$stmt->bindParam(':id', $_SESSION['forumID'], PDO::PARAM_STR);
	$stmt->execute();
	$forum = $stmt->fetch(PDO::FETCH_ASSOC);

    echo '<h2>' . $forum['title'] . '</h2>';
    echo '<p>' . $forum['description'] . '</p>';
    echo '<p>Created on ' . $forum['created_at'] . ' by ' . $forum['username'] . '</p><hr>';
}

// Shows all replies
function showPosts() {
    include 'database.php';

    $stmt = $pdo->prepare("SELECT 
    project_posts.id as post_id, 
    project_marvel_rivals.username, 
    project_posts.post_description, 
    project_posts.created_at 
    FROM project_posts 
    JOIN project_marvel_rivals ON project_posts.user_id = project_marvel_rivals.id 
    WHERE project_posts.forum_id = :id");
    $stmt->bindParam(':id', $_SESSION['forumID'], PDO::PARAM_STR);
	$stmt->execute();
	$replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($replies) > 0) {
        foreach ($replies as $reply) {
            echo '<p>' . $reply['post_description'] . '</p>';
            echo '<p>Posted on ' . $reply['created_at'] . ' by ' . $reply['username'] . '</p><hr><br>';    
        }
    } else {
        echo '<p>No posts available.</p>';
    }
}

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Posts</title>
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

    <?php 
        showForumDetails();
        showPosts();
    ?>
    
    <?php 
    // Display option to add a reply
    if (isset($_SESSION['userID'])) {
        echo '<form method="POST" action="posts.php">';
        echo '<input type="hidden" name="user_id" value="' . $_SESSION['userID'] . '"><br>';
        echo '<input type="hidden" name="forum_id" value="' . $_SESSION['forumID'] . '"><br>';
        echo '<input type="text" id="new-post-description" name="new-post-description" placeholder="Add a message" required><br>';
        echo '<button type="submit" name="new_reply">Reply</button><br>';
        echo '</form>';
    }
    ?>

</body>
</html>