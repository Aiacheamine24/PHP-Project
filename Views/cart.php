<?php

use App\Controllers\ClothesFunctions;

require_once '../Controllers/ClothesFunctions.php';
require_once '../Controllers/CommandFunctions.php';
require_once  __DIR__ . '/../autoloader.php';

use App\Autoloader;

Autoloader::register();

session_start();

$utilisateur = isset($_SESSION['utilisateur']) ? $_SESSION['utilisateur'] : null;
$isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true ? true : false;

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$totalPrice = 0;

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $itemKey => $item) {
        $product_id = $item['product_id'];
        $product = ClothesFunctions::getClotheById($product_id);

        // Extract the size from the itemKey
        $size = $item['size'];

        $totalPrice += $item['quantity'] * $product['price'];
    }
}

$_SESSION['totalPrice'] = $totalPrice;

if (isset($_POST['checkout'])) {
    if (isset($_SESSION['utilisateur']) && isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        // Récupérez l'ID de l'utilisateur connecté depuis la session PHP (si disponible)
        $user_id = isset($_SESSION['utilisateur']['user_id']) ? $_SESSION['utilisateur']['user_id'] : 0;

        // Récupérez le montant total depuis la session PHP (prix total de la commande)
        $totalPrice = $_SESSION['totalPrice'];

        // Récupérez les détails de la commande depuis la session PHP (les articles dans le panier)
        $orderDetails = $_SESSION['cart'];

        // Appel de la fonction pour enregistrer les détails de la commande
        // $commande_id = insertCommandeDetails($user_id, $orderDetails, $totalPrice);

        // Rediriger vers le formulaire PayPal
        header("Location: success.php");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["item_key"])) {
        $itemKey = $_POST["item_key"];

        // Check if the item key exists in the cart
        if (isset($_SESSION["cart"][$itemKey])) {
            // Remove the item from the cart
            unset($_SESSION["cart"][$itemKey]);
        }

        // Redirect back to the cart page after removal
        header("Location: cart.php");
        exit;
    }
}

if (isset($_POST["checkout"])) {
    // On cree une commande
    $res = CommandFunctions::insertOne($_SESSION['cart'], $_SESSION['user']['user_id']);
    if ($res) {
        // On vide le panier
        unset($_SESSION['cart']);
        // On redirige vers la page de succès
        header("Location: sucess.php");
        exit;
    } else {
        echo "Erreur lors de la création de la commande";
    }
}
$utilisateur = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true ? true : false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://www.paypal.com/sdk/js?client-id=Ab7tu5WVI9fox02xsKgDLurxVs-brX2ku4QZJ7D0KqVXAOzqk4aSOiudFVjajYSDy93PnPPf_BhlObuU&currency=USD"></script>
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
        <h2>Shopping Cart</h2>

        <?php
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            foreach ($_SESSION['cart'] as $itemKey => $item) {
                $product_id = $item['product_id'];
                $product = ClothesFunctions::getClotheById($product_id);
        ?>
                <div class="row mb-3">
                    <div class="col-md-2">
                        <?php if (!empty($product['photos'][0]['file_path'])) : ?>
                            <img src="../Public/images/<?= $product['photos'][0]['file_path'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid">
                        <?php else : ?>
                            <p>No Image Available</p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h4>
                            <?= htmlspecialchars($product['name']) ?>
                        </h4>
                        <p><strong>Price:</strong>
                            <?= htmlspecialchars($product['price']) ?>€
                        </p>
                        <!-- <p><strong>Quantity:</strong>
                    <?= htmlspecialchars($item['quantity']) ?>
                </p> -->
                        <p><strong>Size:</strong>
                            <?= htmlspecialchars($item['size']) ?>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <form method="post" class="d-inline">
                            <input type="hidden" name="item_key" value="<?= htmlspecialchars($itemKey) ?>">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p>Your shopping cart is empty.</p>";
        }
        ?>

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6">
                    <h4>Total Price: <?php echo $totalPrice; ?> €</h4>
                </div>
                <div class="col-md-6 text-right">
                    <form method="post">
                        <input type="hidden" name="checkout" value="1">
                        <button type="submit" class="btn btn-primary">Checkout with PayPal</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- PayPal Button Container -->
        <div id="paypal-button-container"></div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script>
            // PayPal Button Configuration and Setup (Remove the previous script from the bottom)
            paypal.Buttons({
                style: {
                    color: 'blue' // Set the button color to blue
                },
                createOrder: function(data, actions) {
                    // This function is called when the user clicks the PayPal button
                    // It creates the order with the specified amount (value of $totalPrice in this case)
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: <?= $totalPrice ?> // Use PHP variable $totalPrice here
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    // This function is called when the user approves the PayPal transaction
                    // It captures the funds of the transaction and redirects to the "success.html" page
                    return actions.order.capture().then(function(details) {
                        console.log(details); // Display transaction details in the console
                        window.location.replace("success.html"); // Redirect to the "success.html" page
                    });
                }
            }).render('#paypal-button-container'); // Render the PayPal button inside the element with ID 'paypal-button-container'
        </script>
    </div>
</body>

</html>