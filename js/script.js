document.addEventListener('DOMContentLoaded', function() {
    const testimonialSlider = document.querySelector('.testimonial-slider');
    testimonialSlider.addEventListener('wheel', (e) => {
        if (e.deltaY > 0) {
            testimonialSlider.scrollBy(300, 0); // Scroll right
        } else {
            testimonialSlider.scrollBy(-300, 0); // Scroll left
        }
    });
});

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
    fetch('booking.php?date=' + date) // Same file, passing date as query parameter
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

// Navigation between steps
function nextStep(step) {
    const currentStep = document.querySelector('.form-step.active');
    const nextStep = document.getElementById('step' + step);

    currentStep.classList.remove('active');
    nextStep.classList.add('active');
}

function prevStep(step) {
    const currentStep = document.querySelector('.form-step.active');
    const prevStep = document.getElementById('step' + step);

    currentStep.classList.remove('active');
    prevStep.classList.add('active');
}
