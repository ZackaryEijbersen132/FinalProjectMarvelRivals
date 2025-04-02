<?php
include 'config.php'; 

// Initialize variables
$errors = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        include_once('database.php'); // Connects to database

        // Validate inputs
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $role = isset($_POST['role']) && $_POST['role'] === 'coach' ? 'Coach' : 'User';
        
        // Profile picture handling
        $profile_picture = null;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_name = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_path)) {
                $profile_picture = $target_path;
            }
        }

        // Validate username
        if (empty($username)) {
            $errors['username'] = 'Username is required';
        } elseif (strlen($username) > 255) {
            $errors['username'] = 'Username must be less than 255 characters';
        }

        // Validate password
        if (empty($password)) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        }

        // If no errors, insert into database
        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO project_marvel_rivals (username, password, profile_picture, bio, role) 
                                  VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $profile_picture, $bio, $role]);
            
            // Check if user selected coach role
            if ($role === 'Coach') {
                $_SESSION['is_coach'] = true;
                $_SESSION['new_user'] = $username; // Store username in session if needed
                header("Location: login.php");
                //header("Location: coach_creation.php"); // Skipping for now as we are only dealing with one game
            } else {
                header("Location: login.php");
            }
            exit();
        }
    } catch (PDOException $e) {
        $errors['database'] = 'Database error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Marvel Rivals</title>
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
        input[type="password"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input {
            width: auto;
        }
        
        button {
            background-color: #479fe6;
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
        
        .error {
            color: #e62429;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .profile-picture-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: 10px;
            display: none;
            border: 2px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Your Account</h1>
        
        <?php if (isset($errors['database'])): ?>
            <div class="error"><?php echo htmlspecialchars($errors['database']); ?></div>
        <?php endif; ?>
        
        <form action="create_account.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                <?php if (isset($errors['username'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['username']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <?php if (isset($errors['password'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['password']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="profile_picture">Profile Picture (Optional)</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                <img id="preview" class="profile-picture-preview" alt="Preview">
            </div>
            
            <div class="form-group">
                <label for="bio">About You (Optional)</label>
                <textarea id="bio" name="bio"><?php echo htmlspecialchars($_POST['bio'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Account Type</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="role" name="role" value="coach">
                    <label for="role" style="font-weight: normal;">Check this box if you want to be a Coach</label>
                </div>
            </div>
            
            <button type="submit">Create Account</button>
        </form>
    </div>

    <script>
        // Preview profile picture before upload
        document.getElementById('profile_picture').addEventListener('change', function(e) {
            const preview = document.getElementById('preview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
</body>
</html>