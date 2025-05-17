<?php
require 'db.php'; // Ensure this is correctly configured for your DB


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
     <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JavaScript Bundle (Includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
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
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
    margin-top: 120px; /* Push content below the navbar */
    padding: 30px;
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    border-radius: 12px;
    max-width: 900px;
    margin: auto;
    text-align: center;
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
            box-sizing: border-box;
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
        }
       header {
    background: black; /* Keep navbar solid */
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
    height: 80px; /* Ensures enough height */
}
main {
            margin-top: 200px; /* Space for fixed header */
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 100px);
        }

        header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        nav .navbar-collapse {
            background: rgba(0, 0, 0, 0.9);
            padding: 10px 20px;
            border-radius: 10px;
            position: absolute;
            top: 100%;
            right: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            z-index: 999;
        }

        .nav-item {
            margin: 5px 0;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 20px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .nav-link:hover {
            background: #f39c12;
            transform: scale(1.1);
        }

    </style>
</head>
<body>
<header>
        <h1>BASILAN SPEAKS</h1>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="no_dictionary.php">Dictionary</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="no_history.php">History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="no_translate.php">Translation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="selection.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
<div class="container">
    <h1>Yakan-English Translator</h1>

    <!-- Translation Form -->
    <form method="POST" action="no_translate.php">
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
