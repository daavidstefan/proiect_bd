<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

include 'database_connection.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT fs.titlu, fs.an_lansare, fs.gen, fs.platforma_streaming, fs.tip 
        FROM tblFilme_Seriale fs
        JOIN tblWatchlist w ON fs.idFS = w.codFS
        WHERE w.codUtilizator = ?"; 

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilizator - WatchlistHub</title>
    <link rel="stylesheet" href="profilestyles.css">
</head>
<body>
    <header>       
        <h1 class="title">WatchlistHub</h1>
        <a href="listafilme.php" class="button">Vezi filmele/serialele disponibile.</a>
        <a href="welcome.html" class="button">Deconectează-te.</a>

    </header>
    <div class="hero">
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <h2>Salut, <?php echo htmlspecialchars(isset($_SESSION['nume']) ? $_SESSION['nume'] : 'Utilizator') . ' ' . htmlspecialchars(isset($_SESSION['prenume']) ? $_SESSION['prenume'] : ''); ?>!</h2>
        <div class="section"><strong>Filmele sau serialele pe care le-ai vizionat:</strong></div>
        <br>
        <table border="1">
            <thead>
                <tr>
                    <th>Titlu</th>
                    <th>An Lansare</th>
                    <th>Gen</th>
                    <th>Platforma Streaming</th>
                    <th>Tip</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['titlu']); ?></td>
                    <td><?php echo htmlspecialchars($row['an_lansare']); ?></td>
                    <td><?php echo htmlspecialchars($row['gen']); ?></td>
                    <td><?php echo htmlspecialchars($row['platforma_streaming']); ?></td>
                    <td><?php echo htmlspecialchars($row['tip']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <a href="recenziile_mele.php" class="button">Recenziile mele.</a>
        <br>
        <a href="detalii_cont.php" class="button">Detalii cont.</a>
    </div>
    <footer>
        <p>&copy; 2024 WatchlistHub. All rights reserved.</p>
    </footer>
</body>
</html>
<?php
$conn->close();
?>
