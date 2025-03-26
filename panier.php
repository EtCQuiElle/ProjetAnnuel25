<?php
include('pdo.php');
session_start();

// Debug - À désactiver en production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialisation garantie du panier
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Traitement de l'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $livreId = $_POST['id_livre'] ?? null;
    $prix = (float)($_POST['prix'] ?? 0);
    
    if ($livreId) {
        $livreExiste = false;
        
        // Vérification de l'existence du livre
        foreach ($_SESSION['panier'] as $item) {
            if ($item['id_livre'] == $livreId) {
                $livreExiste = true;
                break;
            }
        }

        if (!$livreExiste) {
            $_SESSION['panier'][] = [
                'id_livre' => $livreId,
                'titre' => $_POST['titre'] ?? 'Titre inconnu',
                'prix' => $prix,
                'image' => $_POST['image'] ?? 'image_livre/placeholder.jpg'
            ];
            $_SESSION['message'] = "Livre ajouté au panier!";
        } else {
            $_SESSION['message'] = "Ce livre est déjà dans votre panier";
        }
    }
    
    header("Location: panier.php");
    exit();
}

// Traitement de la suppression avec réindexation
// Traitement de la suppression avec réindexation
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id_livre'])) {
    foreach ($_SESSION['panier'] as $key => $item) {
        if ($item['id_livre'] == $_GET['id_livre']) {
            unset($_SESSION['panier'][$key]);
            // Réindexation du tableau après suppression
            $_SESSION['panier'] = array_values($_SESSION['panier']);
            $_SESSION['message'] = "Livre retiré du panier";
            break;
        }
    }
    header("Location: panier.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- header -->
    <div class="top-bar">
        <div class="logo">
            <a href="Accueil.html">
                <img src="Icon/logo.png" alt="logo">
            </a>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Rechercher...">
        </div>
        <div class="icons-header">
            <div class="icons">
                <a href="coup_de_coeur.html">
                    <img src="Icon/coeur.png" alt="Coup de Coeur">
                </a>
                <a href="panier.php"> <!-- Changé de panier.html à panier.php -->
                    <img src="Icon/cart.png" alt="Panier">
                </a>
                <a href="acces_client.html">
                    <img src="Icon/profil_pictures.png" alt="Profil">
                </a>
            </div>
            <div class="deconnexion">
                <a href="#notificationModal" class="notification-box">Notifications</a>
                <a href="Admin_ou_lecteur.html" class="deconnecte">Déconnexion</a>
            </div>
        </div>
    </div>

    <!-- Modal Notifications -->
    <div id="notificationModal" class="modal-notification">
        <div class="modal-notification-content">
            <a href="#" class="close">&times;</a>
            <p>Voici les notifications</p>
        </div>
    </div>

    <div class="categories">
        <a href="Accueil.html">Accueil</a>
        <a href="blog.html">Blog</a>
        <a href="catalogue_livre.php" class="active">Livre</a>
        <a href="rachats_de_livres.html">Rachats de livres</a>
        <a href="messagerie.html">Messagerie</a>
    </div>

<!-- Contenu principal -->
    <main>
        <h1>Votre Panier</h1>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert-message">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <div class="cart-container">
            <?php if (empty($_SESSION['panier'])): ?>
                <p class="empty-cart">Votre panier est vide</p>
            <?php else: ?>
                <ul class="cart-list">
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['panier'] as $item): 
                        $prix = (float)($item['prix'] ?? 0);
                        $total += $prix;
                    ?>
                    <li class="cart-item">
                        <img src="<?= htmlspecialchars($item['image']) ?>" 
                             alt="<?= htmlspecialchars($item['titre']) ?>" 
                             class="cart-item-image">
                        <div class="cart-item-details">
                            <p class="cart-item-name"><?= htmlspecialchars($item['titre']) ?></p>
                            <p class="cart-item-price">Prix: <?= number_format($prix, 2) ?>€</p>
                            <p class="cart-item-id">Réf: <?= htmlspecialchars($item['id_livre']) ?></p>
                        </div>
                        <a href="panier.php?action=remove&id_livre=<?= $item['id_livre'] ?>" class="remove-item">Supprimer</a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                
                <div class="cart-summary">
                    <p class="cart-total">Total: <?= number_format($total, 2) ?>€</p>
                    <div class="cart-actions">
                        <a href="catalogue_livre.php" class="continue-shopping">Continuer mes achats</a>
                        <a href="checkout.php" class="checkout-button">Passer commande</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>


<!-- footer -->
    <footer>
        <p>&copy; <?= date('Y') ?> Catalogue de Livres. Tous droits réservés.</p>
    </footer>
</body>
</html>