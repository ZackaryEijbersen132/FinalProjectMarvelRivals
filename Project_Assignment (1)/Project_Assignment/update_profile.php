<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	include_once('database.php'); // Connects to database
	
	// Updates user's profile
	if (isset($_POST['update_request'])) {
		// Create a directory if it doesn't exist (temporary)
		$target_dir = "uploads/";
		if (!file_exists($target_dir)) {
			mkdir($target_dir);
		}

		// Stores the file locally (temporary)
		$fileName = $_FILES["profile-picture"]["name"];
		$ext = pathinfo($fileName, PATHINFO_EXTENSION);
		$allowedTypes = array("jpg", "jpeg", "png");
		$tempName = $_FILES["profile-picture"]["tmp_name"];
		$targetPath = $target_dir . $fileName;
		if (in_array($ext, $allowedTypes)) {
			if (move_uploaded_file($tempName, $targetPath)) {
				//$_SESSION['profile-picture'] = $fileName;
			} 
		} else {
			$_SESSION['error'] = 'Invalid file type!';
			header("Location: update_profile.php");
			exit();
		}

		if (strlen($_POST['user-password']) < 8) {
            $_SESSION['error'] = 'Password must be at least 8 characters!';
			header("Location: profile.php");
			exit();
        }
		
		$userID = $_SESSION['user']['id'];

		$hashedPassword = password_hash($_POST['user-password'], PASSWORD_BCRYPT); // Hash the password

		$stmt = $pdo->prepare("UPDATE project_marvel_rivals SET password = ?, profile_picture = ?, bio = ?, email = ? WHERE id = ?");
		$stmt->bind_param("sssss", $hashedPassword, $targetPath, $_POST['user-description'], $_POST['user-email'], $userID);
		$stmt->execute();
		
		header("Location: profile.php");
		exit();
	}

	// Cancel option
	if (isset($_POST['cancel_request'])) {
		header("Location: profile.php");
		exit();
	}
}
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
	<link rel="stylesheet" href="styles.css">
	<title></title>
    <style>
        body {
            background-image: url('update_profile.jpg_large');
        }
        h1 {
            text-shadow: 2px 2px blue;
            font-family: verdana;
            text-align: center;
            color: black;
        }
        fieldset {
            margin-top: 75px;
            margin-left: auto;
            margin-right: auto;
            background-color: #e6e7f2;
            width: 30%;
            border-style: solid;
            border-width: 3px;
            border-color: black;
            border-radius: 8px;
        }
        button {
            display: block;
            margin: 0 auto;
            border-style: solid;
            border-color: black;
            border-radius: 2px;
        }
        form {
            text-align: center;
        }
        h2 {
            text-shadow: 1px 1px blue;
        }
        p {
            text-align: center;
        }
    </style>
</head>
<body>
	<h1>Update Your Profile</h1>
    <fieldset>
	<form method="POST" action="update_profile.php" enctype="multipart/form-data">
		<!-- <input type="text" id="user-username" name="user-username" placeholder="Enter your new username" required> -->
        <h2>Password</h2>
		<input type="password" id="user-password" name="user-password" placeholder="Enter your new password" required><br>
        <h2>Email</h2>
		<input type="email" id="user-email" name="user-email" placeholder="Enter your email" required><br>
        <h2>Profile Picture</h2>
		<input type="file" id="user-profile-picture" name="user-profile-picture"><br>
        <h2>User Description</h2>
		<input type="text" id="user-description" name="user-description" placeholder="Enter information about yourself"><br>
		<?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
        <?php endif; ?>
		<button type="submit" name="update_request" id="update_request">Update</button>
		<button type="submit" name="cancel_request" id="cancel_request">Cancel</button>
	</form>
    </fieldset>
</body>
</html>

