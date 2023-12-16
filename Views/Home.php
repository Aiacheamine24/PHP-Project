<?php

use App\Controllers\ClothesFunctions;
use App\Models\ModelClothe;

function getLimitedProducts($allClothes, $limit = 20, $offset = 0)
{
    $start = $offset;
    $end = $offset + $limit;
    return array_slice($allClothes, $start, $end);
}
$allClothes = ClothesFunctions::getAllClothes(); // Assuming getAllClothes returns all products
$products_per_page = 12; // Adjusted to limit to 20 products per page
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $products_per_page;

$vetements = getLimitedProducts($allClothes, $products_per_page, $offset);
$total_products = count($allClothes);

$utilisateur = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true ? true : false;

// echo '<br>';
// echo '<br>';
// echo '<br>';
// echo '<br>';
// var_dump($vetements[0]['photos'][0]['file_path']);
// echo '<br>';
// echo './Public/images/' . $vetements[0]['photos'][0]['file_path'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site E-commerce</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .card {
            transition: transform 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card-img-top {
            max-height: 300px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">My E-commerce Site</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php if (isset($utilisateur)) : ?>

                    <li class="nav-item">
                        <a class="nav-link" href="./Views/cart.php">
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
                                <a class="dropdown-item" href="./Views/AdminFeatures.php">Admin Features</a>
                            <?php else : ?>
                                <a class="dropdown-item" href="#">
                                    <?= htmlspecialchars($utilisateur['username']); ?>
                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="./Views/Logout.php">Logout</a>
                        </div>
                    </li>

                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./Views/Login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./Views/Register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="row">
            <?php foreach ($vetements as $vetement) : ?>
                <div class="col-md-3">
                    <a href="./Views/ProductDetails.php?id=<?= htmlspecialchars($vetement['clothes_id']) ?>" style="text-decoration: none; color: inherit;">
                        <div class="card mb-4">
                            <img src="./Public/images/<?= htmlspecialchars($vetement['photos'][0]['file_path']) ?>" alt="<?= htmlspecialchars($vetement['name']) ?>" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= htmlspecialchars($vetement['name']) ?>
                                </h5>
                                <p class="card-text">Prix:
                                    <?= htmlspecialchars($vetement['price']) ?>€
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>


        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?= $page - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo; Previous</span>
                    </a>
                </li>

                <?php for ($i = 1; $i <= ceil($total_products / $products_per_page); $i++) : ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= ($page >= ceil($total_products / $products_per_page)) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?= $page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">Next &raquo;</span>
                    </a>
                </li>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>