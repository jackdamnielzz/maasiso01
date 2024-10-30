document.addEventListener('DOMContentLoaded', function() {
    // Initialize carousel elements
    const carousel = document.querySelector('.iso-blocks-container');
    const blocks = document.querySelectorAll('.iso-block');
    const totalBlocks = blocks.length;
    let currentIndex = 0;
    let blocksPerView = getBlocksPerView();

    // Create navigation buttons
    const prevButton = document.createElement('button');
    prevButton.className = 'carousel-nav carousel-prev';
    prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
    prevButton.setAttribute('aria-label', 'Vorige ISO bouwstenen');

    const nextButton = document.createElement('button');
    nextButton.className = 'carousel-nav carousel-next';
    nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
    nextButton.setAttribute('aria-label', 'Volgende ISO bouwstenen');

    // Create indicators
    const indicatorsContainer = document.createElement('div');
    indicatorsContainer.className = 'carousel-indicators';
    const totalIndicators = Math.ceil(totalBlocks / blocksPerView);
    
    for (let i = 0; i < totalIndicators; i++) {
        const indicator = document.createElement('button');
        indicator.className = 'carousel-indicator' + (i === 0 ? ' active' : '');
        indicator.setAttribute('aria-label', `Ga naar set ${i + 1}`);
        indicator.addEventListener('click', () => goToSlide(i * blocksPerView));
        indicatorsContainer.appendChild(indicator);
    }

    // Add elements to DOM
    const carouselWrapper = document.querySelector('.iso-blocks-carousel');
    carouselWrapper.appendChild(prevButton);
    carouselWrapper.appendChild(nextButton);
    carouselWrapper.appendChild(indicatorsContainer);

    // Navigation functions
    function getBlocksPerView() {
        if (window.innerWidth <= 767) return 1;
        if (window.innerWidth <= 1023) return 2;
        return 3;
    }

    function updateCarousel() {
        const slideWidth = 100 / blocksPerView;
        const offset = -(currentIndex * slideWidth);
        carousel.style.transform = `translateX(${offset}%)`;

        // Update indicators
        const indicators = document.querySelectorAll('.carousel-indicator');
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', Math.floor(currentIndex / blocksPerView) === index);
        });

        // Update navigation buttons visibility
        prevButton.style.visibility = currentIndex <= 0 ? 'hidden' : 'visible';
        nextButton.style.visibility = currentIndex >= totalBlocks - blocksPerView ? 'hidden' : 'visible';
    }

    function nextSlide() {
        if (currentIndex < totalBlocks - blocksPerView) {
            currentIndex++;
            updateCarousel();
        }
    }

    function previousSlide() {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    }

    function goToSlide(index) {
        currentIndex = Math.min(Math.max(index, 0), totalBlocks - blocksPerView);
        updateCarousel();
    }

    // Event listeners
    prevButton.addEventListener('click', previousSlide);
    nextButton.addEventListener('click', nextSlide);

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const newBlocksPerView = getBlocksPerView();
            if (newBlocksPerView !== blocksPerView) {
                blocksPerView = newBlocksPerView;
                currentIndex = Math.min(currentIndex, totalBlocks - blocksPerView);
                updateCarousel();
            }
        }, 250);
    });

    // Initialize carousel
    updateCarousel();

    // Contact form integration
    const isoBlockButtons = document.querySelectorAll('.iso-block-button');
    isoBlockButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const blockTitle = this.closest('.iso-block').querySelector('h3').textContent;
            window.location.href = `contact.html?subject=${encodeURIComponent(`Informatie aanvraag: ${blockTitle}`)}`;
        });
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            previousSlide();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
        }
    });
});
