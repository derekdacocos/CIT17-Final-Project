<?php
session_start();
include_once "db.php";

// // Ensure the user is logged in and is an admin
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header("Location: login.php");
//     exit();
// }

// Fetch all bookings
$bookings_sql = "SELECT * FROM Appointments";
$bookings_result = $conn->query($bookings_sql);

// Fetch all services
$services_sql = "SELECT * FROM Services";
$services_result = $conn->query($services_sql);

// Fetch therapist availability
$availability_sql = "SELECT * FROM Availability";
$availability_result = $conn->query($availability_sql);

// Fetch payments
$payments_sql = "SELECT * FROM Payments";
$payments_result = $conn->query($payments_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Booking System</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <h1>Admin Dashboard</h1>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_services.php">Manage Services</a>
        <a href="manage_bookings.php">Manage Bookings</a>
        <a href="therapist_schedule.php">Therapist Schedule</a>
        <a href="payments_reports.php">Payments & Reports</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h3>Admin Panel</h3>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_services.php">Manage Services</a></li>
                <li><a href="manage_bookings.php">Manage Bookings</a></li>
                <li><a href="therapist_schedule.php">Therapist Schedule</a></li>
                <li><a href="payments_reports.php">Payments & Reports</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2>Admin Dashboard</h2>

            <!-- Manage Bookings -->
            <section>
                <h3>Manage Bookings</h3>
                <table border="1" cellpadding="10">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>User ID</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['appointment_id']); ?></td>
                                <td><?php echo htmlspecialchars($booking['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['appointment_date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['status']); ?></td>
                                <td>
                                    <a href="approve_booking.php?id=<?php echo $booking['appointment_id']; ?>">Approve</a> |
                                    <a href="cancel_booking.php?id=<?php echo $booking['appointment_id']; ?>">Cancel</a> |
                                    <a href="reschedule_booking.php?id=<?php echo $booking['appointment_id']; ?>">Reschedule</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <!-- Manage Services -->
            <section>
                <h3>Manage Services</h3>
                <table border="1" cellpadding="10">
                    <thead>
                        <tr>
                            <th>Service ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($service = $services_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($service['service_id']); ?></td>
                                <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                                <td><?php echo htmlspecialchars($service['description']); ?></td>
                                <td><?php echo htmlspecialchars($service['price']); ?></td>
                                <td><?php echo htmlspecialchars($service['duration']); ?></td>
                                <td>
                                    <a href="edit_service.php?id=<?php echo $service['service_id']; ?>">Edit</a> |
                                    <a href="delete_service.php?id=<?php echo $service['service_id']; ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="add_service.php" class="btn">Add New Service</a>
            </section>

            <!-- Therapist Schedule -->
            <section>
                <h3>Therapist Schedule</h3>
                <table border="1" cellpadding="10">
                    <thead>
                        <tr>
                            <th>Therapist ID</th>
                            <th>Name</th>
                            <th>Available From</th>
                            <th>Available To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($availability = $availability_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($availability['therapist_id']); ?></td>
                                <td><?php echo htmlspecialchars($availability['therapist_name']); ?></td>
                                <td><?php echo htmlspecialchars($availability['available_from']); ?></td>
                                <td><?php echo htmlspecialchars($availability['available_to']); ?></td>
                                <td>
                                    <a href="edit_availability.php?id=<?php echo $availability['availability_id']; ?>">Edit</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="add_availability.php" class="btn">Add Availability</a>
            </section>

            <!-- Payments & Reports -->
            <section>
                <h3>Payments & Reports</h3>
                <table border="1" cellpadding="10">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>User ID</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($payment = $payments_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['payment_id']); ?></td>
                                <td><?php echo htmlspecialchars($payment['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($payment['amount']); ?></td>
                                <td><?php echo htmlspecialchars($payment['status']); ?></td>
                                <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>

</body>
</html>
