<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-image: url('mainPage.jpg');
        }

        p {
            font-family: verdana;
            background-color: #e6e7f2;
            border-radius: 4px;
            border-style: solid;
            border-color: #e6e7f2;
            border-width: 2px;
            
        }

        h1 {
            text-shadow: 2px 2px blue;
        }

        h2 {
            text-shadow: 2px 2px blue;
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

    <!-- welcoming message -->
    <h1>Welcome to the Marvel Rivals Information</h1>
    <p>This website allows users to learn more about marvel rivals, as well as book coaching sessions.</p>
    
    <!-- exmaple area for informaiton -->
    <section>
        <h2>About Our Platform</h2>
        <p>Our site allows users to learn improve and ask questions to the community.</p>
        
        <h2>How It Works</h2>
        <p>You can create a forum post by navigating to the "Create Forum" page, and view other discussions, you can book sessions with the coaches, and you can just view basic information </p>
        
        <h2>Coach Booking</h2>
        <p>Need professional guidance? Browse our list of coaches and book a session that suits your needs.</p>
    </section>
</body>
</html>