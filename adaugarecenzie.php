<?php
session_start();
include 'database_connection.php';

if (!isset($_GET['idFilm'])) {
    echo "Filmul specificat nu este valid.";
    exit;
}

$idFilm = $_GET['idFilm'];
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
        <a href="profile.php" class="button">Înapoi la contul tău.</a>
        <h1 class="title">WatchlistHub</h1>
        <a href="welcome.html" class="button">Deconectează-te.</a>
    </header>
    <div class="hero">
        <br>
        <form action="proceseaza_recenzie.php" method="post">
        <input type="hidden" name="idFilm" value="<?php echo $idFilm; ?>">
        <label for="email">Emailul cu care te-ai conectat:</label>
        <input type="email" name="email" id="email" required><br>
        <br>
        <label for="rating">Rating:</label>
        <select name="rating" id="rating" required>
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>

        </select><br>
        <br>
        <label for="comentariu">Comentariu:</label>
        <textarea name="comentariu" id="comentariu" rows="4" cols="50" required></textarea><br>
        <br>
        <input type="submit" value="Trimite Recenzia">
    </form>
    </div>
    <footer>
        <p>&copy; 2024 WatchlistHub. All rights reserved.</p>
    </footer>
</body>
</html>

