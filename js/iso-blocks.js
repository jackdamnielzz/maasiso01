document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.iso-blocks-container');
    const blocks = document.querySelectorAll('.iso-block');
    let currentIndex = 0;
    let isMobile = window.innerWidth <= 768;
    let carouselInitialized = false;

    function initializeCarousel() {
        if (!carouselInitialized && isMobile) {
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
            const totalIndicators = blocks.length;
            
            for (let i = 0; i < totalIndicators; i++) {
                const indicator = document.createElement('button');
                indicator.className = 'carousel-indicator' + (i === 0 ? ' active' : '');
                indicator.setAttribute('aria-label', `Ga naar bouwsteen ${i + 1}`);
                indicator.addEventListener('click', () => goToSlide(i));
                indicatorsContainer.appendChild(indicator);
            }

            // Add elements to DOM
            const carouselWrapper = document.querySelector('.iso-blocks-carousel');
            carouselWrapper.appendChild(prevButton);
            carouselWrapper.appendChild(nextButton);
            carouselWrapper.appendChild(indicatorsContainer);

            // Add event listeners
            prevButton.addEventListener('click', previousSlide);
            nextButton.addEventListener('click', nextSlide);

            carouselInitialized = true;
            updateCarousel();
        }
    }

    function destroyCarousel() {
        if (carouselInitialized) {
            // Remove navigation elements
            const carouselWrapper = document.querySelector('.iso-blocks-carousel');
            const navButtons = carouselWrapper.querySelectorAll('.carousel-nav');
            const indicators = carouselWrapper.querySelector('.carousel-indicators');
            
            navButtons.forEach(button => button.remove());
            indicators?.remove();

            // Reset carousel position
            carousel.style.transform = '';
            currentIndex = 0;
            carouselInitialized = false;
        }
    }

    function updateCarousel() {
        if (!isMobile) return;

        const offset = -(currentIndex * 100);
        carousel.style.transform = `translateX(${offset}%)`;

        // Update indicators
        const indicators = document.querySelectorAll('.carousel-indicator');
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentIndex);
        });

        // Update navigation buttons visibility
        const prevButton = document.querySelector('.carousel-prev');
        const nextButton = document.querySelector('.carousel-next');
        if (prevButton && nextButton) {
            prevButton.style.visibility = currentIndex <= 0 ? 'hidden' : 'visible';
            nextButton.style.visibility = currentIndex >= blocks.length - 1 ? 'hidden' : 'visible';
        }
    }

    function nextSlide() {
        if (currentIndex < blocks.length - 1) {
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
        currentIndex = index;
        updateCarousel();
    }

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const wasMobile = isMobile;
            isMobile = window.innerWidth <= 768;

            if (wasMobile !== isMobile) {
                if (isMobile) {
                    initializeCarousel();
                } else {
                    destroyCarousel();
                }
            }
        }, 250);
    });

    // Initialize based on current screen size
    if (isMobile) {
        initializeCarousel();
    }

    // Contact form integration
    const isoBlockButtons = document.querySelectorAll('.iso-block-button');
    isoBlockButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const blockTitle = this.closest('.iso-block').querySelector('h3').textContent;
            window.location.href = `contact.html?subject=${encodeURIComponent(`Informatie aanvraag: ${blockTitle}`)}`;
        });
    });

    // Keyboard navigation for mobile
    document.addEventListener('keydown', function(e) {
        if (!isMobile) return;
        
        if (e.key === 'ArrowLeft') {
            previousSlide();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
        }
    });
});
