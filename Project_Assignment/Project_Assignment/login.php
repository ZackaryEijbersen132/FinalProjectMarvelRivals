<?php
include 'config.php'; 

$validUser = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require 'database.php'; // Connects to database
	
	$username = $_POST['username'];
	$password = $_POST['password'];

	$stmt = $pdo->prepare("SELECT * FROM project_marvel_rivals WHERE username = :username");
	$stmt->bindParam(':username', $username, PDO::PARAM_STR);
	$stmt->execute();
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	// Checks all accounts if username exists and the password matches. 
	// It will user's account if successful.
	// It currently does not check if the account is a coach
	if ($user && password_verify($password, $user['password'])) {
		$loggedUser = [
			'id' => $user['id'],
			'username' => $user['username'],
			'profile_picture' => $user['profile_picture'],
			'bio' => $user['bio'],
			'role' => $user['role'],
			'email' => $user['email']
		];

		$_SESSION['userID'] = $loggedUser['id'];
		unset($_SESSION['error']);
		header("Location: index.php");
		exit();
	} else {
		$_SESSION['error'] = $user ? "Invalid password!" : "Username not found!";
		header("Location: login.php");
		exit();
	}
}
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
	<title></title>
    <style>
        body {
            background-image: url('login.jpg');
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
            background-color: #eb9b34;
            width: 200px;
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
<h1>Welcome to Gaming Warehouse</h1>
<fieldset>
	<form method="POST" action="login.php" enctype="multipart/form-data">
        <h2>Username</h2>
		<input type="text" id="username" name="username" placeholder="Enter your username" required><br>
        <h2>Password</h2>
		<input type="password" id="password" name="password" placeholder="Enter your password" required><br><br>
		<button type="submit">Login</button>
		<?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
        <?php endif; ?>
		<p>Don't have an account? <a href="create_account.php">Register here</a>.</p>
	</form>
</fieldset>
</body>
</html>

