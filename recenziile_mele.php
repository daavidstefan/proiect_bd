<?php
session_start();
include 'database_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT r.idRecenzie, r.comentariu, r.rating, r.data_recenzie, fs.titlu
        FROM tblRecenzie r
        JOIN tblFilme_Seriale fs ON r.idFS = fs.idFS
        WHERE r.idUtilizator = ?
        ORDER BY r.data_recenzie DESC";

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
    <title>WatchlistHub</title>
    <link rel="stylesheet" href="adaugarecenzies.css">
</head>
<body>
    <header>
        <a href="profile.php" class="button">Înapoi la profilul tău.</a>
        <h1 class="title">WatchlistHub</h1>
        
    </header>
    <div class="hero">
        
        <?php if ($result->num_rows > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Titlu</th>
                        <th>Rating</th>
                        <th>Comentariu</th>
                        <th>Data Recenziei</th>
                        <th>Acțiune</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['titlu']); ?></td>
                        <td><?php echo htmlspecialchars($row['rating']); ?></td>
                        <td><?php echo htmlspecialchars($row['comentariu']); ?></td>
                        <td><?php echo htmlspecialchars($row['data_recenzie']); ?></td>
                        <td>
                            <form action="sterge_recenzie.php" method="post">
                                <input type="hidden" name="id_recenzie" value="<?php echo $row['idRecenzie']; ?>">
                                <input type="submit" value="Șterge" onclick="return confirm('Ești sigur că vrei să ștergi această recenzie?');">
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nu ai lăsat încă nicio recenzie.</p>
        <?php endif; ?>
    
    </div>
    <footer>
        <p>&copy; 2024 WatchlistHub. All rights reserved.</p>
    </footer>
</body>
</html>

