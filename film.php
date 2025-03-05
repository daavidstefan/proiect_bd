<?php
session_start();
include 'database_connection.php';

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    echo "Niciun film specificat.";
    exit;
}

$slug = $_GET['slug'];
$slug = mysqli_real_escape_string($conn, $slug);

$sql = "SELECT fs.*, f.durata_min, s.nr_sezoane FROM tblFilme_Seriale fs
        LEFT JOIN tblFilm f ON fs.idFS = f.idFS
        LEFT JOIN tblSerial s ON fs.idFS = s.idFS
        WHERE REPLACE(LOWER(fs.titlu), ' ', '-') = '$slug' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Filmul sau serialul nu a fost găsit.";
    $conn->close();
    exit;
}

$film = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($film['titlu']); ?></title>
    <link rel="stylesheet" href="film.css">
    <script>
        function submitForm() {
            document.getElementById("watchlistForm").submit();
        }
        document.documentElement.style.setProperty('--scrollbar-width', (window.innerWidth - document.documentElement.clientWidth) + 'px');
    </script>
</head>
<body>
    <header>
        <a href="listafilme.php" class="button">Vezi filmele/serialele disponibile.</a>
        <center><h1 class="title">WatchlistHub</h1></center>
        <form id="watchlistForm" action="add_to_watchlist.php" method="post">
            <input type="hidden" name="film_id" value="<?php echo $film['idFS']; ?>">
            <button type="button" class="button" onclick="submitForm()">Adaugă acest film în watchlist.</button>
        </form>

    </header>
    <div class="hero">
        <h1><?php echo htmlspecialchars($film['titlu']); ?></h1>
        <p><strong>Anul lansării:</strong> <?php echo htmlspecialchars($film['an_lansare']); ?></p>
        <p><strong>Gen:</strong> <?php echo htmlspecialchars($film['gen']); ?></p>
        <p><strong>Platforma de streaming:</strong> <?php echo htmlspecialchars($film['platforma_streaming']); ?></p>
        <p><strong>Tip:</strong> <?php echo htmlspecialchars($film['tip']); ?></p>
        <?php if ($film['tip'] == 'Film') : ?>
            <p><strong>Durata:</strong> <?php echo htmlspecialchars($film['durata_min']); ?> minute</p>
        <?php elseif ($film['tip'] == 'Serial') : ?>
            <p><strong>Număr sezoane:</strong> <?php echo htmlspecialchars($film['nr_sezoane']); ?></p>
        <?php endif; ?>
    </div>
    <div class="film-description">
        <div class="film-image">
            <img src="<?php echo "/site_BD/images/" . htmlspecialchars($slug) . ".png"; ?>" alt="Cover image for <?php echo htmlspecialchars($slug); ?>" style="width: 400px; height:auto;">
        </div>
        <div class="film-details">
            <?php
            $descriptionPath = __DIR__ . "/descriptions/" . htmlspecialchars($slug) . ".txt";
            if (file_exists($descriptionPath)) {
                $description = file_get_contents($descriptionPath);
                echo nl2br(htmlspecialchars($description));
            } else {
                echo "<p>Descrierea nu este disponibilă.</p>";
            }
            ?>
        </div>
    </div>

        <div class="reviews-container">
        <h2>Recenzii</h2>
        <a href="adaugarecenzie.php?idFilm=<?php echo $film['idFS']; ?>">Adaugă Recenzie</a>

        <ul>
            <?php
            if ($conn && $conn->ping()) {
                $recenzie_sql = "SELECT r.*, u.nume, u.prenume FROM tblRecenzie r
                                 INNER JOIN tblUtilizator u ON r.idUtilizator = u.idUtilizator
                                 WHERE r.idFS = {$film['idFS']}";
                $recenzie_result = $conn->query($recenzie_sql);

                if ($recenzie_result->num_rows > 0) {
                    while ($recenzie = $recenzie_result->fetch_assoc()) {
                        echo "<li><p><strong>Utilizator:</strong> " . htmlspecialchars($recenzie['nume']) . " " . htmlspecialchars($recenzie['prenume']) . "</p>";
                        echo "<p><strong>Rating:</strong> " . htmlspecialchars($recenzie['rating']) . "/10</p>";
                        echo "<p><strong>Comentariu:</strong> " . nl2br(htmlspecialchars($recenzie['comentariu'])) . "</p>";
                        echo "<p><strong>Data recenziei:</strong> " . htmlspecialchars($recenzie['data_recenzie']) . "</p></li>";
                        echo "<br>";
                    }
                } else {
                    echo "<li>Nu există recenzii pentru acest film.</li>";
                }
            } else {
                echo "<li>Eroare de conectare la baza de date.</li>";
            }
            ?>
        </ul>
    </div>

    <footer>
        <p>&copy; 2024 WatchlistHub. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>




