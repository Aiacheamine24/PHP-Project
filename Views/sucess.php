<?php
session_start();
$utilisateur = isset($_SESSION['utilisateur']) ? $_SESSION['utilisateur'] : null;
$isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true ? true : false;


unset($_SESSION['cart']);
unset($_SESSION['totalPrice']);

$utilisateur = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true ? true : false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
                                <a class="dropdown-item" href="./AdminFeatures.php">Admin Features</a>
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
                        <a class="nav-link" href="./Login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./Register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>Payment Successful!</h2>
                <p>Thank you for your payment.</p>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>