<?php
session_start();
include_once "db.php";

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch user's upcoming appointments
$upcoming_appointments_sql = "SELECT * FROM Appointments WHERE user_id = '$user_id' AND appointment_date >= NOW()";
$upcoming_appointments_result = $conn->query($upcoming_appointments_sql);

// Fetch user's past appointments
$past_appointments_sql = "SELECT * FROM Appointments WHERE user_id = '$user_id' AND appointment_date < NOW()";
$past_appointments_result = $conn->query($past_appointments_sql);

// Fetch user's details for account settings
$user_sql = "SELECT * FROM Users WHERE user_id = '$user_id'";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Booking System</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <h1>Booking System</h1>
        <a href="dashboard.php">Dashboard</a>
        <a href="edit_profile.php">Account Settings</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h3>Welcome, <?php echo htmlspecialchars($user['full_name']); ?></h3>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="edit_profile.php">Account Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2>Your Dashboard</h2>

            <!-- Upcoming Appointments Section -->
            <section class="appointments">
                <h3>Upcoming Appointments</h3>
                <?php if ($upcoming_appointments_result->num_rows > 0): ?>
                    <ul>
                        <?php while ($appointment = $upcoming_appointments_result->fetch_assoc()): ?>
                            <li>
                                <p>Service: <?php echo htmlspecialchars($appointment['service_name']); ?></p>
                                <p>Date: <?php echo htmlspecialchars($appointment['appointment_date']); ?></p>
                                <a href="cancel_appointment.php?appointment_id=<?php echo $appointment['appointment_id']; ?>">Cancel</a>
                                <a href="reschedule_appointment.php?appointment_id=<?php echo $appointment['appointment_id']; ?>">Reschedule</a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No upcoming appointments.</p>
                <?php endif; ?>
            </section>

            <!-- Past Appointments Section -->
            <section class="appointments">
                <h3>Past Appointments</h3>
                <?php if ($past_appointments_result->num_rows > 0): ?>
                    <ul>
                        <?php while ($appointment = $past_appointments_result->fetch_assoc()): ?>
                            <li>
                                <p>Service: <?php echo htmlspecialchars($appointment['service_name']); ?></p>
                                <p>Date: <?php echo htmlspecialchars($appointment['appointment_date']); ?></p>
                                <a href="leave_review.php?appointment_id=<?php echo $appointment['appointment_id']; ?>">Leave a Review</a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No past appointments.</p>
                <?php endif; ?>
            </section>

            <!-- Account Settings Section -->
            <section class="account-settings">
                <h3>Account Settings</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
                <a href="edit_profile.php" class="btn">Edit Profile</a>
                <a href="change_password.php" class="btn">Change Password</a>
            </section>
        </div>
    </div>

</body>
</html>
