<?php
session_start();
include 'database_connection.php';

$sqlDetails = "SELECT f.idFS, f.titlu, s.nr_sezoane, f.an_lansare, f.gen, f.platforma_streaming, f.tip
               FROM tblFilme_Seriale AS f
               JOIN tblSerial AS s ON f.idFS = s.idFS
               WHERE f.titlu = 'Stranger Things'";
$resultDetails = $conn->query($sqlDetails);
$serial = $resultDetails->fetch_assoc();

$sqlReviews = "SELECT r.rating, r.comentariu, u.nume, u.prenume
               FROM tblRecenzie AS r
               JOIN tblUtilizator AS u ON r.idUtilizator = u.idUtilizator
               WHERE r.idFS = " . $serial['idFS'];
$resultReviews = $conn->query($sqlReviews);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($serial['titlu']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($serial['titlu']); ?></h1>
        <p><strong>Număr sezoane:</strong> <?php echo $serial['nr_sezoane']; ?></p>
        <p><strong>An lansare:</strong> <?php echo $serial['an_lansare']; ?></p>
        <p><strong>Gen:</strong> <?php echo $serial['gen']; ?></p>
        <p><strong>Platforma de streaming:</strong> <?php echo $serial['platforma_streaming']; ?></p>
        <p><strong>Tip:</strong> <?php echo $serial['tip']; ?></p>
    </header>
    <div>
        <h2>Recenzii</h2>
        <?php
        if ($resultReviews->num_rows > 0) {
            echo "<ul>";
            while ($review = $resultReviews->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($review['nume']) . " " . htmlspecialchars($review['prenume']) . ": " .
                    htmlspecialchars($review['rating']) . "/10 - " . htmlspecialchars($review['comentariu']) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Nu există recenzii pentru acest serial.</p>";
        }
        ?>
    </div>
    <footer>
        <p>&copy; 2024 WatchlistHub. All rights reserved.</p>
    </footer>
</body>
</html>

<?php $conn->close(); ?>
