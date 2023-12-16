<?php
session_start();

use App\Models\ModelClothe;
use App\Models\ModelUser;

require_once __DIR__ . '/../autoloader.php';

use App\Autoloader;
use App\Controllers\ClothesFunctions;

Autoloader::register();
require_once __DIR__ . '/../Controllers/UsersFunctions.php';
require_once __DIR__ . '/../Controllers/CommandFunctions.php';
$users = UsersFunctions::getAllUsers();

$products = ModelClothe::getAllClothes();
$selectedProduct = null;

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
    // Path to the directory where the images will be stored
    $targetDirectory = __DIR__ . '\..\Public\images\\';

    foreach ($_FILES['photos']['name'] as $key => $filename) {
        // $targetPath = $targetDirectory . basename($filename);
        // Move the uploaded file to the target directory
        // if (move_uploaded_file($_FILES['photos']['tmp_name'][$key], $targetPath)) {
        $photoPaths[] = $filename; // Save the relative path to the list
        // } else {
        // echo "Erreur lors de l'upload de l'image: " . $filename;
        // exit();
        // }
    }
    // Appeler la fonction pour ajouter le vêtement à la base de données
    if (ClothesFunctions::insertOne([
        'name' => $nom,
        'price' => $prix,
        'description' => $description,
        'file_path' => $photoPaths
    ])) {
        // Rediriger vers une page de succès ou afficher un message de succès
        echo "Vêtement ajouté avec succès.";
    } else {
        // Afficher un message d'erreur si l'ajout du vêtement a échoué
        echo "Erreur lors de l'ajout du vêtement.";
    }
}

if (isset($_POST['modifier_vetement'])) {
    // Récupérer les données du formulaire
    $idVetement = $_POST['id_vetement'];
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];

    // Traiter les fichiers d'images s'ils sont présents
    $photos = [];
    if (isset($_FILES['photos'])) {
        foreach ($_FILES['photos']['name'] as $key => $fileName) {
            if ($_FILES['photos']['error'][$key] == UPLOAD_ERR_OK) {
                // Ajouter le nom du fichier au tableau $photos
                $photos[] = $fileName;
            }
        }
    }
    // Appeler la fonction pour modifier le vêtement dans la base de données
    $updated = ModelClothe::updateClothe($idVetement, [
        'name' => $nom,
        'price' => $prix,
        'description' => $description,
        'file_path' => $photos
    ]);

    if ($updated) {
        echo "Vêtement modifié avec succès.";
    } else {
        echo "Erreur lors de la modification du vêtement.";
    }
}


if (isset($_POST['supprimer_vetement'])) {
    // Récupérer les données du formulaire
    $idVetement = $_POST['id_vetement'];

    // Appeler la fonction pour supprimer le vêtement de la base de données
    $deleted = ModelClothe::deleteClothe($idVetement);
    if ($deleted === 1) {
        echo "Vêtement supprimer avec succès.";
    } else {
        echo "Erreur lors de la supprision du vêtement.";
    }
}

if (isset($_POST['set_admin'])) {
    $userId = $_POST['user_id'];

    $user = new ModelUser([
        'user_id' => $userId,
        'user_type' => 'admin'
    ]);

    // Appeler la fonction pour modifier le type de l'utilisateur
    $updated = $user->updateUser();
    echo "Utilisateur modifié avec succès. actualiser la page pour voir les changements.";
}

if (isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];

    $user = new ModelUser([
        'user_id' => $userId
    ]);
    $updated = $user->deleteUser();
    echo "Utilisateur supprimé avec succès. actualiser la page pour voir les changements.";
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
                <select class="form-control" id="select_vetement" name="id_vetement" onchange="populateFields()">
                    <option value="">-- Sélectionner un vêtement --</option>
                    <?php foreach ($products as $product) : ?>
                        <option value="<?= $product['clothes_id']; ?>">
                            <?= $product['name'] . ' - ' . $product['price'] . ' - ' . $product['description']; ?>
                            <?= $selectedProduct = $product; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id">Id : </label>
                <input type="text" class="form-control" id="id" name="id_vetement" value="<?= isset($selectedProduct['clothes_id']) ? $selectedProduct['clothes_id'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?= isset($selectedProduct['name']) ? $selectedProduct['name'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="prix">Prix :</label>
                <input type="text" class="form-control" id="prix" name="prix" value="<?= isset($selectedProduct['price']) ? $selectedProduct['price'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= isset($selectedProduct['description']) ? $selectedProduct['description'] : ''; ?></textarea>
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
                const selectedIndex = select.selectedIndex;

                // Vérifier si une option est sélectionnée
                if (selectedIndex !== -1) {
                    // Récupérer les données du produit sélectionné (stockées dans l'attribut value de l'option)
                    const selectedValues = select.options[selectedIndex].value.split('|');

                    // Remplir les champs du formulaire avec les valeurs du produit sélectionné
                    document.getElementById("id").value = selectedValues[0];
                    document.getElementById("nom").value = selectedValues[1];
                    document.getElementById("prix").value = selectedValues[2];
                    document.getElementById("description").value = selectedValues[3];
                }
            }
        </script>

    </div>

    <div class="container mt-5">
        <h2>User Management</h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col"># ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Telephone</th>
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
                            <form method="POST">
                                <input type="hidden" name="user_id" value="<?= $user['user_id']; ?>">
                                <button type="submit" name="set_admin" class="btn btn-primary">
                                    <i class="fas fa-pencil-alt"></i> Set admin
                                </button>
                            </form>

                            <form method="POST">
                                <input type="hidden" name="user_id" value="<?= $user['user_id']; ?>">
                                <button type="submit" name="delete_user" class="btn btn-danger">
                                    <i class="fas fa-pencil-alt"></i> Delete
                                </button>
                            </form>
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
                    <th scope="col"># ID</th>
                    <th scope="col">Utilisateur ID</th>
                    <th scope="col">Prix Total</th>
                    <th scope="col">Date Commande</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Remplacer "getAllCommandes" par la fonction qui récupère toutes les commandes depuis la base de données
                $commandes = CommandFunctions::getAll();

                foreach ($commandes as $commande) {
                ?>
                    <tr>
                        <th scope="row"><?= $commande['command_id']; ?></th>
                        <td><?= $commande['user_id']; ?></td>
                        <td><?= $commande['total_price']; ?></td>
                        <td><?= $commande['order_date']; ?></td>
                    </tr>
                <?php
                }
                ?>

            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>