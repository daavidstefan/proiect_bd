<?php
session_start();
include 'database_connection.php';

if (!isset($_POST['idFilm'], $_POST['email'], $_POST['rating'], $_POST['comentariu'])) {
    die('Toate câmpurile sunt necesare pentru a adăuga o recenzie.');
}

$idFilm = $_POST['idFilm'];
$email = $_POST['email'];
$rating = filter_var($_POST['rating'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10]]);
$comentariu = mysqli_real_escape_string($conn, $_POST['comentariu']);
$data_recenzie = date('Y-m-d');

$idUser = getUserIdByEmail($email, $conn);
if (!$idUser) {
    die('Eroare: Adresa de email nu este asociată cu niciun utilizator înregistrat.');
}

if (!$rating) {
    die('Ratingul trebuie să fie un număr între 1 și 10.');
}

$sql = "INSERT INTO tblRecenzie (idUtilizator, idFS, rating, comentariu, data_recenzie) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Eroare la pregătirea interogării: " . $conn->error;
    exit;
}

$stmt->bind_param("iiiss", $idUser, $idFilm, $rating, $comentariu, $data_recenzie);
if ($stmt->execute()) {
    echo "Recenzia a fost adăugată cu succes!";
    header("Location: profile.php?id=$idFilm");
    exit;
} else {
    echo "Eroare la adăugarea recenziei: " . $stmt->error;
}

$stmt->close();
$conn->close();

function getUserIdByEmail($email, $conn) {
    $query = "SELECT idUtilizator FROM tblUtilizator WHERE email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Eroare pregătire interogare: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['idUtilizator'];
    }
    return false;
}
?>
