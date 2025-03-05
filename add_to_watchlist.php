<?php
session_start();
include 'database_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$film_id = $_POST['film_id'];
$user_id = $_SESSION['user_id'];

$checkSql = "SELECT fs.tip FROM tblWatchlist w JOIN tblFilme_Seriale fs ON w.codFS = fs.idFS WHERE w.codUtilizator = ? AND w.codFS = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("ii", $user_id, $film_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    $existingItem = $checkResult->fetch_assoc();
    $typeMessage = ($existingItem['tip'] === 'Film') ? 'Acest element este deja în watchlistul tău.' : 'Acest element este deja în watchlistul tău.';
    echo "<script>alert('$typeMessage'); window.location.href = 'profile.php';</script>";
    $checkStmt->close();
    exit;
} else {
    $sql = "INSERT INTO tblWatchlist (codUtilizator, codFS) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $film_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Filmul a fost adăugat în watchlist.');</script>";
        header('Location: profile.php');
        exit;
    } else {
        echo "<script>alert('A apărut o eroare la adăugarea filmului.');</script>";
        header('Location: profile.php');
        exit;
    }
    $stmt->close();
}

$conn->close();
?>






