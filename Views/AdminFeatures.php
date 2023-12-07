<?php
session_start();

use App\Models\ModelClothe;
use App\Models\ModelUser;

require_once __DIR__ . '/../autoloader.php';

use App\Autoloader;

Autoloader::register();
require_once __DIR__ . '/../Controllers/UsersFunctions.php';
$users = UsersFunctions::getAllUsers();
var_dump($users);
$products = [];
// for ($i = 0; $i < count($products); $i++) {
//     var_dump($products[$i]);
// }


$utilisateur = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true ? true : false;

if (isset($_SESSION['login']) && $_SESSION['login']) {
} else {
    // Rediriger vers la page de connexion
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['logout'])) {
    // Déconnexion de l'utilisateur
    session_destroy();
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['ajouter_vetement'])) {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];

    // Traiter les fichiers d'images
    $photoPaths = [];
    $targetDirectory = "../img/"; // Adjust this if necessary

    foreach ($_FILES['photos']['name'] as $key => $filename) {
        $targetPath = $targetDirectory . basename($filename);
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['photos']['tmp_name'][$key], $targetPath)) {
            $photoPaths[] = $targetPath; // Save the relative path to the list
        } else {
            echo "Erreur lors de l'upload de l'image: " . $filename;
            exit();
        }
    }

    // // Appeler la fonction pour ajouter le vêtement à la base de données
    // if (ajouterVetement($nom, $prix, $description, $photoPaths)) {
    //     // Rediriger vers une page de succès ou afficher un message de succès
    //     echo "Vêtement ajouté avec succès.";
    // } else {
    //     // Afficher un message d'erreur si l'ajout du vêtement a échoué
    //     echo "Erreur lors de l'ajout du vêtement.";
    // }
}

if (isset($_POST['modifier_vetement'])) {
    // Récupérer les données du formulaire
    $idVetement = $_POST['id_vetement'];
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];


    // Traiter les fichiers d'images s'ils sont présents
    $photos = [];
    $uploadDir = "./img/"; // Répertoire où les images sont stockées
    if (isset($_FILES['photos'])) {
        foreach ($_FILES['photos']['name'] as $fileName) {
            if ($fileName) { // Vérifiez que le fichier a un nom
                $photos[] = $uploadDir . $fileName; // Construisez le chemin relatif
            }
        }
    }

    // // Appeler la fonction pour modifier le vêtement dans la base de données
    // if (modifierVetement($idVetement, $nom, $prix, $description, $photos)) {
    //     echo "Vêtement modifié avec succès.";
    // } else {
    //     echo "Erreur lors de la modification du vêtement.";
    // }
}

if (isset($_POST['supprimer_vetement'])) {
    // Récupérer les données du formulaire
    $idVetement = $_POST['id_vetement'];

    // if (supprimervetement($idVetement)) {
    //     echo "Vêtement supprimer avec succès.";
    // } else {
    //     echo "Erreur lors de la supprision du vêtement.";
    // }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Features</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="../index.php">My E-commerce Site</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php if (isset($utilisateur)) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../cart.php">
                            <i class="fas fa-shopping-cart"></i>
                            Cart
                            <?php
                            $cartItemCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                            echo "($cartItemCount)";
                            ?>
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= htmlspecialchars($utilisateur['email']); ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                            <?php if (isset($isAdmin) && $isAdmin) : ?>
                                <a class="dropdown-item" href="#">User: Admin</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Admin Features</a>
                            <?php else : ?>
                                <a class="dropdown-item" href="#">
                                    <?= htmlspecialchars($utilisateur['nom']); ?>
                                </a>
                                <a class="dropdown-item" href="#">
                                    <?= htmlspecialchars($utilisateur['telephone']); ?>
                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="./logout.php">Logout</a>
                        </div>
                    </li>

                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Ajouter un vêtement</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" class="form-control" id="nom" name="nom" value="Clothe " required>
            </div>
            <div class="form-group">
                <label for="prix" value="0.01">Prix :</label>
                <input type="text" class="form-control" id="prix" name="prix" required>
            </div>
            <div class="form-group">
                <label for="description" value="Clothe Number ">Description :</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="photos">Photos :</label>
                <input type="file" class="form-control-file" id="photos" name="photos[]" multiple required>
            </div>
            <button type="submit" name="ajouter_vetement" class="btn btn-primary">Ajouter</button>
        </form>

        <hr>

        <h2>Modifier un vêtement</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="select_vetement">Sélectionner un vêtement :</label>
                <select class="form-control" id="select_vetement" name="select_vetement" onchange="populateFields()">
                    <option value="">-- Sélectionner un vêtement --</option>
                    <?php foreach ($products as $product) : ?>
                        <option value="<?= $product['id']; ?>">
                            <?= $product['nom'] . ' - ' . $product['prix'] . ' - ' . $product['description']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </div>
            <div class="form-group">
                <label for="nom">Id : </label>
                <input type="text" class="form-control" id="id" name="id_vetement">
            </div>
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" class="form-control" id="nom" name="nom">
            </div>
            <div class="form-group">
                <label for="prix">Prix :</label>
                <input type="text" class="form-control" id="prix" name="prix">
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="photos">Photos :</label>
                <input type="file" class="form-control" id="photos" name="photos[]" multiple>
            </div>

            <button type="submit" name="modifier_vetement" class="btn btn-primary">Modifier</button>
            <button type="submit" name="supprimer_vetement" class="btn btn-danger">Supprimer</button>
        </form>
        <script>
            function populateFields() {
                const select = document.getElementById("select_vetement");
                const selectedId = select.value;

                document.getElementById("id").value = selectedId;
            }
        </script>

    </div>

    <div class="container mt-5">
        <h2>User Management</h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Telephone</th>
                    <th scope="col">Admin</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <th scope="row">
                            <?= $user['user_id']; ?>
                        </th>
                        <td>
                            <?= $user['username']; ?>
                        </td>
                        <td>
                            <?= $user['email']; ?>
                        </td>
                        <td>
                            <?php
                            if ($user['user_type'] === 'admin') {
                                echo "<p style='color: red'>Admin User</p>";
                            } else {
                                echo "Ordinaire User";
                            }
                            ?>
                        </td>
                        <td>
                            <a href="../edit_user.php?id=<?= $user['id']; ?>" class="btn btn-primary">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="container mt-5">
        <h2>Commandes</h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Utilisateur ID</th>
                    <th scope="col">Prix Total</th>
                    <th scope="col">Date Commande</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Crash Page : tbody-->
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>