<?php
include('pdo.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialiser la variable de recherche
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_livre = $_POST['id_livre'];
    $stock_change = intval($_POST['stock_change']);
    $operation = $_POST['operation'];

    try {
        // Commencer une transaction
        $pdo->beginTransaction();

        // Récupérer le stock actuel
        $stmt = $pdo->prepare("SELECT stock FROM livre WHERE id_livre = :id");
        $stmt->execute([':id' => $id_livre]);
        $current_stock = $stmt->fetchColumn();

        // Calculer le nouveau stock
        if ($operation === 'ajouter') {
            $new_stock = $current_stock + $stock_change;
        } else { // soustraire
            $new_stock = max(0, $current_stock - $stock_change);
        }

        // Mettre à jour le stock
        $update_stmt = $pdo->prepare("UPDATE livre SET stock = :new_stock WHERE id_livre = :id");
        $update_stmt->execute([
            ':new_stock' => $new_stock,
            ':id' => $id_livre
        ]);

        // Valider la transaction
        $pdo->commit();

        $message = "Stock mis à jour avec succès. Nouveau stock : $new_stock";
    } catch(PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        $message = "Erreur lors de la mise à jour du stock : " . $e->getMessage();
    }
}

// Préparer la requête de recherche de livres
$query = "SELECT id_livre, titre, nom_auteur FROM livre";
$params = [];

if (!empty($search_term)) {
    $query .= " WHERE titre LIKE :search OR nom_auteur LIKE :search";
    $params[':search'] = "%{$search_term}%";
}

$query .= " ORDER BY titre";

// Exécuter la requête
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Stock</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .box {
            width: 100%;
            max-width: 500px;
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group select, 
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .book-results {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 5px;
        }
        .book-results a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #eee;
        }
        .book-results a:hover {
            background-color: #f1f1f1;
        }
        .form-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:first-child {
            background-color: #4CAF50;
            color: white;
        }
        .btn:last-child {
            background-color: #2196F3;
            color: white;
        }
    </style>
</head>
<body>
    <div class="admin">
        <div class="top-bar">
            <div class="logo">
                <a href="Accueil.html">
                    <img src="Icon\logo.png" alt="Logo">
                </a>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Rechercher...">
            </div>
            <div class="icons-header-admin">
                <div class="icons">
                    <div class="deconnexion">
                        <a href="Admin_ou_lecteur.html" class="deconnecte">Déconnexion</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="categories">
            <a href="Accueil_admin.html">Accueil</a>
            <a href="Stock_admin.html" class="active">Stock</a>
            <a href="nouveau_livre_admin.html">Nouveaux livres</a>
            <a href="rachat_de_livre_admin.html">Rachats de livres</a>
            <a href="vitrine_admin.html">Vitrine</a>
        </div>

        <div class="container">
            <div class="box">
                <h2>Modifier le Stock</h2>
                
                <?php if(isset($message)): ?>
                    <div class="message"><?= $message ?></div>
                <?php endif; ?>

                <form method="GET" action="">
                    <div class="form-group">
                        <label for="search">Rechercher un Livre</label>
                        <input type="text" id="search" name="search" placeholder="Saisissez le titre ou l'auteur" 
                               value="<?= htmlspecialchars($search_term) ?>">
                    </div>
                </form>

                <?php if (!empty($search_term)): ?>
                    <div class="book-results">
                        <?php if (count($livres) > 0): ?>
                            <?php foreach($livres as $livre): ?>
                                <a href="Stock_admin.php?search=<?= urlencode($search_term) ?>&selected_book=<?= $livre['id_livre'] ?>">
                                    <?= htmlspecialchars($livre['titre']) ?> - <?= htmlspecialchars($livre['nom_auteur']) ?>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun livre trouvé.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php 
                $selected_book_id = isset($_GET['selected_book']) ? intval($_GET['selected_book']) : null;
                if ($selected_book_id): 
                    // Récupérer les détails du livre sélectionné
                    $stmt = $pdo->prepare("SELECT * FROM livre WHERE id_livre = :id");
                    $stmt->execute([':id' => $selected_book_id]);
                    $selected_book = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                    <form method="POST" action="">
                        <input type="hidden" name="id_livre" value="<?= $selected_book_id ?>">
                        <div class="form-group">
                            <label>Livre Sélectionné</label>
                            <input type="text" readonly value="<?= htmlspecialchars($selected_book['titre']) ?> - <?= htmlspecialchars($selected_book['nom_auteur']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="operation">Opération</label>
                            <select id="operation" name="operation" required>
                                <option value="ajouter">Ajouter au stock</option>
                                <option value="soustraire">Soustraire du stock</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="stock_change">Nombre d'exemplaires</label>
                            <input type="number" id="stock_change" name="stock_change" min="1" required>
                        </div>

                        <div class="form-buttons">
                            <button type="submit" class="btn">Mettre à jour le Stock</button>
                            <a href="description_livre_admin.php?id=<?= $selected_book_id ?>" class="btn">Afficher Détails</a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>