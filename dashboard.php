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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
        <header class="navbar">
            <div class="navbar-container">
                <h1 class="logo">Booking System</h1>
                <nav class="nav-links">
                    <a href="dashboard.php" class="nav-item">Dashboard</a>
                    <a href="edit_profile.php" class="nav-item">Account Settings</a>
                    <a href="logout.php" class="nav-item">Logout</a>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1 class="hero-title">Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h1>
                <p class="hero-subtitle">Your wellness journey starts here. Manage your bookings and more below.</p>
                <div class="cta-buttons">
                    <a href="booking.php" class="btn btn-primary">Book Now</a>
                    <a href="services.php" class="btn btn-secondary">View Services</a>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="services">
            <h2>Your Bookings</h2>
            <div class="services-grid">
                <!-- Upcoming Appointments -->
                <div class="service-card">
                    <h3>Upcoming Appointments</h3>
                    <?php if ($upcoming_appointments_result->num_rows > 0): ?>
                        <ul>
                            <?php while ($appointment = $upcoming_appointments_result->fetch_assoc()): ?>
                                <li>
                                    <p>Service: <?php echo htmlspecialchars($appointment['service_name']); ?></p>
                                    <p>Date: <?php echo htmlspecialchars($appointment['appointment_date']); ?></p>
                                    <a href="cancel_appointment.php?appointment_id=<?php echo $appointment['appointment_id']; ?>" class="btn">Cancel</a>
                                    <a href="reschedule_appointment.php?appointment_id=<?php echo $appointment['appointment_id']; ?>" class="btn">Reschedule</a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No upcoming appointments.</p>
                    <?php endif; ?>
                </div>

                <!-- Past Appointments -->
                <div class="service-card">
                    <h3>Past Appointments</h3>
                    <?php if ($past_appointments_result->num_rows > 0): ?>
                        <ul>
                            <?php while ($appointment = $past_appointments_result->fetch_assoc()): ?>
                                <li>
                                    <p>Service: <?php echo htmlspecialchars($appointment['service_name']); ?></p>
                                    <p>Date: <?php echo htmlspecialchars($appointment['appointment_date']); ?></p>
                                    <a href="leave_review.php?appointment_id=<?php echo $appointment['appointment_id']; ?>" class="btn">Leave a Review</a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No past appointments.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="services-overview">
            <h2>Our Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <h3>Massage Therapy</h3>
                    <p>Relax your body and mind with our professional massage services.</p>
                    <a href="booking.php?service=massage" class="btn">Learn More</a>
                </div>
                <div class="service-card">
                    <h3>Skin Care</h3>
                    <p>Rejuvenate your skin with our personalized treatments.</p>
                    <a href="booking.php?service=skincare" class="btn">Learn More</a>
                </div>
                <div class="service-card">
                    <h3>Yoga Classes</h3>
                    <p>Find your balance and strength with our yoga sessions.</p>
                    <a href="booking.php?service=yoga" class="btn">Learn More</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Booking System. All rights reserved.</p>
    </footer>
</body>
</html>
