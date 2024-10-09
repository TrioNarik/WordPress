document.addEventListener('DOMContentLoaded', function() {
    const sliders = document.querySelectorAll('.scroll_slider');
    let currentSlider = 0;
    let currentHeading = 0;

    // Funkcja rozbija tekst nagłówka na litery
    function splitTextIntoSpans(element) {
        const text = element.textContent;
        element.innerHTML = ''; // Wyczyść aktualny tekst
        text.split('').forEach(letter => {
            const span = document.createElement('span');
            span.textContent = letter;
            span.classList.add('slide-letter');
            element.appendChild(span);
        });
    }

    // Dla każdego nagłówka w sliderze rozbijamy tekst na litery
    sliders.forEach(slider => {
        const headings = slider.querySelectorAll('.slide-heading');
        headings.forEach(heading => splitTextIntoSpans(heading));
    });

    // Funkcja animująca literki przy przewijaniu
    function animateLetters(scrollDirection) {
        const currentHeadings = sliders[currentSlider].querySelectorAll('.slide-heading');
        const heading = currentHeadings[currentHeading];
        const letters = Array.from(heading.querySelectorAll('.slide-letter'));

        // Losowo permutujemy indeksy liter
        const shuffledIndexes = letters.map((_, index) => index).sort(() => Math.random() - 0.5);

        shuffledIndexes.forEach((index, i) => {
            const letter = letters[index];
            setTimeout(() => {
                if (scrollDirection === 'down') {
                    letter.classList.add('scroll-up');
                    letter.classList.remove('scroll-down');
                } else if (scrollDirection === 'up') {
                    letter.classList.add('scroll-down');
                    letter.classList.remove('scroll-up');
                }
            }, i * 50); // Losowe opóźnienie między literkami
        });
    }

    function handleScroll() {
        const scrollDirection = window.oldScroll > window.scrollY ? 'up' : 'down';
        window.oldScroll = window.scrollY;

        // Wywołaj animację liter w zależności od kierunku przewijania
        animateLetters(scrollDirection);
    }

    window.addEventListener('scroll', handleScroll);

    // Pokaż pierwszy nagłówek na starcie
    animateLetters('up');
});
