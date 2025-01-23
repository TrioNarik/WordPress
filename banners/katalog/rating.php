<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);

    if ($data['HEisWatched']) {

        // Aktualizacja ranking i głosów przy nowej wizycie
        updateRating();
    }
}


// ====================================
// Funkcja do zapisu danych do pliku
// =====================================
function updateRating() {
    $file_path = '../rating.json';

    // Odczyt aktualnych danych z pliku
    if (file_exists($file_path)) {
        $data = json_decode(file_get_contents($file_path), true);
        
        $currentRating = $data['rating'];
        // Reset po 5-ciu
        if ($currentRating >= 5) {
            $currentRating = 4.795;
        }
        
        $currentVotes = $data['votes'];
        // Reset po 100
        if ($currentVotes >= 102) {
            $currentVotes = 12;
        }

    } else {
        $currentRating = 4.795;
        $currentVotes = 12;
    }

    // Zwiększenie ranking o 0.01 i liczby głosów o 1
    $newRating = bcadd($currentRating, '0.00025', 4); // Używamy bcadd z precyzją do 4 miejsc po przecinku
    $newVotes = $currentVotes + 1;

    // Zapis nowych danych do pliku
    $data = array('rating' => $newRating, 'votes' => $newVotes);
    file_put_contents($file_path, json_encode($data));
}
?>