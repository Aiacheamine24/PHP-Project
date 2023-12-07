<?php

use App\Models\ModelUser;
// Incure le Controller UsersFunctions.php
require_once '../Controllers/UsersFunctions.php';
// Incure le fichier Autoloader.php
use App\Autoloader;
// Dans vos fichiers où vous utilisez des classes sans les inclure manuellement
require_once __DIR__ . '/../Autoloader.php';
Autoloader::register();
// Demarrer la session
session_start();
// Recuperer Email et password et appler le controller
if (isset($_POST['connexion'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = UsersFunctions::login($email, $password);
    //ajouter le user dans la session
    if ($user) {
        $_SESSION['login'] = true;
        $_SESSION['user'] = $user;
        if ($user['user_type'] === 'admin') {
            $_SESSION['isAdmin'] = true;
        } else {
            $_SESSION['isAdmin'] = false;
        }
        header('Location: ../index.php');
    } else {
        $_SESSION['errorLogin'] = 'Email ou mot de passe incorrect';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="../index.php">E-commerce Site</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
                <?php
                if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
                    echo '
                    <li class="nav-item">
                        <a class="nav-link" href="adminFeatures.php">Admin Features</a>
                    </li>';
                }
                ?>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h2 class="mt-5 mb-4">Page de Connexion</h2>
        <form method="POST">
            <div class="form-group">
                <label for="email">E-mail :</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" name="connexion" class="btn btn-primary">Connexion</button>
            <?php
            if (isset($_SESSION['errorLogin'])) {
                echo '<br><br><h5 style="color:red;">' . $_SESSION['errorLogin'] . '</h5>';
                unset($_SESSION['errorLogin']);
            }
            ?>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>