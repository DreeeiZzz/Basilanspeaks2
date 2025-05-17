<?php 

require 'db.php'; // Include your DB connection file
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID
$language = isset($_GET['language']) ? $_GET['language'] : 'Yakan'; // Default to 'Yakan'

// Fetch the history of images and videos for the logged-in user, filtered by language
$stmt = $db->prepare("SELECT * FROM media_history WHERE language = :language ORDER BY uploaded_at DESC");
$stmt->execute(['language' => $language]);
$media_history = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle language change
$language_options = ['Yakan', 'English'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('./uploads/translator.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
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
            transform: scale(1.1);
        }

        .container {
            margin-top: 100px;
            padding: 30px;
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            max-width: 800px;
            margin: 120px auto;
        }

        .container h2 {
            color: #333;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .gallery-item {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            cursor: pointer;
        }

        .gallery-item img, .gallery-item video {
            width: 100%;
            height: auto;
            display: block;
        }

        .caption {
            padding: 10px;
            font-size: 16px;
            background-color: #f1f1f1;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .modal-content {
            max-width: 90%;
            max-height: 90%;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            overflow: auto;
        }

        .modal img, .modal video {
            width: 100%;
            height: auto;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            color: white;
            font-size: 30px;
            cursor: pointer;
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

        @media (max-width: 768px) {
            header h1 {
                font-size: 24px;
            }

            nav ul {
                flex-direction: column;
                align-items: center;
            }

            .container {
                width: 90%;
                padding: 20px;
            }

            .gallery {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
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

    <div class="container">
        <h2>History of <?php echo htmlspecialchars($language); ?> Translations</h2>

        <!-- Dropdown to select language -->
        <form method="get" action="history.php">
            <label for="language">Select Language: </label>
            <select name="language" id="language" onchange="this.form.submit()">
                <?php foreach ($language_options as $option): ?>
                    <option value="<?php echo $option; ?>" <?php echo $language == $option ? 'selected' : ''; ?>><?php echo $option; ?></option>
                <?php endforeach; ?>
            </select>
        </form>

        <div class="gallery">
            <?php if (count($media_history) > 0): ?>
                <?php foreach ($media_history as $item): ?>
                    <div class="gallery-item" onclick="openModal('<?php echo './uploads/' . htmlspecialchars($item['media_path']); ?>', '<?php echo htmlspecialchars($item['caption']); ?>')">
                        <?php 
                        // Construct the URL path for media
                        $media_path = './uploads/' . htmlspecialchars($item['media_path']);
                        // Display only the first 50 characters of the caption in the gallery
                        $short_caption = strlen($item['caption']) > 50 ? substr($item['caption'], 0, 50) . '...' : $item['caption'];
                        ?>
                        <?php if ($item['media_type'] == 'image'): ?>
                            <img src="<?php echo $media_path; ?>" alt="Media">
                        <?php elseif ($item['media_type'] == 'video'): ?>
                            <video controls>
                                <source src="<?php echo $media_path; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php endif; ?>
                        <div class="caption">
                            <p><?php echo htmlspecialchars($short_caption); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No media history found for <?php echo htmlspecialchars($language); ?> translations.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="modal-media"></div>
            <div id="modal-caption"></div>
        </div>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
    </footer>

    <script>
        function openModal(mediaUrl, caption) {
            var modal = document.getElementById("modal");
            var modalMedia = document.getElementById("modal-media");
            var modalCaption = document.getElementById("modal-caption");

            if (mediaUrl.endsWith('.mp4')) {
                modalMedia.innerHTML = `<video controls><source src="${mediaUrl}" type="video/mp4">Your browser does not support the video tag.</video>`;
            } else {
                modalMedia.innerHTML = `<img src="${mediaUrl}" alt="Media">`;
            }
            modalCaption.innerHTML = `<p>${caption}</p>`;
            modal.style.display = "flex";
        }

        function closeModal() {
            var modal = document.getElementById("modal");
            modal.style.display = "none";
        }
    </script>
</body>
</html>
