<?php 
require 'db.php';

$language = isset($_GET['language']) ? $_GET['language'] : 'Yakan';

$stmt = $db->prepare("SELECT * FROM media_history WHERE language = :language ORDER BY uploaded_at DESC");
$stmt->execute(['language' => $language]);
$media_history = $stmt->fetchAll(PDO::FETCH_ASSOC);

$language_options = ['Yakan', 'English'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - Basilan Speaks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('./uploads/translator.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding-top: 70px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background: rgba(0, 0, 0, 0.8);
            padding: 10px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar-nav .nav-link {
            color: white;
            margin-left: 15px;
            font-size: 16px;
            transition: color 0.3s;
        }

        .navbar-nav .nav-link:hover {
            color: #f39c12;
        }

        .container-main {
            flex: 1;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            margin: 30px auto;
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
            width: 90%;
            max-width: 1000px;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .gallery-item {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            cursor: pointer;
        }

        .gallery-item img, .gallery-item video {
            width: 100%;
            height: auto;
            display: block;
        }

        .caption {
            background: #f1f1f1;
            padding: 10px;
            font-size: 14px;
        }

        footer {
            background: rgba(0,0,0,0.8);
            color: white;
            text-align: center;
            padding: 15px 0;
            font-size: 14px;
            margin-top: auto;
        }

        footer a {
            color: #f39c12;
            text-decoration: none;
            font-weight: 500;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
            z-index: 1001;
        }

        .modal-content {
            background: white;
            border-radius: 10px;
            padding: 20px;
            max-width: 90%;
            max-height: 90%;
            overflow: auto;
        }

        .modal img, .modal video {
            width: 100%;
            height: auto;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 30px;
            color: white;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .navbar-nav {
                background: rgba(0,0,0,0.9);
                padding: 10px;
                border-radius: 8px;
            }

            .gallery {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">BASILAN SPEAKS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="no_dictionary.php">Dictionary</a></li>
                    <li class="nav-item"><a class="nav-link" href="no_history.php">History</a></li>
                    <li class="nav-item"><a class="nav-link" href="no_translate.php">Translation</a></li>
                    <li class="nav-item"><a class="nav-link" href="selection.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="container-main">
    <h2 class="text-center mb-4">History of <?= htmlspecialchars($language) ?> Translations</h2>

    <form method="get" action="history.php" class="text-center mb-4">
        <label for="language">Select Language:</label>
        <select name="language" id="language" onchange="this.form.submit()" class="form-select d-inline w-auto ms-2">
            <?php foreach ($language_options as $option): ?>
                <option value="<?= htmlspecialchars($option) ?>" <?= $language == $option ? 'selected' : '' ?>><?= htmlspecialchars($option) ?></option>
            <?php endforeach; ?>
        </select>
    </form>

    <div class="gallery">
        <?php if (count($media_history) > 0): ?>
            <?php foreach ($media_history as $item): ?>
                <div class="gallery-item" onclick="openModal('<?= './uploads/' . htmlspecialchars($item['media_path']) ?>', '<?= htmlspecialchars($item['caption']) ?>')">
                    <?php if ($item['media_type'] == 'image'): ?>
                        <img src="<?= './uploads/' . htmlspecialchars($item['media_path']) ?>" alt="Image">
                    <?php elseif ($item['media_type'] == 'video'): ?>
                        <video controls>
                            <source src="<?= './uploads/' . htmlspecialchars($item['media_path']) ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php endif; ?>
                    <div class="caption"><?= htmlspecialchars(strlen($item['caption']) > 50 ? substr($item['caption'], 0, 50) . '...' : $item['caption']) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center fw-bold text-danger">No media history found for <?= htmlspecialchars($language) ?> translations.</p>
        <?php endif; ?>
    </div>
</main>

<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modal-media"></div>
        <div id="modal-caption"></div>
    </div>
</div>

<footer>
    &copy; <?= date("Y") ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
</footer>

<script>
function openModal(mediaUrl, caption) {
    const modal = document.getElementById("modal");
    const modalMedia = document.getElementById("modal-media");
    const modalCaption = document.getElementById("modal-caption");

    if (mediaUrl.endsWith('.mp4')) {
        modalMedia.innerHTML = `<video controls><source src="${mediaUrl}" type="video/mp4"></video>`;
    } else {
        modalMedia.innerHTML = `<img src="${mediaUrl}" alt="Image">`;
    }
    modalCaption.innerHTML = `<p>${caption}</p>`;
    modal.style.display = "flex";
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
