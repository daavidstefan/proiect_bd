<?php
session_start();
include 'database_connection.php';

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    echo "Niciun film specificat.";
    exit;
}

$slug = $_GET['slug'];

$titlu = str_replace('-', ' ', $slug);
$titlu = mysqli_real_escape_string($conn, ucwords($titlu));

$sql = "SELECT * FROM tblFilme_Seriale WHERE REPLACE(LOWER(titlu), ' ', '-') = '$slug' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Filmul sau serialul nu a fost găsit.";
    exit;
}

$film = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($film['titlu']); ?></title>
    <link rel="stylesheet" href="listastyle.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($film['titlu']); ?></h1>
        <p><strong>Anul lansării:</strong> <?php echo htmlspecialchars($film['an_lansare']); ?></p>
        <p><strong>Gen:</strong> <?php echo htmlspecialchars($film['gen']); ?></p>
        <p><strong>Platforma de streaming:</strong> <?php echo htmlspecialchars($film['platforma_streaming']); ?></p>
        <p><strong>Tip:</strong> <?php echo htmlspecialchars($film['tip']); ?></p>
    </header>
    <footer>
        <p>&copy; 2024 WatchlistHub. All rights reserved.</p>
    </footer>
</body>
</html>
