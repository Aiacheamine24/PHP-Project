<?php
require_once '../Controllers/UsersFunctions.php';

use App\Autoloader;

require_once __DIR__ . '/../Autoloader.php';
Autoloader::register();

if (isset($_POST['inscription'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];

    try {
        $id = UsersFunctions::register($username, $email, $password);
        if ($id) {
            header('Location: login.php');
        }
    } catch (\PDOException $e) {
        if ($e->getCode() == '23000') {
            $errorMessage = "L'adresse e-mail est déjà utilisée. Veuillez en choisir une autre.";
        } else {
            $errorMessage = "Une erreur s'est produite : " . $e->getMessage();
        }
    } catch (\Throwable $th) {
        $errorMessage = "Une erreur inattendue s'est produite : " . $th->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription d'utilisateur</title>
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
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h2 class="mt-5 mb-4">Inscription d'utilisateur</h2>
        <form method="POST">
            <div class="form-group">
                <label for="email">E-mail :</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <!-- <div class="form-group">
                <label for="phone">Téléphone :</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div> -->
            <button type="submit" name="inscription" class="btn btn-primary">Inscription</button>
            <?php
            if (isset($errorMessage)) {
                echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
            }
            ?>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>