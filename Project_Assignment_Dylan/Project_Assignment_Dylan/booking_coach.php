<?php
include 'config.php'; 

// Checks if user is logged in
if (!isset($_SESSION['userID'])) {
	header("Location: login.php");
	exit();
}

if (isset($_SESSION['coachID'])) {
	include_once('database.php'); // Connects to database

	$stmt = $pdo->prepare("SELECT * FROM project_marvel_rivals WHERE role = 'Coach' AND id = :id");
    $stmt->bindParam(':id', $_SESSION['coachID'], PDO::PARAM_STR);
	$stmt -> execute();
	$_SESSION['coach'] = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
	header("Location: list_coaches.php");
	exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
	// If user cancels request, return to view coach list and unset coach variable
	if (isset($_POST['cancel_request'])) {
		unset($_SESSION['coach']);
		header("Location: list_coaches.php");
		exit();
	}

	// If user submits a request, a new session is added onto the sessions table
	if (isset($_POST['submit_request'])) {
		include_once('database.php'); // Connects to database

		$stmt = $pdo->prepare("INSERT INTO project_session_booking (user_id, coach_id, session_time, reason) VALUES (?, ?, ?, ?)");
		$stmt->execute([$_SESSION['userID'], $_SESSION['coach']['id'], $_POST['user-selected-time'], $_POST['user-reason']]);
		
		// Note, we could allow users to stay on the session page and make more bookings, but I'm not sure how to do it
		unset($_SESSION['coach']);
		unset($_SESSION['coachID']);
		header("Location: list_coaches.php");
		exit();
	}
}

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
	<link rel="stylesheet" href="styles.css">
	<title>Booking Coach</title>
	<style>
		body {
            background-image: url('coach.png');
        }
	</style>
</head>
<body>
	<form method="POST" action="booking_coach.php">
		<h3>Booking a Session with <?php echo $_SESSION['coach']['username']; ?></h3>
		<input type="hidden" name="coach_id" value="' . $_SESSION['coach']['id'] . '">
		<input type="hidden" name="user_id" value="' . $_SESSION['user']['id'] . '">
		<input type="datetime-local" id="user-selected-time" name="user-selected-time" placeholder="Select a date and time" required>
		<input type="text" id="user-reason" name="user-reason" placeholder="Enter a reason" required>
		<button type="submit" name="submit_request" id="submit_request">Submit</button>
		<button type="submit" name="cancel_request" id="cancel_request">Cancel</button>
		<!-- Place error message here -->
	</form>
</body>
</html>