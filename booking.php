<?php
include_once "./db.php"; // Include the database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'];
    $therapist_id = $_POST['therapist_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $payment_method = $_POST['payment_method'];
    $promo_code = $_POST['promo_code'] ?? '';

    $sql = "INSERT INTO Appointments (user_id, therapist_id, service_id, appointment_date, start_time, status)
            VALUES (1, '$therapist_id', '$service_id', '$date', '$time', 'pending')"; // Assuming user_id=1 for testing

    if ($conn->query($sql) === TRUE) {
        $success = "Booking confirmed!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Fetch data for the form
$services = $conn->query("SELECT service_id, service_name, price, description FROM Services");
$therapists = $conn->query("SELECT user_id, full_name FROM Users WHERE role = 'therapist'");

// Fetch available time slots for a specific date (AJAX handler)
if (isset($_GET['date'])) {
    $selected_date = $_GET['date'];
    $sql = "SELECT time_slot FROM AvailableSlots WHERE date = '$selected_date'"; // Modify the query to match your table structure
    $result = $conn->query($sql);

    $time_slots = [];
    while ($row = $result->fetch_assoc()) {
        $time_slots[] = $row['time_slot']; // Assuming 'time_slot' is the field containing the available times
    }

    // Return available time slots as JSON
    echo json_encode($time_slots);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Appointment</title>
    <link rel="stylesheet" href="booking.css">
</head>
<body>
    <div class="container">
        <h1>Book Your Appointment</h1>

        <!-- Form Start -->
        <form id="bookingForm" method="POST" action="index.php">
            
            <!-- Step 1: Service and Therapist Selection -->
            <div class="form-step active" id="step1">
                <h2>Select Service and Therapist</h2>
                <label for="service">Service</label>
                <select name="service_id" id="service" required onchange="updateServiceSummary()">
                    <option value="">Select a Service</option>
                    <?php
                    if ($services->num_rows > 0) {
                        while ($row = $services->fetch_assoc()) {
                            echo "<option value='" . $row["service_id"] . "' data-description='" . htmlspecialchars($row["description"]) . "' data-price='" . number_format($row["price"], 2) . "'>" . $row["service_name"] . " - ₱" . number_format($row["price"], 2) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No services available</option>";
                    }
                    ?>
                </select>

                <label for="therapist">Therapist</label>
                <select name="therapist_id" id="therapist" required>
                    <option value="">Select a Therapist</option>
                    <?php
                    if ($therapists->num_rows > 0) {
                        while ($row = $therapists->fetch_assoc()) {
                            echo "<option value='" . $row["user_id"] . "'>" . $row["full_name"] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No therapists available</option>";
                    }
                    ?>
                </select>

                <div class="service-summary">
                    <h3>Service Summary</h3>
                    <p id="service-description">Service description will appear here.</p>
                    <p id="service-price">Price: ₱</p>
                </div>

                <button type="button" class="next-btn" onclick="nextStep(2)">Next</button>
            </div>

            <!-- Step 2: Date and Time Selection -->
            <div class="form-step" id="step2">
                <h2>Choose Date and Time</h2>
                <label for="date">Date</label>
                <input type="date" name="date" id="date" required onchange="loadAvailableTimeSlots()" />

                <label for="time">Time</label>
                <select name="time" id="time" required>
                    <option value="">Select a Time</option>
                    <!-- Time slots will be populated dynamically -->
                </select>

                <button type="button" class="prev-btn" onclick="prevStep(1)">Back</button>
                <button type="button" class="next-btn" onclick="nextStep(3)">Next</button>
            </div>

            <!-- Step 3: Payment and Confirmation -->
            <div class="form-step" id="step3">
                <h2>Confirmation and Payment</h2>
                <div class="confirmation-details">
                    <p>Service: <span id="confirm-service"></span></p>
                    <p>Date: <span id="confirm-date"></span></p>
                    <p>Time: <span id="confirm-time"></span></p>
                    <p>Therapist: <span id="confirm-therapist"></span></p>
                    <p>Price: <span id="confirm-price"></span></p>
                </div>

                <div class="payment-options">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" id="payment_method" required>
                        <option value="">Select a Method</option>
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                    </select>

                    <label for="promo_code">Promo Code</label>
                    <input type="text" name="promo_code" id="promo_code" placeholder="Promo Code (Optional)">
                </div>

                <button type="button" class="prev-btn" onclick="prevStep(2)">Back</button>
                <button type="submit" class="submit-btn">Confirm Booking</button>
            </div>
        </form>
    </div>

    <script src="js/script.js"></script>
    <script>
        // Function to update the service summary based on selected service
        function updateServiceSummary() {
            var serviceSelect = document.getElementById("service");
            var selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            var description = selectedOption.getAttribute("data-description");
            var price = selectedOption.getAttribute("data-price");

            // Update the service summary section
            document.getElementById("service-description").textContent = description || "Service description will appear here.";
            document.getElementById("service-price").textContent = "Price: ₱" + price;
        }

        // Function to load available time slots based on selected date
        function loadAvailableTimeSlots() {
            var date = document.getElementById("date").value;

            // Ensure a date is selected
            if (!date) {
                alert("Please select a date.");
                return;
            }

            // Use AJAX or a simple fetch to get available time slots
            fetch('index.php?date=' + date) // Same file, passing date as query parameter
                .then(response => response.json())
                .then(data => {
                    var timeSelect = document.getElementById("time");
                    timeSelect.innerHTML = "<option value=''>Select a Time</option>"; // Clear current options

                    if (data.length > 0) {
                        data.forEach(function(timeSlot) {
                            var option = document.createElement("option");
                            option.value = timeSlot;
                            option.textContent = timeSlot;
                            timeSelect.appendChild(option);
                        });
                    } else {
                        timeSelect.innerHTML = "<option value=''>No available slots for this date</option>";
                    }
                });
        }
    </script>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>