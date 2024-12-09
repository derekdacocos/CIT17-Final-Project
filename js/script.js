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
