function updateCanvas(canvasEl, font, text, name, rating, badge, backgroundColor = false) {
    const canvas = canvasEl; // Użyj przekazanego elementu canvas zamiast pobierania go ponownie
    const container = canvas.parentNode;
    const ctx = canvas.getContext("2d");

    // Ustaw szerokość i wysokość canvas na 100% szerokości 
    canvas.width = container.clientWidth;
    canvas.height = 75;

    const width = canvas.width;
    const height = canvas.height;

    ctx.clearRect(0, 0, width, height);
    ctx.fillStyle = backgroundColor ? backgroundColor : "#F0F0F0";
    ctx.fillRect(0, 0, width, height);

    // Draw a Ranking circle
    for (let i = 0; i < 5; i++) {
        ctx.beginPath();
        ctx.arc(width - (6 * (i + 1)), 10, 2, 0, 2 * Math.PI);
        ctx.strokeStyle = "#000000"; // Kolor obrysu
        ctx.stroke();
    }

    // Wypełnianie kół na podstawie oceny
    for (let i = 0; i < rating; i++) {
        ctx.beginPath();
        ctx.arc(width - (6 * (i + 1)), 10, 2, 0, 2 * Math.PI);
        ctx.fillStyle = "#000000"; // Kolor wypełnienia
        ctx.fill();
    }

    // Draw name
    ctx.font = "10px Montserrat, sans-serif";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "left";
    ctx.fillText(name, 5, 10);

    // Draw badge
    ctx.font = "9px Montserrat, sans-serif";
    ctx.fillStyle = "#000000";
    ctx.fontVariantCaps = "small-caps";
    ctx.letterSpacing = "5px";
    ctx.textAlign = "left";
    ctx.fillText(badge, 5, height / 2);

    ctx.font = font;
    ctx.fillStyle = "#000000";
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    const displayText = text.trim() ? text : `${name}`;
    ctx.fillText(displayText, width / 2, height / 2);
}

function updateAllCanvases() {
    const inputText = encodeHTML(document.getElementById("personalizationText").value);
    // const backgroundColor = document.getElementById("favcolor").value;

    document.querySelectorAll('canvas').forEach(canvasEl => {
        const font = canvasEl.getAttribute('data-font');
        const name = canvasEl.getAttribute('data-name');
        const rating = parseInt(canvasEl.getAttribute('data-rating'), 10); // Rating jest liczbą
        const badge = canvasEl.getAttribute('data-badge');
        updateCanvas(canvasEl, font, inputText, name, rating, badge);

        // Dodaj obsługę zdarzenia click
        canvasEl.addEventListener('click', () => {
            // Usuń klasę .selected z wszystkich canvasów
            document.querySelectorAll('canvas').forEach(c => c.classList.remove('selected'));
            // Dodaj klasę .selected do klikniętego canvasu
            canvasEl.classList.add('selected');

            document.getElementById("previewText").setAttribute("font-family", name);
        });
    });
}

function encodeHTML(str) {
    return str.replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
}

document.getElementById("personalizationText").addEventListener("input", updateAllCanvases);
// document.getElementById("favcolor").addEventListener("input", updateAllCanvases);

updateAllCanvases(); // Initial update
