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
            <a href="login.php" class="btn btn-primary">Book Now</a>
            <!-- Login and Sign Up Buttons -->
            <a href="register.php" class="btn btn-secondary">Sign Up</a>
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
                <!-- Static testimonials -->
                <div class="testimonial-card">
                    <div class="customer-info">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRzbcRd8Hv5VQrwTl6vg1Nka55REaEC8oVSEQ&s" alt="Customer Photo" class="customer-photo">
                        <div class="customer-details">
                            <h4>John Vincent</h4>
                            <p>Rating: 5/5</p>
                        </div>
                    </div>
                    <p>"The therapy session was amazing, I feel rejuvenated!"</p>
                </div>

                <div class="testimonial-card">
                    <div class="customer-info">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSAh4cljZKBW7mMA-sTlSHqXqhyHMcWZTF2mHzLWn2P4i1UBnkwnY8qmILEuOQRzzcb5BY&usqp=CAU" alt="Customer Photo" class="customer-photo">
                        <div class="customer-details">
                            <h4>Nagi Seishiro</h4>
                            <p>Rating: 4/5</p>
                        </div>
                    </div>
                    <p>"Very professional and effective. Highly recommend!"</p>
                </div>

                <div class="testimonial-card">
                    <div class="customer-info">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSD0HTICyAkwZM_BNoKuJ0mFE00qofHnDV3BA&s" alt="Customer Photo" class="customer-photo">
                        <div class="customer-details">
                            <h4>Emily Johnson</h4>
                            <p>Rating: 5/5</p>
                        </div>
                    </div>
                    <p>"I loved the service! The staff were friendly and attentive."</p>
                </div>

                <div class="testimonial-card">
                    <div class="customer-info">
                        <img src="https://www.okayafrica.com/media-library/the-oh-my-god-wow-meme-came-from-the-film-azonto-ghost-this-is-a-still-photo-from-the-ghanaian-movie.png?id=17427713&width=1000&height=1000&quality=85&coordinates=326%2C0%2C0%2C0" alt="Customer Photo" class="customer-photo">
                        <div class="customer-details">
                            <h4>Kwame Boateng</h4>
                            <p>Rating: 5/5</p>
                        </div>
                    </div>
                    <p>"Oh my God! Wow!"</p>
                </div>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section class="cta">
            <h2>Ready to Begin Your Wellness Journey?</h2>
            <p>Join us today and experience the best therapy sessions.</p>
            <div class="cta-buttons">
                <a href="register.php" class="cta-btn">Create an Account</a>
                <a href="booking.php" class="cta-btn">Book Your First Session</a>
            </div>
        </section>

    </main>

    <script src="js/script.js"></script>
    
    <?php
    $conn->close();
    ?>
</body>
</html>
