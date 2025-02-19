<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Przeciąganie i przesuwanie elementów</title>
    <style>

        /* Efekt oscylowania dla front-panel */
        .dropzone.highlight {
            animation: pulse 0.5s infinite;
        }

        @keyframes pulse {
            0% {
                border-color: #aaa;
            }
            50% {
                border-color: #f00;
            }
            100% {
                border-color: #aaa;
            }
        }

        /* Przyciemnienie reszty strony */
        body.dragging-active {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .dropzone {
            width: 300px;
            height: 300px;
            border: 2px dashed #aaa;
            position: relative;
            overflow: hidden;
        }
        .design {
            width: 100px;
            height: 100px;
            background-color: lightblue;
            border: 2px solid #333;
            cursor: grab;
        }
        div[draggable="false"] {
            cursor: move;
            border: 2px dashed #f00; /* Przykładowa ramka */
            background-color: #f9f9f9; /* Przykładowe tło */
        }
        .dropzone .design {
            position: absolute;
            z-index:100;
        }
        .design.dragging {
            opacity: 0.5;
        }
        .delete-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            background: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            cursor: pointer;
            font-size: 12px;
            line-height: 18px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="dropzone" id="front-panel"></div>
</div>

<div style="display:flex; gap: 20px;">
    <div class="design" id="design1" draggable="true">Design 1</div>

    <div class="design" id="design2" draggable="true">Design 2</div>

    <div class="design" id="design3" draggable="true">Design 3</div>
</div>

<script>
const designs = document.querySelectorAll('.design');
const frontPanel = document.getElementById('front-panel');

let offsetX, offsetY;

designs.forEach(design => {
    design.addEventListener('dragstart', (e) => {
        e.dataTransfer.setData('text/plain', design.id);
        design.classList.add('dragging');

        // Dodaj klasę do body, aby przyciemnić resztę strony
        document.body.classList.add('dragging-active');

        // Dodaj klasę do front-panel, aby rozpocząć oscylowanie
        frontPanel.classList.add('highlight');
    });

    design.addEventListener('dragend', () => {
        design.classList.remove('dragging');

        // Usuń klasę z body, aby usunąć przyciemnienie
        document.body.classList.remove('dragging-active');

        // Usuń klasę z front-panel, aby zakończyć oscylowanie
        frontPanel.classList.remove('highlight');
    });
});

frontPanel.addEventListener('dragover', (e) => {
    e.preventDefault();
});

frontPanel.addEventListener('drop', (e) => {
    e.preventDefault();
    const designId = e.dataTransfer.getData('text/plain');
    const draggedDesign = document.getElementById(designId);

    // Usuń poprzedni element w frontPanel
    frontPanel.innerHTML = '';

    const newDesign = draggedDesign.cloneNode(true);
    newDesign.classList.remove('dragging');
    newDesign.setAttribute('draggable', 'false'); // Wyłącz przeciąganie

    // Ustaw dokładną pozycję upuszczenia
    const rect = frontPanel.getBoundingClientRect();
    newDesign.style.left = `${e.clientX - rect.left - newDesign.offsetWidth / 2}px`;
    newDesign.style.top = `${e.clientY - rect.top - newDesign.offsetHeight / 2}px`;

    // Dodaj przycisk usuwania
    const deleteBtn = document.createElement('button');
    deleteBtn.classList.add('delete-btn');
    deleteBtn.textContent = '×';
    deleteBtn.onclick = (event) => {
        event.stopPropagation();
        newDesign.remove();
    };
    newDesign.appendChild(deleteBtn);

    frontPanel.appendChild(newDesign);

    enableDragging(newDesign, frontPanel);
});

function enableDragging(element, container) {
    element.addEventListener('mousedown', (e) => {
        if (e.target.classList.contains('delete-btn')) return;

        offsetX = e.clientX - element.getBoundingClientRect().left;
        offsetY = e.clientY - element.getBoundingClientRect().top;

        const onMouseMove = (moveEvent) => {
            const rect = container.getBoundingClientRect();
            let x = moveEvent.clientX - rect.left - offsetX;
            let y = moveEvent.clientY - rect.top - offsetY;

            // Ograniczenia ruchu w obrębie frontPanel z marginesem 10px
            x = Math.max(10, Math.min(x, rect.width - element.offsetWidth - 10));
            y = Math.max(10, Math.min(y, rect.height - element.offsetHeight - 10));

            element.style.left = `${x}px`;
            element.style.top = `${y}px`;
        };

        const onMouseUp = () => {
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
        };

        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);
    });
}

</script>

</body>
</html>
