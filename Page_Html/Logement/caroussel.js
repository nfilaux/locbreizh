document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('.slider');
    const leftArrow = document.querySelector('.left');
    const rightArrow = document.querySelector('.right');
    const indicatorParent = document.querySelector('.controls ul');
    const slides = document.querySelectorAll('.slide');

    let sectionIndex = 0;
    const totalSlides = slides.length;

    // Create indicators dynamically
    for (let i = 0; i < totalSlides; i++) {
        const indicator = document.createElement('li');
        indicator.addEventListener('click', function() {
            sectionIndex = i;
            updateSlider();
        });
        indicatorParent.appendChild(indicator);
    }

    const indicators = document.querySelectorAll('.controls li');
    
    function updateSlider() {
        const slideWidth = slides[0].offsetWidth; // Width of a single slide
        slider.style.transform = 'translate(' + (-sectionIndex * slideWidth) + 'px)';
        indicators.forEach((indicator, i) => {
            indicator.classList.toggle('selected', i === sectionIndex);
        });
    }

    leftArrow.addEventListener('click', function() {
        sectionIndex = (sectionIndex > 0) ? sectionIndex - 1 : totalSlides - 1;
        updateSlider();
    });

    rightArrow.addEventListener('click', function() {
        sectionIndex = (sectionIndex < totalSlides - 1) ? sectionIndex + 1 : 0;
        updateSlider();
    });

    // Initial setup
    updateSlider();
});