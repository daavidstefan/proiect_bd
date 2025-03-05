<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Platforma_Recenzii";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
$lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
$email = mysqli_real_escape_string($conn, $_POST['userid']); 
$password = mysqli_real_escape_string($conn, $_POST['password']); 

$sql = "SELECT email FROM tblUtilizator WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    header("Location: alreadyexists.html");
    exit;
} else {
    $sqlInsert = "INSERT INTO tblUtilizator (nume, prenume, email, parola) VALUES (?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ssss", $firstname, $lastname, $email, $password);
    if ($stmtInsert->execute()) {
        header("Location: succesfullyregistered.html");
        exit;
    } else {
        echo "Error: " . $stmtInsert->error;
    }
}

$stmt->close();
$conn->close();
?>
