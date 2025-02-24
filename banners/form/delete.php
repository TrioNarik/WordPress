<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $filePath = $data['filePath'] ?? '';

    if ($filePath && file_exists($filePath)) {
        if (unlink($filePath)) {
            echo json_encode(['success' => 'Plik usunięty.']);
        } else {
            echo json_encode(['error' => 'Nie udało się usunąć pliku.']);
        }
    } else {
        echo json_encode(['error' => 'Plik nie istnieje.']);
    }
}
?>
