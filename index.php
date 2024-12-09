<?php
include_once "./db.php";

// Query the services table
$sql = "SELECT service_name, description, price, image_url FROM Services";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Booking System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="home">
    <header class="hero">
        <h1>Your Wellness Journey Starts Here</h1>
        <p>Book your next session now!</p>
        <div class="cta-buttons">
            <a href="booking.php" class="btn">Book Now</a>
            <a href="services.php" class="btn">View Services</a>
        </div>
    </header>

    <main>
        <!-- Services Section -->
        <section class="services">
            <h2>Our Popular Services</h2>
            <div class="services-grid">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $service_name = $row['service_name'];
                        $description = $row['description'];
                        $price = $row['price'];
                        $image_url = $row['image_url'];

                        echo "
                        <div class='service-card'>
                            <img src='$image_url' alt='$service_name'>
                            <h3>$service_name</h3>
                            <p>$description</p>
                            <p><strong>Price: ₱$price</strong></p>
                            <a href='booking.php' class='btn'>Book Now</a>
                        </div>
                        ";
                    }
                } else {
                    echo "<p>No services available at the moment.</p>";
                }
                ?>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonials">
            <h2>Customer Reviews</h2>
            <div class="testimonial-slider">
                <?php
                // Fetch reviews from the database
                $sql = "SELECT * FROM reviews JOIN users ON reviews.user_id = users.user_id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Display each review
                    while ($row = $result->fetch_assoc()) {
                        echo '
                        <div class="testimonial-card">
                            <div class="customer-info">
                                <img src="' . (empty($row['profile_pic']) ? 'default-avatar.jpg' : $row['profile_pic']) . '" alt="Customer Photo" class="customer-photo">
                                <div class="customer-details">
                                    <h4>' . htmlspecialchars($row['full_name']) . '</h4>
                                    <p>Rating: ' . htmlspecialchars($row['rating']) . '/5</p>
                                </div>
                            </div>
                            <p>"' . htmlspecialchars($row['comment']) . '"</p>
                        </div>';
                    }
                } else {
                    echo '<p>No reviews available at the moment.</p>';
                }
                ?>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section class="cta">
            <h2>Ready to Begin Your Wellness Journey?</h2>
            <p>Join us today and experience the best therapy sessions.</p>
            <div class="cta-buttons">
                <a href="register.php" class="cta-btn">Create an Account</a>
                <a href="book.php" class="cta-btn">Book Your First Session</a>
            </div>
        </section>

    </main>

    <script src="js/script.js"></script>
    
    <?php
    $conn->close();
    ?>
</body>
</html>
