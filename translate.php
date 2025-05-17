<?php
require 'db.php'; // Ensure this is correctly configured for your DB
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: signin.php");
    exit();
}

// Initialize result to store the output and default $direction
$result = [];
$direction = ''; // Default value

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = $_POST;
        $text = $data['text'];
        $direction = $data['direction']; // yakan-to-english or english-to-yakan

        if ($direction === 'yakan-to-english') {
            // Translate Yakan to English
            // Query to fetch English sentence related to Yakan sentence
            $stmt = $db->prepare("SELECT english_sentence 
                                  FROM english_sentences 
                                  WHERE id = (SELECT english_sentence_id 
                                              FROM yakan_sentences 
                                              WHERE yakan_sentence = ? 
                                              AND is_validated = 1 LIMIT 1)");
            $stmt->execute([$text]);
            $translation = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($translation) {
                $result['translation'] = $translation['english_sentence'];
            } else {
                $result['translation'] = 'Translation not found or not validated.';
            }
        } elseif ($direction === 'english-to-yakan') {
            // Translate English to Yakan
            // Query to fetch Yakan sentence and audio related to English sentence
            $stmt = $db->prepare("SELECT yakan_sentence, audio_path 
                                  FROM yakan_sentences 
                                  WHERE english_sentence_id = (
                                      SELECT id 
                                      FROM english_sentences 
                                      WHERE english_sentence = ? 
                                      AND is_validated = 1 LIMIT 1)
                                  AND is_validated = 1 LIMIT 1");
            $stmt->execute([$text]);
            $translation = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($translation) {
                $result['translation'] = $translation['yakan_sentence'];
                $result['audio'] = $translation['audio_path'] ?? null;
            } else {
                $result['translation'] = 'Translation not found or not validated.';
            }
        }
    } catch (PDOException $e) {
        // Handle DB errors gracefully
        $result['error'] = 'Database error: ' . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yakan-English Translator</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background: url('./uploads/translator.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }
        nav ul li {
            display: inline;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 20px;
            transition: background-color 0.3s, transform 0.2s;
        }
        nav ul li a:hover {
            background: #f39c12;
            color: white;
            transform: scale(1.1);
        }
        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 100px; /* Ensure space for fixed header */
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            width: 600px;
            text-align: center;
            margin: 50px auto;
            max-width: 90%;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .input-box, .output-box {
            width: 100%;
            height: 120px;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin: 10px 0;
            font-size: 18px;
            resize: vertical;
            box-sizing: border-box;
        }
        select, button {
            width: 100%;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-sizing: border-box;
        }
        select {
            border: 1px solid #007bff;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        button:hover {
            background-color: #0056b3;
        }
        .output {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .suggestions {
            margin-top: 10px;
            font-size: 16px;
            color: gray;
            display: none;
        }
        .suggestions ul {
            padding: 0;
            list-style-type: none;
        }
        .suggestions li {
            cursor: pointer;
            margin-bottom: 5px;
            padding: 8px;
            background-color: #f1f1f1;
            border-radius: 4px;
        }
        .suggestions li:hover {
            background-color: #ddd;
        }
        /* Button Icons */
        .icon {
            margin-right: 8px;
        }
        footer {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 15px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 16px;
        }
        footer a {
            color: #f39c12;
            text-decoration: none;
            font-weight: bold;
        }
        footer a:hover {
            text-decoration: underline;
        }
        /* Mobile Styles */
        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
                padding: 25px;
            }
            h1 {
                font-size: 24px;
            }
            .input-box, .output-box, select, button {
                font-size: 16px;
                padding: 12px;
            }
            header {
                flex-direction: column;
                text-align: center;
            }
            nav ul {
                flex-direction: column;
                gap: 10px;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
<header>
        <h1>BASILAN SPEAKS</h1>
        <nav>
            <ul>
                <li><a href="dictionary.php">Dictionary</a></li>
                <li><a href="history.php">History</a></li>
                <li><a href="translate.php">Translation</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
</header>
<main>
<div class="container">
    <h1>Yakan-English Translator</h1>

    <!-- Translation Form -->
    <form method="POST" action="translate.php">
        <!-- Suggestions Box (pop-up as user types) -->
        <div id="suggestions" class="suggestions">
            <ul></ul>
        </div>

        <textarea id="inputText" name="text" class="input-box" placeholder="Enter text for translation..." required></textarea>

        <!-- Language Direction Selection -->
        <select name="direction" required>
            <option value="yakan-to-english">Yakan to English</option>
            <option value="english-to-yakan">English to Yakan</option>
        </select>

        <!-- Speak Button -->
        <button type="button" onclick="startRecognition()">
            <span class="icon">🎤</span> Speak
        </button>

        <!-- Translate Button -->
        <button type="submit">
            <span class="icon">🔄</span> Translate
        </button>
    </form>

    <!-- Output Box -->
    <?php if (isset($result['translation'])): ?>
        <div class="output">
            <h3>Translation Result:</h3>
            <div class="output-box">
                <strong>Translation:</strong> <?php echo htmlspecialchars($result['translation']); ?>
            </div>
            <?php if (isset($result['audio']) && $result['audio']): ?>
                <p><strong>Audio:</strong></p>
                <audio controls autoplay>
                    <source src="<?php echo htmlspecialchars($result['audio']); ?>" type="audio/mp3">
                    Your browser does not support the audio element.
                </audio>
            <?php elseif ($direction === 'yakan-to-english'): ?>
                <!-- Text-to-Speech API for English Translation -->
                <script>
                    const text = "<?php echo addslashes($result['translation']); ?>";
                    const speech = new SpeechSynthesisUtterance(text);
                    speech.lang = "en-US";
                    window.speechSynthesis.speak(speech);
                </script>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>
</main>

<script>
// Show suggestions as the user types
document.getElementById("inputText").addEventListener("input", function() {
    const text = this.value;
    const suggestionsDiv = document.getElementById("suggestions");
    const direction = document.querySelector("select[name='direction']").value;
    if (text.length > 2) {
        fetch(`suggestions.php?text=${text}&direction=${direction}`)
            .then(response => response.json())
            .then(data => {
                let suggestionsHtml = '';
                data.suggestions.forEach(suggestion => {
                    suggestionsHtml += `<li onclick="selectSuggestion('${suggestion}')">${suggestion}</li>`;
                });
                suggestionsDiv.querySelector("ul").innerHTML = suggestionsHtml;
                suggestionsDiv.style.display = suggestionsHtml ? 'block' : 'none';
            });
    } else {
        suggestionsDiv.style.display = 'none';
    }
});

// Select a suggestion
function selectSuggestion(suggestion) {
    document.getElementById("inputText").value = suggestion;
    document.getElementById("suggestions").style.display = 'none';
}

// Speech-to-Text functionality
function startRecognition() {
    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.lang = document.querySelector("select[name='direction']").value === 'yakan-to-english' ? 'en-US' : 'en-US';
    recognition.onresult = function(event) {
        const spokenText = event.results[0][0].transcript;
        document.getElementById("inputText").value = spokenText;
        triggerSuggestions(spokenText);
    };
    recognition.onerror = function(event) {
        console.error('Speech recognition error', event.error);
    };
    recognition.start();
}

// Trigger suggestions based on speech input
function triggerSuggestions(text) {
    const suggestionsDiv = document.getElementById("suggestions");
    const direction = document.querySelector("select[name='direction']").value;
    if (text.length > 2) {
        fetch(`suggestions.php?text=${text}&direction=${direction}`)
            .then(response => response.json())
            .then(data => {
                let suggestionsHtml = '';
                data.suggestions.forEach(suggestion => {
                    suggestionsHtml += `<li onclick="selectSuggestion('${suggestion}')">${suggestion}</li>`;
                });
                suggestionsDiv.querySelector("ul").innerHTML = suggestionsHtml;
                suggestionsDiv.style.display = suggestionsHtml ? 'block' : 'none';
            });
    } else {
        suggestionsDiv.style.display = 'none';
    }
}
</script>

<footer>
    &copy; <?php echo date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
</footer>

</body>
</html>
