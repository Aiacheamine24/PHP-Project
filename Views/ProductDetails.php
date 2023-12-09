<?php
require_once '../Controllers/ClothesFunctions.php';
require_once '../autoloader.php';

use App\Autoloader;
use App\Controllers\ClothesFunctions;

Autoloader::register();

session_start();
$utilisateur = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true ? true : false;

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $product = ClothesFunctions::getClotheById($product_id);
    var_dump($product);
    echo '<br>';

    foreach ($product as $key => $value) {
        if ($key === 'file_path') {
            echo '<br>' . $value . '<br>';
        }
    }
} else {
    header("Location: index.php");
    exit;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .product-image {
            max-width: 300px;
            margin: 0 auto;
        }

        .product-image img {
            width: 100%;
            height: auto;
        }
    </style>
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
                        <a class="nav-link" href="cart.php">
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
                                <a class="dropdown-item" href="./AdminFeatures.php">Admin Features</a>
                            <?php else : ?>
                                <a class="dropdown-item" href="#">
                                    <?= htmlspecialchars($utilisateur['nom']); ?>
                                </a>
                                <a class="dropdown-item" href="#">
                                    <?= htmlspecialchars($utilisateur['telephone']); ?>
                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="./Logout.php">Logout</a>
                        </div>
                    </li>

                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./Login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./Register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <style>
        .carousel-control-prev,
        .carousel-control-next {
            width: auto;
            background: none;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.5);
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        .carousel-control-prev {
            left: -10%;
        }

        .carousel-control-next {
            right: -10%;
        }
    </style>

    <div class="container mt-5">
        <h2>
            <?= htmlspecialchars($product['name']) ?>
        </h2>
        <div class="product-image">
            <div id="productCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php $isActive = true;
                    foreach ($product['photo'] as $photo) : ?>
                        <div class="carousel-item <?= $isActive ? 'active' : '' ?>">
                            <img src="../Public/images/<? htmlspecialchars($photo['file_path']) ?>" class="d-block w-100" alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                    <?php $isActive = false;
                    endforeach; ?>
                </div>
                <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

        <form action="addToCart.php" method="post">
            <input type="hidden" name="product_id" value="<?= $product_id ?>">

            <div class="form-group">
                <label for="quantity">Quantité :</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1">
            </div>

            <div class="form-group">
                <label for="size">Taille :</label>
                <select class="form-control" id="size" name="size">
                    <option value="small">Small</option>
                    <option value="medium">Medium</option>
                    <option value="large">Large</option>
                    <option value="xlarge">X-Large</option>
                </select>
            </div>

            <p><strong>Prix:</strong>
                <?= htmlspecialchars($product['price']) ?>€
            </p>
            <p><strong>Description:</strong>
                <?= htmlspecialchars($product['description']) ?>
            </p>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i> Ajouter au panier
            </button>
        </form>
    </div>

    <footer class="bg-dark text-white mt-5 p-4 text-center">
        © 2023 E-commerce Site. All Rights Reserved.
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>