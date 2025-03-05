<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

include 'database_connection.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT nume, prenume, email FROM tblUtilizator WHERE idUtilizator = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($userDetails = $result->fetch_assoc()) {
    $nume = htmlspecialchars($userDetails['nume']);
    $prenume = htmlspecialchars($userDetails['prenume']);
    $email = htmlspecialchars($userDetails['email']);
} else {
    echo "Nu există detalii disponibile pentru utilizatorul specificat.";
    exit;
}
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
        <a href="schimba_parola.php" class="button">Schimbă parola.</a>
    </header>
    <div class="hero">
        <h1>Detalii Cont</h1>
        <p>Nume: <?php echo $nume; ?></p>
        <p>Prenume: <?php echo $prenume; ?></p>
        <p>Email: <?php echo $email; ?></p>
    </div>
    <footer>
        <p>&copy; 2024 WatchlistHub. All rights reserved.</p>
    </footer>
</body>
</html>

