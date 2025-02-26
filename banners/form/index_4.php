<?php
function svg_code($svg_file) {
    if (file_exists($svg_file)) {
        echo file_get_contents($svg_file);
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Efekt Glitter</title>

  <body>

  <style>
  .glitter-container {
    width: 500px;
    height: 800px;
  }

  /* Efekt błyszczenia */
  @keyframes sparkle {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 1; }
  }

  .sparkle {
    animation: sparkle var(--blink-time) infinite alternate ease-in-out;
  }
</style>
<button id="download-btn">Pobierz SVG</button>

<div class="glitter-container">
  <svg id="my-svg" viewBox="0 0 300 300">
    <!-- Tło (bez brokatu) -->
    <rect width="100%" height="100%" fill="none"/>

    <!-- Elementy, na których ma być brokat -->
    <path id="czesc1" d="M50,50 L250,50 L150,200 Z" fill="black"/>
    <circle id="czesc2" cx="150" cy="220" r="50" fill="black"/>

    <!-- ClipPath - ogranicza brokat do wybranych kształtów -->
    <clipPath id="glitter-mask">
      <use href="#czesc1"/>
      <use href="#czesc2"/>
    </clipPath>

    <!-- Warstwa brokatu -->
    <g id="glitter-layer" clip-path="url(#glitter-mask)"></g>
  </svg>
</div>

<script>
    const blink = 1; // migotanie

  function getRandomGlitterColor() {
    const colors = ["#FFFFFF", "#FFFF99", "#FFD700", "#FFA500"]; // Biały, jasnożółty, złoty, pomarańczowy
    return colors[Math.floor(Math.random() * colors.length)];
  }

  function createGlitter(targetElement, numSparkles) {
    for (let i = 0; i < numSparkles; i++) {
      const x = Math.random() * 300;
      const y = Math.random() * 300;
      const fillColor = getRandomGlitterColor();
      const opacity = Math.random() * 0.25 + 0.25;

      const shapeType = Math.random() < 0.33 ? 'star' : 'ellipse';

      if (shapeType === 'star') {
        // 1/3 elementów to gwiazdki
        const star = document.createElementNS("http://www.w3.org/2000/svg", "polygon");
        star.setAttribute("points", "2,0 4,4 8,4 5,7 6,11 2,9 0,11 1,7 -2,4 2,4");
        star.setAttribute("fill", fillColor);
        star.setAttribute("opacity", opacity);
        
        // Losowy rozmiar gwiazdek (mniejsze niż wcześniej)
        const scale = Math.random() * 0.3 + 0.1; // 0.1 do 0.7
        star.setAttribute("transform", `translate(${x},${y}) scale(${scale})`);
        
        if (blink && Math.random() < 0.1) { // 10% gwiazdek miga
          star.classList.add("sparkle");
          star.style.setProperty("--blink-time", `${(Math.random() * 4 + 1).toFixed(2)}s`); // 1s do 5s
        }
        targetElement.appendChild(star);
      } else {
        // Reszta to plamki
        const ellipse = document.createElementNS("http://www.w3.org/2000/svg", "ellipse");
        ellipse.setAttribute("cx", x);
        ellipse.setAttribute("cy", y);
        ellipse.setAttribute("rx", Math.random() * 1.3 + 0.25); // Mniejsze niż wcześniej (0.5 do 2.5)
        ellipse.setAttribute("ry", Math.random() * 1.2 + 0.3);
        ellipse.setAttribute("fill", fillColor);
        ellipse.setAttribute("opacity", opacity);

        if (blink && Math.random() < 0.1) { // 10% plamek miga
          ellipse.classList.add("sparkle");
          ellipse.style.setProperty("--blink-time", `${(Math.random() * 4 + 1).toFixed(2)}s`); // 1s do 5s
        }

        targetElement.appendChild(ellipse);
      }
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    const glitterLayer = document.getElementById('glitter-layer');
    createGlitter(glitterLayer, 3000); // Dodaj n elementów brokatu (1/3 gwiazdki)

    
    document.getElementById('download-btn').addEventListener('click', () => {
      const svgContent = document.getElementById('my-svg').outerHTML;
      const blob = new Blob([svgContent], { type: 'image/svg+xml' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = 'glitter.svg';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    });

  });
</script>


</body>
</html>