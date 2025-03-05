<?php
session_start();
include 'database_connection.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['id_recenzie'])) {
    header('Location: login.html');
    exit();
}

$id_recenzie = $_POST['id_recenzie'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT idUtilizator FROM tblRecenzie WHERE idRecenzie = ? AND idUtilizator = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_recenzie, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $delete_sql = "DELETE FROM tblRecenzie WHERE idRecenzie = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $id_recenzie);
    $delete_stmt->execute();
}

$conn->close();
header('Location: recenziile_mele.php');
exit();
?>
