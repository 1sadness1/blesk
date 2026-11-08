const slides = [...document.querySelectorAll('.slide')];
        const dots = [...document.querySelectorAll('.dot')];
        let currentSlide = 0;
        let sliderTimer;

        function showSlide(index) {
            currentSlide = (index + slides.length) % slides.length;
            slides.forEach((slide, i) => slide.classList.toggle('active', i === currentSlide));
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentSlide);
                if (i === currentSlide) dot.setAttribute('aria-current', 'true');
                else dot.removeAttribute('aria-current');
            });
        }

        function restartSlider() {
            clearInterval(sliderTimer);
            sliderTimer = setInterval(() => showSlide(currentSlide + 1), 3000);
        }

        document.querySelector('.previous').addEventListener('click', () => {
            showSlide(currentSlide - 1);
            restartSlider();
        });
        document.querySelector('.next').addEventListener('click', () => {
            showSlide(currentSlide + 1);
            restartSlider();
        });
        dots.forEach((dot, index) => dot.addEventListener('click', () => {
            showSlide(index);
            restartSlider();
        }));
        restartSlider();