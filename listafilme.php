<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'database_connection.php';

function slugify($text) {
    $text = str_replace(' ', '-', $text);
    $text = preg_replace('/[^A-Za-z0-9\-]/', '', $text);
    $text = strtolower($text);
    return $text;
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$typeFilter = isset($_GET['type']) && $_GET['type'] != '' ? "tip = '" . $conn->real_escape_string($_GET['type']) . "'" : '';
$genreFilter = isset($_GET['genre']) && $_GET['genre'] != '' ? "gen = '" . $conn->real_escape_string($_GET['genre']) . "'" : '';
$yearFilter = isset($_GET['year']) && $_GET['year'] != '' ? "an_lansare = " . intval($_GET['year']) : '';
$platformFilter = isset($_GET['platform']) && $_GET['platform'] != '' ? "platforma_streaming = '" . $conn->real_escape_string($_GET['platform']) . "'" : '';

$filters = array($typeFilter, $genreFilter, $yearFilter, $platformFilter);
$filters = array_filter($filters);
$whereClause = !empty($filters) ? " WHERE " . implode(' AND ', $filters) : '';

$sql = "SELECT idFS, titlu, tip, an_lansare FROM tblFilme_Seriale" . $whereClause . " LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$sqlCount = "SELECT COUNT(*) as total FROM tblFilme_Seriale" . $whereClause;
$resultCount = $conn->query($sqlCount);
$rowCount = $resultCount->fetch_assoc();
$total = $rowCount['total'];
$pages = ceil($total / $limit);

$genresResult = $conn->query("SELECT DISTINCT gen FROM tblFilme_Seriale WHERE gen IS NOT NULL AND gen != '' ORDER BY gen");
$genres = $genresResult->fetch_all(MYSQLI_ASSOC);

$platformsResult = $conn->query("SELECT DISTINCT platforma_streaming FROM tblFilme_Seriale WHERE platforma_streaming IS NOT NULL AND platforma_streaming != '' ORDER BY platforma_streaming");
$platforms = $platformsResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WatchlistHub</title>
    <link rel="stylesheet" href="listastyle.css">
    <style>
    .pagination {
        text-align: center;
        padding: 20px 0;
        list-style: none;
    }
    .pagination li {
        display: inline-block;
        margin-right: 5px;
    }
    .pagination li a {
        text-decoration: none;
        color: white;
        background-color: #007BFF;
        border: 1px solid #007BFF;
        padding: 5px 10px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }
    .pagination li a:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
    .pagination .current-page a {
        background-color: #004080;
        border-color: #004080;
    }
    .filter form {
        background-color: rgba(255, 255, 255, 0.5);
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    </style>
</head>
<body>
    <header>
        <a href="profile.php" class="button">Înapoi la contul tău.</a>
        <center><h1 class="title">WatchlistHub</h1></center>
        <a href="welcome.html" class="button">Deconectează-te.</a>
    </header>
    <div class="filter">
        <form action="" method="GET">
            <select name="type">
                <option value="">Toate tipurile</option>
                <option value="film" <?= (isset($_GET['type']) && $_GET['type'] == 'film') ? 'selected' : '' ?>>Film</option>
                <option value="serial" <?= (isset($_GET['type']) && $_GET['type'] == 'serial') ? 'selected' : '' ?>>Serial</option>
            </select>
            <select name="genre">
                <option value="">Toate genurile</option>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?= htmlspecialchars($genre['gen']); ?>" <?= (isset($_GET['genre']) && $_GET['genre'] == $genre['gen']) ? 'selected' : '' ?>><?= htmlspecialchars($genre['gen']); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="year" placeholder="Anul lansării" value="<?= isset($_GET['year']) ? htmlspecialchars($_GET['year']) : '' ?>">
            <select name="platform">
                <option value="">Toate platformele</option>
                <?php foreach ($platforms as $platform): ?>
                    <option value="<?= htmlspecialchars($platform['platforma_streaming']); ?>" <?= (isset($_GET['platform']) && $_GET['platform'] == $platform['platforma_streaming']) ? 'selected' : '' ?>><?= htmlspecialchars($platform['platforma_streaming']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrează</button>
            <button type="button" onclick="resetFilters()">Resetează filtrele</button>

            <script>
                function resetFilters() {
                    document.querySelector('select[name="type"]').value = '';
                    document.querySelector('select[name="genre"]').value = '';
                    document.querySelector('input[name="year"]').value = '';
                    document.querySelector('select[name="platform"]').value = '';
                    window.location.href = 'listafilme.php';
                }
            </script>
        </form>
    </div>

    <div class="hero">
        <ul>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php $slug = slugify($row["titlu"]); ?>
                    <li><a href='film.php?slug=<?= $slug ?>'><?= htmlspecialchars($row["titlu"]) ?> (<?= htmlspecialchars($row["tip"]) ?> - <?= htmlspecialchars($row["an_lansare"]) ?>)</a></li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>Nu există filme sau seriale înregistrate.</li>
            <?php endif; ?>
            <?php $conn->close(); ?>
        </ul>
    </div>
    <nav>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="<?= ($i == $page) ? 'current-page' : '' ?>">
                <a href="?page=<?= $i ?>&<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>

    <footer>
        <p>&copy; 2024 WatchlistHub. All rights reserved.</p>
    </footer>
</body>
</html>

