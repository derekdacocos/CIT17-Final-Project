<?php
// Include the database connection
include_once "./db.php"; // Ensure the database connection is included at the start

// Fetch all services from the database
$sql = "SELECT service_id, service_name, description, price, duration, image_url FROM Services";
$result = $conn->query($sql);

// Define filters and sorting options
$service_types = ['Massage Therapy', 'Facial Treatment', 'Aromatherapy']; // Example categories
$min_price = 0;
$max_price = 5000;
$min_duration = 30;
$max_duration = 120;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - Booking System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="page-services">
    <header class="hero">
        <h1>Our Services</h1>
        <p>Explore our wide range of services and book your next appointment.</p>
    </header>

    <main>
        <div class="services-container">
            <!-- Sidebar Filters -->
            <aside class="filters">
                <h2>Filters</h2>
                <form action="services.php" method="GET">
                    <label for="type">Service Type:</label>
                    <select name="type" id="type">
                        <option value="">All</option>
                        <?php foreach ($service_types as $type) { ?>
                            <option value="<?= $type ?>"><?= $type ?></option>
                        <?php } ?>
                    </select>
                    <br>

                    <label for="price_range">Price Range:</label>
                    <input type="number" name="min_price" id="min_price" placeholder="Min" value="<?= $min_price ?>">
                    <input type="number" name="max_price" id="max_price" placeholder="Max" value="<?= $max_price ?>">
                    <br>

                    <label for="duration">Duration:</label>
                    <input type="number" name="min_duration" id="min_duration" placeholder="Min" value="<?= $min_duration ?>">
                    <input type="number" name="max_duration" id="max_duration" placeholder="Max" value="<?= $max_duration ?>">
                    <br>

                    <label for="sort">Sort By:</label>
                    <select name="sort" id="sort">
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="duration_asc">Duration: Short to Long</option>
                        <option value="duration_desc">Duration: Long to Short</option>
                    </select>

                    <button type="submit">Apply Filters</button>
                </form>
            </aside>

            <!-- Service Cards -->
            <div class="services-grid">
                <?php
                // Apply filters and sorting
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                    $type_filter = isset($_GET['type']) ? $_GET['type'] : '';
                    $min_price = isset($_GET['min_price']) ? $_GET['min_price'] : 0;
                    $max_price = isset($_GET['max_price']) ? $_GET['max_price'] : 5000;
                    $min_duration = isset($_GET['min_duration']) ? $_GET['min_duration'] : 30;
                    $max_duration = isset($_GET['max_duration']) ? $_GET['max_duration'] : 120;
                    $sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'price_asc';

                    // Construct the SQL query with filtering and sorting
                    $sql = "SELECT service_id, service_name, description, price, duration, image_url FROM Services WHERE price BETWEEN $min_price AND $max_price AND duration BETWEEN $min_duration AND $max_duration";

                    if ($type_filter) {
                        $sql .= " AND service_name = '$type_filter'";
                    }

                    switch ($sort_option) {
                        case 'price_asc':
                            $sql .= " ORDER BY price ASC";
                            break;
                        case 'price_desc':
                            $sql .= " ORDER BY price DESC";
                            break;
                        case 'duration_asc':
                            $sql .= " ORDER BY duration ASC";
                            break;
                        case 'duration_desc':
                            $sql .= " ORDER BY duration DESC";
                            break;
                        default:
                            $sql .= " ORDER BY price ASC";
                    }

                    $result = $conn->query($sql);
                }

                // Check if there are services and display them
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $service_name = $row['service_name'];
                        $description = $row['description'];
                        $price = $row['price'];
                        $duration = $row['duration'];
                        $image_url = $row['image_url'] ? $row['image_url'] : 'default-image.jpg'; // Fallback image

                        echo "
                        <div class='service-card'>
                            <img src='$image_url' alt='$service_name'>
                            <h3>$service_name</h3>
                            <p>Price: ₱$price</p>
                            <p>Duration: $duration mins</p>
                            <p>$description</p>
                            <a href='booking.php?service_id=" . $row['service_id'] . "' class='btn'>Book Now</a>
                        </div>";
                    }
                } else {
                    echo "<p>No services available that match your criteria.</p>";
                }
                ?>
            </div>
        </div>
    </main>
</body>
</html>
