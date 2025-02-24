<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];

    // Sprawdzamy rozszerzenie pliku
    $allowedTypes = ['image/jpeg', 'image/png'];
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    if (!in_array($file['type'], $allowedTypes) || !in_array($fileExtension, ['jpg', 'png'])) {
        echo json_encode(['error' => 'Nieprawidłowy format pliku!']);
        exit;
    }

    // Sprawdzamy rozmiar pliku
    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode(['error' => 'Plik jest za duży!']);
        exit;
    }

    // Katalog docelowy
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generujemy bezpieczną nazwę pliku
    $safeFileName = uniqid('file_', true) . '.' . $fileExtension;
    $uploadFile = $uploadDir . $safeFileName;

    // Zapis pliku
    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        echo json_encode(['success' => 'Plik zapisany!', 'file' => $uploadFile]);
    } else {
        echo json_encode(['error' => 'Błąd podczas zapisu pliku!']);
    }
} else {
    echo json_encode(['error' => 'Nieprawidłowe żądanie!']);
}
?>
