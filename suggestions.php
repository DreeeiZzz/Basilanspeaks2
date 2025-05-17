<?php
require 'db.php'; // Ensure this is correctly configured for your DB

// Validate input parameters
if (!isset($_GET['text']) || !isset($_GET['direction'])) {
    echo json_encode(['error' => 'Invalid parameters.']);
    exit;
}

$text = $_GET['text'];
$direction = $_GET['direction'];

try {
    $suggestions = [];

    // Determine query based on direction
    if ($direction === 'yakan-to-english') {
        // Query for Yakan sentences similar to the input text
        $stmt = $db->prepare("SELECT yakan_sentence FROM yakan_sentences WHERE yakan_sentence LIKE ? LIMIT 5");
        $stmt->execute(['%' . $text . '%']);
    } elseif ($direction === 'english-to-yakan') {
        // Query for English sentences similar to the input text
        $stmt = $db->prepare("SELECT english_sentence FROM english_sentences WHERE english_sentence LIKE ? LIMIT 5");
        $stmt->execute(['%' . $text . '%']);
    } else {
        echo json_encode(['error' => 'Invalid direction.']);
        exit;
    }

    // Fetch suggestions
    $suggestions = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Return suggestions as JSON
    echo json_encode(['suggestions' => $suggestions]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
