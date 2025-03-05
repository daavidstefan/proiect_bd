<?php
session_start();
include 'database_connection.php'; 

$email = $_POST['email'];
$password = $_POST['password']; 

$query_email = "SELECT idUtilizator, parola, nume, prenume FROM tblUtilizator WHERE email = ?";
$stmt_email = $conn->prepare($query_email);
$stmt_email->bind_param("s", $email);
$stmt_email->execute();
$result_email = $stmt_email->get_result();

if ($result_email->num_rows > 0) {
    $user = $result_email->fetch_assoc();

    if ($password == $user['parola']) { 
        $_SESSION['user_id'] = $user['idUtilizator'];
        $_SESSION['nume'] = $user['nume']; 
        $_SESSION['prenume'] = $user['prenume']; 
        header('Location: profile.php'); 
        exit();
    } else {
        header('Location: invalidlogin.html'); 
        exit();
    }
} else {
    header('Location: noemailfound.html');
    exit();
}
?>






