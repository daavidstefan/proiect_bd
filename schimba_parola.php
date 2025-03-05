<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

include 'database_connection.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password != $confirm_password) {
        $message = 'Parolele noi nu coincid.';
    } elseif ($new_password == $old_password) {
        $message = 'Parola nouă nu poate fi identică cu cea veche.';
    } else {
        $sql = "SELECT parola FROM tblUtilizator WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows === 1) {
            $userDetails = $result->fetch_assoc();
            if ($old_password == $userDetails['parola']) {
                $sql = "UPDATE tblUtilizator SET parola = ? WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $new_password, $email);
                $stmt->execute();

                if ($stmt->affected_rows === 1) {
                    echo "<script>alert('Parola a fost schimbată cu succes!'); window.location='profile.php';</script>";
                } else {
                    $message = 'Actualizarea parolei a eșuat.';
                }
            } else {
                $message = 'Parola veche nu este corectă.';
            }
        } else {
            $message = 'Utilizatorul nu a fost găsit.';
        }
    }

    if ($message != '') {
        echo "<script>alert('$message');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WatchlistHub</title>
    <link rel="stylesheet" href="adaugarecenzies.css">
    <style>
        .form-group {
            margin-bottom: 15px; 
        }
        label, input {
            display: block; 
            width: 100%;
        }
        input {
            margin-top: 5px; 
        }
        button {
            margin-top: 20px; 
        }
    </style>
</head>
<body>
    <header>
        <a href="profile.php" class="button">Înapoi la profilul tău.</a>
        <h1 class="title">WatchlistHub</h1>
    </header>
    <div class="hero">
        <h1>Schimbă Parola</h1>
        <form action="schimba_parola.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="old_password">Parola Veche:</label>
                <input type="password" id="old_password" name="old_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">Parola Nouă:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmă Parola Nouă:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Schimbă Parola</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 WatchlistHub. All rights reserved.</p>
    </footer>
</body>
</html>
